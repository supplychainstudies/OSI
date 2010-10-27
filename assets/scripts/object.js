/**
 * Sourcemap.Object: Representation of Sourcemap data.
 *
 * @version 0.7.5
 * @author sourcemap@media.mit.edu
 * @package sourcemap
 * @subpackage js
 */

if ( typeof(Sourcemap) == "undefined" ) { Sourcemap = {}; }

Sourcemap.Object = function(obj) {
	jQuery.extend(this, obj);
	
	//===== Object Setter Functions =====
	this.setName = function(val) {
		this.name = Sourcemap.Util.utf8encode(val);
	};
	this.setDescription = function(val) {
		this.description = Sourcemap.Util.utf8encode(val);	   
		$("body").trigger("objectDescriptionUpdated", [val]);							
	};
	this.setImageLink = function(val) {
		this.imagelink = Sourcemap.Util.utf8encode(val);
	};
	this.setPermissions = function(val) {};
	this.setOrigin = function(val) {
		// TODO Object Depency on Smap existing
		SMap.geoCoder.getLocations(val, function(response){
				if(response.Status.code != 200) { 
					$("body").trigger("originGeoError", ["object"]);
					SMap.removeOrigin(); 
				} 
				else {
					if(typeof(response.Placemark[0]) != 'undefined') {
						place = response.Placemark[0];		
					    point = new GLatLng(place.Point.coordinates[1], place.Point.coordinates[0]);

						if(point != null && (point.lat() != 0 && point.lng() != 0) && response.Status.code == 200){						
							$("body").trigger("originGeoMessage", ["object", place.address]);	
							var adjPoint = point.lat() + "|" + point.lng();
							objectConfig.latlon = adjPoint;			
						
					       	SMap.setOrigin(val, objectConfig.parts); SMap.refreshMap(); //TODO objconfig dep
							objectConfig.origin = Sourcemap.Util.utf8encode(place.address); // TODO 
						}
						else { 
							$("body").trigger("originGeoError", ["object"]); 
							SMap.removeOrigin(); 
						}
					}
				}
				//TODO look at this
				for (var i=0;i<objectConfig.parts.length;i++) {
					objectConfig.setPartOrigin(i, objectConfig.parts[i].origin, false);
				}
			});
	};
	this.setDestination = function(val) {}; // TODO Remove?
	this.setVia = function(val) {}; // TODO Remove?	
	this.setWeight = function(val) { this.weight = val; };
	this.setShipping = function(val) { this.shipping = val; };
	this.setEmissions = function(val) { this.emissions = val; };
  	this.setEmbodiedEmissions = function(val) { this.embodied = val; };
  	this.setTransportEmissions = function(val) { this.transport = val; };
	this.setUPC = function(val) { this.upc = val; };

        this.setProcessEmissions = function(val) {this.processEmissions =val;};

	//===== Object Functions =====\\	
	this.save = function() {
	        var saveData = "data="+Sourcemap.Util.utf8encode(JSON.stringify(objectConfig));
		$("body").trigger("savingObject", [objectConfig]); 
	
		$.post(Sourcemap.siteurl+"objects/save",  saveData, this.saveConfirmation);
	};
	this.saveConfirmation = function(data) {
		for ( var i = 0; i < objectConfig.parts.length; i++ ) {
			if( typeof(objectConfig.parts[i]) != 'undefined') {
				if(objectConfig.parts[i].flag == "remove") {
					objectConfig.parts[i] == null;
				} else {
					objectConfig.parts[i].flag = "unchanged";
				}
			}
		}
		Sourcemap.pageTracker._trackEvent("SMap", "Save", "Sourcemap "+objectConfig.type+"-"+objectConfig.oid+" saved");				
		$("body").trigger("saveObjectConfirmation", [data]); 
	};
	
	//===== Part Functions =====\\	
	this.addPart = function(name, description, emissions, linkid) {
		// TODO partstub and partlocationstub depend		
		var id = this.partcount;

		var part = new Object();
		part.name = name != '' ? name : Sourcemap.Template.partstubname;
	  	part.description = typeof(description) != 'undefined' ? description : '';
	  	part.linkid = typeof(linkid) != 'undefined' ? linkid : 0;
	  	part.rawemissions = typeof(emissions) != 'undefined' ? Number(emissions) : 0;
	  	part.emissions = typeof(emissions) != 'undefined' ? Number(emissions) : 0;

		part.flag = 'add';	
		part.imid = -1;
		part.weight = 0;
		part.shipping = 0;
		part.latlon = '0|0';	
		part.via = 'Part Via';
		part.transportemissions = 0;
		part.origin = Sourcemap.Template.partlocationstubname;
    
		this.parts[id] = part;
		this.partcount = this.partcount+1;
		Sourcemap.pageTracker._trackEvent("SMap", "AddedPart", "Added "+part.name+" to "+objectConfig.oid+"");				
		
		$("body").trigger("partAdded", [id, part.name, part.description, part.emissions, part.linkid]); 		
	};
	this.removePart = function(id) {
		if(this.parts[id].flag == "add") {
			this.parts[id].flag = "unchanged";
		}
		else { this.parts[id].flag = "remove";}

		this.recalculateObjectValues();
		
		$("body").trigger("partRemoved", [id]); 			
	};
	this.recalculateObjectValues = function() {
		var emissionsTotal = 0;
		var shippingDistanceTotal = 0;
	        var weightTotal = 0;
		var transportEmissionsTotal = 0;
		var transportKGKM = 0;
		var embodiedEmissionsTotal = 0;
	        var processEmissionsTotal = 0;

 		for ( var i = 0; i < this.parts.length; i++ ) {
 			var part = this.parts[i];
 			if(part.flag != "remove") {
 			    embodiedEmissionsTotal += parseFloat(part.emissions);
 			    transportEmissionsTotal += (parseFloat(part.shipping) * parseFloat(part.weight/1000) * parseFloat(part.transportemissions));
 			    weightTotal += parseFloat(part.weight);


			    if(typeof(this.parts[i].process) != 'undefined') {
				
 				$.each(this.parts[i].process, function(p, q) {
					   console.log(q["flag"]);
					   if(q["flag"] != 1){
					       if(q["unit"] != "kg"){
 						   processEmissionsTotal += (parseFloat(q["emissions"]) * parseFloat(q["factor"]));             
						   
					       } else{
 						   processEmissionsTotal += (parseFloat(q["emissions"]) * weightTotal);             
 					       }
					   }
 				       });

			    }

 			    shippingDistanceTotal += parseFloat(part.shipping);					
 			    transportKGKM += (Number(part.weight) * Number(part.shipping));
 			}	
 		}
		if(this.showembodied == "on") { emissionsTotal += embodiedEmissionsTotal;}		
	        if(this.showtransport == "on") { emissionsTotal += transportEmissionsTotal;}
		if(this.showuse == "on" && this.usageemissions != null) { 
		    emissionsTotal += parseFloat(this.usageemissions);
		}
	        if(this.showprocess == "on") { emissionsTotal += processEmissionsTotal;}
		
		this.setEmissions(emissionsTotal);
		this.setEmbodiedEmissions(embodiedEmissionsTotal);
		this.setTransportEmissions(transportEmissionsTotal);
		this.setShipping(shippingDistanceTotal);
		this.setWeight(weightTotal);
	        this.setProcessEmissions(processEmissionsTotal);

		$("body").trigger("objectTotalsCalculated", [emissionsTotal, embodiedEmissionsTotal, transportEmissionsTotal, shippingDistanceTotal, weightTotal, processEmissionsTotal]);
	};
	

    //========Process setting functions=====\\
        this.addProcess = function(id, process_name, emissions, unit, flag, factor){
	    
	    if(typeof(this.parts[id].process) == 'undefined') {
		this.parts[id].process = Array();
		
	    }
	    this.parts[id].process.push({'name' : process_name,
					 'emissions' : emissions,
					 'unit' : unit,
					 'flag' : flag,
					 'factor' : factor});		
	    
	    if(this.parts[id].flag != "add") {
		this.parts[id].flag = "changed";  
	    }
	    	   
	    this.recalculateObjectValues(); 
	    
	    $("body").trigger("addingProcess", [id, process_name]);
        };


        this.updateProcessEmissionsByName = function(part_id, process_name, emissions){

	    $.each(this.parts[part_id].process, function(process_id, process) {
		       if(process['name'] == process_name) {
			   process['emissions'] = emissions;
		       }
		   });
	    this.parts[part_id].flag = "changed";
	    this.recalculateObjectValues(); 
        };
    
        this.updateProcessFactorByName = function(part_id, process_name, factor){
	    
	    $.each(this.parts[part_id].process, function(process_id, process) {
		       if(process['name'] == process_name) {
			   process['factor'] = factor;
		       }
		   });
	    this.parts[part_id].flag = "changed";
	    this.recalculateObjectValues(); 
        };
    

    //===== Part Settor Functions =====\\	
    
    this.setPartName = function(id, name) {	
	name = name.replace(/[']/g,'');	
	this.parts[id].name = name; 
	if(this.parts[id].flag != "add") {
		this.parts[id].flag = "changed";  
	    }
	
	$("body").trigger("partNameUpdated", [id, name]);					
    };
	this.setPartDescription = function(id, description) {
			description = description.replace(/[']/g,'');
			this.parts[id].description = description; 				
			if(this.parts[id].flag != "add") {
				this.parts[id].flag = "changed";  
			}
			$("body").trigger("partDescriptionUpdated", [id, description]);
	};
	this.setPartWeight = function(id, weight) {
		// TODO partkgfactor should e tied to something
		if(typeof(parseFloat(weight)) != "number") { weight = 0; }
		this.parts[id].weight = Number(weight*Sourcemap.Template.partkgfactor);				
		if(this.parts[id].flag != "add") {
			this.parts[id].flag = "changed";  
		}

		this.setPartEmissions(id, weight * this.parts[id].rawemissions, true);
		this.setPartTransportEmissions(id, $("#"+id+ " .input-via").val(), true); // TODO Strange Depend.	

		this.recalculateObjectValues(); $("body").trigger("partTotalsChanged", [id]); 
		$("body").trigger("partWeightUpdated", [id, weight]);
		
	};
	this.setPartShipping = function(id, shipping) {
		// TODO partkgfactor should e tied to something
		this.parts[id].shipping = Number(shipping);		
				
		if(this.parts[id].flag != "add") {
			this.parts[id].flag = "changed";  
		}

		this.recalculateObjectValues(); 
		
		$("body").trigger("partTotalsChanged", [id]); 		
		$("body").trigger("partShippingUpdated", [id, shipping]);				
	};
	
	this.setPartEmissions = function(id, emissions, suppress) {		
		this.parts[id].emissions = emissions; 
		if(this.parts[id].flag != "add") {
			this.parts[id].flag = "changed";  
		}

		if(!(suppress)) { this.recalculateObjectValues(); $("body").trigger("partTotalsChanged", [id]); }

		$("body").trigger("partEmissionsUpdated", [id, emissions]);
	};
	this.setPartRawEmissions = function(id, rawemissions, suppress) {	
		this.parts[id].rawemissions = rawemissions; 
		if(this.parts[id].flag != "add") {
			this.parts[id].flag = "changed";  
		}

		this.setPartEmissions(id, rawemissions * this.parts[id].weight, true);

		if(!(suppress)) { this.recalculateObjectValues(); $("body").trigger("partTotalsChanged", [id]); }

		$("body").trigger("partRawEmissionsUpdated", [id, rawemissions]);
	};
	this.setPartTransportEmissions = function(id, transportemissions, suppress) {
		this.parts[id].transportemissions = transportemissions; 
		if(this.parts[id].flag != "add") {
			this.parts[id].flag = "changed";  
		}

		if(!(suppress)) { this.recalculateObjectValues(); $("body").trigger("partTotalsChanged", [id]); }

		$("body").trigger("partTransportEmissionsUpdated", [id, transportemissions]);
	};
	this.setPartVia = function(id, via, type) {
		this.parts[id].via = type;				
		if(this.parts[id].flag != "add") {
			this.parts[id].flag = "changed";  
		}

		this.setPartTransportEmissions(id, $("#"+id+ " .input-via").val(), true);		
		this.recalculateObjectValues(); $("body").trigger("partTotalsChanged", [id]); 

		$("body").trigger("partViaUpdated", [id, via]);
	};
	this.setPartOrigin = function(id, origin, geofeedback) {
		// Todo has objectConfig Depend. Callback should be seperated out
		objectOrigin = GLatLng.fromUrlValue(this.latlon.replace(/[|]/g,','));
		SMap.geoCoder.getLocations(Sourcemap.Util.utf8decode(origin), function(response){

			if(response.Status.code != 200) { if(geofeedback) { 
				$("body").trigger("originGeoError", [id]);
				SMap.removePart(objectConfig.parts[id], id); 
			} }

		    point = new GLatLng(response.Placemark[0].Point.coordinates[1], response.Placemark[0].Point.coordinates[0]);	
			place = response.Placemark[0];		
			
			if(point != null && (point.lat() != 0 && point.lng() != 0) && response.Status.code == 200){
				if(geofeedback) { $("body").trigger("originGeoMessage", [id, place.address]); }
				newDistance = Sourcemap.Util.getDistance(objectOrigin, point);
				if(objectConfig.parts[id].flag != "add") {
					objectConfig.parts[id].flag = "changed";  
				}
				objectConfig.parts[id].latlon = point.lat() + "|" + point.lng();	
				objectConfig.parts[id].origin = Sourcemap.Util.utf8encode(place.address);							
				objectConfig.setPartShipping(id, newDistance);				
				objectConfig.setPartTransportEmissions(id, $("#"+id+ " .input-via").val(), false);		
				// TODO needed? this.recalculateObjectValues()
				
				$("body").trigger("partOriginUpdated", [id, place.address]);	
			}
			else { if(geofeedback) { 
				$("body").trigger("originGeoError", [id]);
				SMap.removePart(objectConfig.parts[id], id); 
			} }
		});
	};

	//===== Sample Debug Bindings =====\\
	$("body").bind("originGeoMessage", function(e, id, msg) { 
		Sourcemap.debug("Event::originGeoMessage {"+id+":"+msg+"}");
	});	
	$("body").bind("originGeoError", function(e, id) {
		Sourcemap.debug("Event::originGeoError:"+id);
	});
	$("body").bind("partAdded", function(e, id, name, desc, emiss, link) { 
		Sourcemap.debug("Event::partAdded {"+name+","+id+","+desc+","+emiss+","+link+"}");
	});
	$("body").bind("savingObject", function(e, obj) {
		Sourcemap.debug("Event::savingObject {"); Sourcemap.debug(obj); Sourcemap.debug("}");
	});	
	$("body").bind("saveObjectConfirmation", function(e, data) { 
		Sourcemap.debug("Event::saveObjectConfirmation {"+data+"}");
	});
	$("body").bind("objectTotalsCalculated", function(e, emis, embod, trans, ship, weight) {
		Sourcemap.debug("Event::objectTotalsCalculated {"+emis+","+embod+","+trans+","+ship+","+weight+"}");
	});
	
}

