/**
 * Sourcemap.Editor: Main component to handle viewing and editing of Sourcemaps.
 *
 * @version 0.7.5
 * @author sourcemap@media.mit.edu
 * @package sourcemap
 * @subpackage js
 */

if ( typeof(Sourcemap) == 'undefined' ) {Sourcemap = {}; }

Sourcemap.Editor = {
    init: function() {
	
	/**
	 * Triggers edit mode of objects by switching fields and enabling interaction.
	 */
			this.processHashEvent = function(hash) {
				if(hash == "#edit") {
					this.initializeEditMode();
					if(Sourcemap.Template.usechooser) { this.showPartChooser();}
				}			
				else if(hash == "#new") {
					this.initializeEditMode();
					if(Sourcemap.Template.usechooser) { this.showPartChooser();}
					this.initializeTooltips();				    
				}			
			};
			
   			this.initializeEditMode = function() {	
 			    // Make Objects Editable
			    $("#object-name").html('<input class="input-object-name" value="'+$("#object-name").text()+'">'); 
			    $("#object-description").html('<textarea class="input-object-description">'+$("#object-description").html()+'</textarea>');
			    $("#object-origin").html('<input class="input-object-origin" value="'+$("#object-origin").text()+'">');
			    $("#object-destination").html('<input class="input-object-destination" value="'+$("#object-destination").text()+'">');
			    $("#object-via select").removeAttr("disabled");
			    
			    $( "#part-list").addClass("editmode");						    
			    $("div#partlist-shell").css("height","360px");	
			
			    // Make Parts Editable
			    $(".part-name").each(function() { $(this).html('<input class="input-name" value="'+$(this).text()+'">');});
			    $(".part-description").each(function() { $(this).html('<textarea class="input-description">'+$(this).html()+'</textarea>');});	
			    $(".part-weight").each(function() { $(this).html('<input class="input-weight" value="'+$(this).text()+'">');});
			    $(".part-via select").removeAttr("disabled");
			    $(".part-origin").each(function() { $(this).html('<input class="input-origin" value="'+$(this).text()+'">');});
			    $(".part-rawemissions").each(function() { $(this).html('<input class="input-rawemissions" value="'+$(this).text()+'">');});
			    			    			    
			   // Make Process Editable
			    $(".process-text").each(function() { $(this).html('<input class="input-process-text" value="'+$(this).text()+'">');});
			    

			    // Set handlers to Sourcemap.Object
			    $(".input-object-name").change(function() { 
				    objectConfig.setName($(this).val()); $(this).addClass("dirty");
				});
			    $(".input-object-description").change(function() { 
				    objectConfig.setDescription($(this).val()); $(this).addClass("dirty");
				});
			  
			    $(".input-object-origin").change(function() { 
				    objectConfig.setOrigin($(this).val()); $(this).addClass("dirty");
				});
			    $(".input-object-destination").change(function() { 
				    objectConfig.setDestination($(this).val()); $(this).addClass("dirty");
				});
			    $("#object-via-select").change(function() { 
				    objectConfig.setVia($("#object-via select :selected").text()); $(this).addClass("dirty");
				});
			    

			    			    			    
			    Sourcemap.Editor.setupPartHandlers("");	    
			    Sourcemap.Editor.setupPermissions();
			    
			    

			    window.onbeforeunload = function() { return "You are currently editing a sourcemap and you haven't saved your changes. If you want to save your changes, click the green 'Save Sourcemap' button before you leave."; };	 
			    
			    $("#permission-button").removeClass("hidden");

			    $("li.part-details").addClass("hover");
			    $(".part-details .removeaction").css("display","inline");
			    
			    $('input:checkbox').removeAttr("disabled"); 
			    $("#usekwh, #poweruselist, #transportlist").removeAttr("disabled"); 
			    $("div#summary-statistics .checkmessage, div#summary-statistics input[type='checkbox']").css("display","inline");
			    
			    $("#editsourcemapbutton").css("display","none");
			    $("#savesourcemapbutton").slideDown();
			    $("#addpartbutton, #partlist-menu").slideDown();
	
			    $(".list-expand").text("-");			    			
			};
			
			this.stopEditMode = function() {	
			    $(".qTip").remove();
			    window.location.hash = "#";
		    
			    $( "#ajaxnote" ).text("Saving Object..."); // TODO should become notes area.
			    $( "#part-list").removeClass("editmode");

			    $("#permission-button").addClass("hidden");
			    $("#permission-panel").css("display","none");

			    Sourcemap.Editor.closePartChooser();
			    
			    objectConfig.recalculateObjectValues();	
			    objectConfig.save();
			    
			    $(".list-expand").text("+");
			    $(".list-expand").parents(".part-details").removeClass("hover");
			    
			    
			    // Add input switch to unedit object
			    $(".input-object-name").each(function() { $(this).replaceWith($(this).val());});		
			    $(".input-object-description").each(function() { $(this).replaceWith(Sourcemap.Util.rhtmlspecialchars($(this).val()));});			
			    $(".input-object-permissions").each(function() { $(this).replaceWith($(this).val());});		
			    $(".input-object-origin").each(function() { $(this).replaceWith($(this).val());});		
			    $(".input-object-destination").each(function() { $(this).replaceWith($(this).val());});
			    
			    $(".input-object-via").attr("disabled", "disabled"); $(".input-object-via").removeClass("dirty");
			    $(".input-object-lifetime").each(function() { $(this).replaceWith($(this).val());});
			    
			    // Add input switch to unedit parts	
			    $(".input-name").each(function() { $(this).replaceWith($(this).val());});
			    $(".input-description").each(function() { $(this).replaceWith(Sourcemap.Util.rhtmlspecialchars($(this).val()));});
			    $(".input-weight").each(function() { $(this).replaceWith($(this).val());});
			    $(".input-via").attr("disabled", "disabled"); $(".input-via").removeClass("dirty");	
			    $(".input-origin").each(function() { $(this).replaceWith($(this).val());});
			    $(".input-rawemissions").each(function() { $(this).replaceWith($(this).val());});
			    
			    //$(".part-details .removeaction").css("display","none");
			    
			    // Add input switch to unedit process
			    $(".input-process-text").each(function() { $(this).replaceWith($(this).val());
								     });          
			   
				
			    
			    // Add capability to create parts
			    $("#private-button").unbind("click");
			    
			    $("#editsourcemapbutton").slideDown();
			    $("#savesourcemapbutton").css("display","none");
			    $("#addpartbutton, #partlist-menu").css("display","none");
			    $("div#summary-statistics .checkmessage, div#summary-statistics input[type='checkbox']").css("display","none");		
			    $("div#partlist-shell").css("height","385px");
			    $('#partchooser-visualization').css("display","none");
			    $("input:checkbox").attr("disabled","true");
			    $("#usekwh, #poweruselist").attr("disabled", "true"); 
			};
			
			this.setupPartHandlers = function(specifier) {
			    // Set part handlers to Sourcemap.Object
			    
			    $(".add-process").click(function(){
                                   
					Sourcemap.Editor.getProcessesForPart(parseInt($(this).parents(".part-details").attr("id")), this);
                          							     
				 });
			    
			    $(specifier + ".input-name").change(function() {
				    objectConfig.setPartName(parseInt($(this).parents(".part-details").attr("id")), $(this).val()); 
				    $(this).addClass("dirty");

				});
			    $(specifier + ".input-description").change(function() {
				    objectConfig.setPartDescription(parseInt($(this).parents(".part-details").attr("id")), $(this).val()); 
				    $(this).addClass("dirty");
				});
			    $(specifier + ".input-weight").change(function() {
				    objectConfig.setPartWeight(parseInt($(this).parents(".part-details").attr("id")), $(this).val()); 
				    $(this).addClass("dirty");
				});
			    $(specifier + ".input-via").change(function() {
				    objectConfig.setPartVia(parseInt($(this).parents(".part-details").attr("id")), $(this).val(), $(this).children(":selected").text()); 
				    $(this).addClass("dirty");
				});
			    $(specifier + ".input-origin").change(function() {
				    objectConfig.setPartOrigin(parseInt($(this).parents(".part-details").attr("id")), $(this).val(), true); 
				    $(this).addClass("dirty");
				});
			    $(specifier + ".input-rawemissions").change(function() {
				    objectConfig.setPartRawEmissions(parseInt($(this).parents(".part-details").attr("id")), $(this).val()); 
				    $(this).addClass("dirty");
				});
			    $(specifier + ".removeaction").click(function() {
				    objectConfig.removePart(parseInt($(this).parents(".part-details").attr("id")));
				});
			};
			
			this.updatePartPunch = function(id) {		
			    var selectpunch = "#"+id+".part-details .part-punch-totalemissions";
			    var embodiedpunch = "#"+id+".part-details .part-punch-emissions";
			    var transportpunch = "#"+id+".part-details .part-punch-transportemissions";
			    var processpunch = "#"+id+".part-details .part-punch-processemissions";
			    var embodiedval = $(embodiedpunch).text();
			    var transportval = $(transportpunch).text();
			    var processval = $(processpunch).text();
			    if(objectConfig.showembodied == "") { embodiedval = 0;}
			    if(objectConfig.showtransport == "") { transportval = 0;}
			    var totalval = Sourcemap.Util.roundIt(Number(embodiedval) + Number(transportval) + Number(processval),2);
			    $(selectpunch).text("" + totalval);
			    if(objectConfig.showprocess == "") { processval =0;}
			};
			
			this.toggleEmbodiedDisplay = function() {
			    if(objectConfig.showembodied == "on" && Sourcemap.Template.useembodied) { $(".part-rawemissions, .part-punch-emissions").css("display", "inline");}
			    else { $(".part-rawemissions, .part-punch-emissions").css("display", "none");}
			};
			
			this.toggleTransportDisplay = function() {
			    if(objectConfig.showtransport == "on") { $(".part-shipping, .part-transport, .part-via, .part-punch-transportemissions").css("display", "inline");}
			    else { $(".part-shipping, .part-transport, .part-via, .part-punch-transportemissions").css("display", "none");}
			};
			
			this.toggleProcessDisplay = function() {
			    if(objectConfig.showprocess =="on") { $(".addprocess, .process-appearance-super, processemissions-punch-value").css("display", "inline"); }
			    else { $(".addprocess, .process-appearance-super, processemissions-punch-value").css("display", "none"); }
			};

			this.toggleEndoflifeDisplay = function() {};
			
			this.initializeTooltips = function() {
			    $('.input-object-name').qtip({ content: '<div class="number">1</div>What is the name of your Sourcemap?', show: { when: false, ready: true }, hide: 'focus', position: { corner: { target: 'leftTop', tooltip: 'bottomLeft' } }, style: { width:540, 'font-size': 16, tip: 'bottomLeft', border: { width: 3, radius: 8, color: '#f9e98e' }, background: '#fbf7aa', 'font-weight': 'bold', color: '#000' } });
			    
			    $('.input-object-origin').qtip({ content: '<div class="number">2</div> What is the location?', show: { when: false, ready: true }, hide: 'focus', position: { corner: { target: 'rightMiddle', tooltip: 'topLeft' } }, style: { width:240, tip: 'topLeft', border: { width: 1, radius: 8, color: '#f9e98e' }, 'font-size': 12, background: '#fbf7aa', color: '#000' } });
			    
			    $('.input-object-description').qtip({ content: '<div class="number">3</div>You can add a description here, some html is ok.', show: { when: false, ready: true }, hide: 'focus', position: { corner: { target: 'bottomMiddle', tooltip: 'topMiddle' } }, style: { width:340, tip: 'topMiddle', border: { width: 1, radius: 8, color: '#f9e98e' }, 'font-size': 12, background: '#fbf7aa', color: '#000'} }); 
			};
			
			// Partlist visualization?
			this.partHighlight = function(id) {
			    $('#partlist-shell').scrollTo($("#"+id), 200, {offset:-10});
			    
			    $("#"+id+" .part-summary").css("background-color", "#ffffcc");
			    $("#"+id+" .part-summary").css("border-color", "#ffaa00");
			    $("#"+id).css("background-color", "#ffffcc");
			    $("#"+id).css("border-color", "#ffaa00");
			    
			    $("#"+id+" .part-actions").css("background-color", "#ffaa00");
			    
			    $("#"+id+" .part-summary").animate({ backgroundColor: "#eeeeee", borderBottomColor: "#cccccc", borderTopColor: "#cccccc", borderLeftColor: "#cccccc", borderRightColor: "#cccccc"}, 3000 );
			    $("#"+id+" .part-summary").animate({ backgroundColor: "#eeeeee", borderBottomColor: "#cccccc", borderTopColor: "#cccccc", borderLeftColor: "#cccccc", borderRightColor: "#cccccc"}, 3000 );
			    $("#"+id).animate({ backgroundColor: "#eeeeee", borderBottomColor: "#cccccc", borderTopColor: "#cccccc", borderLeftColor: "#cccccc", borderRightColor: "#cccccc"}, 3000 );
			    $("#"+id).animate({ backgroundColor: "#f5f5f5", borderBottomColor: "#cccccc", borderTopColor: "#cccccc", borderLeftColor: "#cccccc", borderRightColor: "#cccccc"}, 3000 );
			    
			    $("#"+id+" .part-actions").animate({ backgroundColor: "#cccccc"}, 3000);	
			    
			    $("#"+id+".part-details").addClass("hover");
			    $("#"+id+" .list-expand").text("-"); 				
			};
			
			this.showPartChooser = function() {
				SMap.clearPopup();
			    $('#partlist-shell, #partlist-menu').css("display","none");					
			    $("#part-shell, #part-visualization").css("width","125px");				
			    $('#partchooser-visualization').css("display", "block");
			    $("#partchooser-visualization .visualization-close-button").click(function() {Sourcemap.Editor.closePartChooser();});
			    $("#partlist-close-button").unbind("click");
			    $("#partlist-close-button").click(function() {
				    Sourcemap.Editor.closePartChooser();
				});			    			    
			};
			this.closePartChooser = function() {
			    $("#part-shell, #part-visualization").css("width", "540px"),
			    $('#partlist-menu').css("display","block");					
			    $('#partlist-shell').css("display","block"); 
			    $('#partchooser-visualization').css("display","none");	
			    $("#partlist-close-button").unbind("click");	
			    $("#partlist-close-button").click( function() { 
				    $("#part-visualization ").css("display", "none");
				});	
			};
			this.protoPart = $.template('<li class="part-details hover" id="${pid}"><div class="part-actions"><span class="removeaction" style="display:inline">x</span>&nbsp;&nbsp;<span class="list-expand">-</span></div><div class="list-legend">${legend}</div><ul class="part-summary"><li class="part-name">${plinkstart}<input class="input-name" value="${pname}">${plinkfinish}</li><li class="part-origin"><input class="input-origin" value="${porigin}"></li><li class="part-weight"><input class="input-weight" value="0"></li><span class="unit"><span class="part-punch-totalemissions">0</span></span><div style="clear:both;"></div></ul><div style="clear:both;"></div><ul class="part-properties"><li class="part-rawemissions"><input class="input-rawemissions" value="${pemissions}"></li><li class="part-punch-emissions">0</li><div style="clear:both;"></div><li class="part-shipping">0</li><li class="part-via">${pvia}</li><li class="part-punch-transportemissions">0</li></ul><div style="clear:both;"></div><div class="part-description"><textarea class="input-description">${pdescription}</textarea></div></li>');
			
			this.receiveDescriptionChange = function(description) {
			    $(".input-object-description").val(description);
			};	
			this.receiveAddedPart = function(id, name, description, emissions, link) {	
			    /* TODO Cleanup Create a blank part for editing and adding to the object. was addObjectPart and addExistingObjectPart
			     */	
			    $(".qtip").remove();
			    Sourcemap.Editor.closePartChooser();
			 	var linkurl =  Sourcemap.siteurl+link;
				if(linkurl != Sourcemap.siteurl) { var linkstart = '<a href="'+linkurl+'">'; var linkfinish = '</a>'; }
				else { var linkstart = ''; var linkfinish = '';}

			    $("#part-list").append( Sourcemap.Editor.protoPart , {
				    pid: id,
				    legend:id+1,
				    plinkstart:linkstart,
				    plinkfinish:linkfinish,
				    pname: name,
				    porigin: Sourcemap.Template.partlocationstubname,
				    pemissions: emissions,
				    pvia: $("#object-via").html(),
				    pdescription: description
				});
			
			    Sourcemap.Editor.setupPartHandlers("#"+id+" ");
				if(	$('#part-visualization').css("display") == "none") {
					$('#part-visualization').css("display", "block");
				}
			    $('#partlist-shell').scrollTo("max");	
				Sourcemap.Editor.partHighlight(id);
				
				$(".list-expand").bind("click", function() { 
					$(this).parents(".part-details").toggleClass("hover");
					if($(this).text() == "+") { $(this).text("-"); } else { $(this).text("+"); }
				});
			
			};
			this.receiveRemovedPart = function(id) {
				if(id == 0) { $(".part-details:first").remove(); } else {$("#"+id+".part-details").remove();}			    
			};
			
			// TODO Actually only the receipt cares about this...
			this.receiveObjectTotals = function(emissions, embodied, transport, shipping, weight, processTotal) {
			    $("#object-emissions").text(Sourcemap.Util.roundIt(Number(emissions),2));
			    $("#embodiedemissions-punch-value").text(Sourcemap.Util.roundIt(Number(embodied),2));
			    $("#transportemissions-punch-value").text(Sourcemap.Util.roundIt(Number(transport),2));
			    $("#object-shipping").text(Sourcemap.Util.roundIt(Number(shipping),2));
			    $("#object-weight").text(Sourcemap.Util.roundIt(Number(weight),2));		
			    $("#processemissions-punch-value").text(Sourcemap.Util.roundIt(Number(processTotal),2));		
			    
			    for ( var part in objectConfig.parts ) { Sourcemap.Editor.updatePartPunch(part);}
			};
			this.receiveGeocodeError = function(element) {
			    $(element).parent().qtip({ content: "We couldn't find this location...", show: { when: false, ready: true }, hide: 'click', position: { corner: { target: 'topMiddle', tooltip: 'bottomMiddle' } }, style: { width:340, 'font-size': 16, tip: 'bottomMiddle', border: { width: 3, radius: 8, color: 'red' }, background: 'red', 'font-weight': 'bold', color: 'white'} });			    
			    setTimeout(function(){  $(".qtip:last").fadeOut("normal", function() {$(this).remove(); }); }, 2000);
			};
			this.receiveGeocodeMessage = function(element, address) {
			    $(element).val(address);
			    $(element).parent().qtip({ content: "We think you meant " + address + ".", show: { when: false, ready: true }, hide: 'click', position: { corner: { target: 'topMiddle', tooltip: 'bottomMiddle' } }, style: { width:340, 'font-size': 16, tip: 'bottomMiddle', border: { width: 3, radius: 8, color: '#f9e98e' }, background: '#fbf7aa', 'font-weight': 'bold',
color: '#000'} });			    
			    setTimeout(function(){  $(".qtip:last").fadeOut("normal", function() {$(this).remove(); }); }, 2000);
			};
			
			this.receiveSaveConfirmation = function(data) {
			    $( "#ajaxnote" ).text("");
			    window.onbeforeunload = null;
			    
			    if(data == "added") { 
				window.location.href = Sourcemap.siteurl+objectConfig.type+'/' + objectConfig.slug+"#edit";
			    }
			
				SMap.clearPopup();
				SMap.objectsummary = objectConfig;
				
				SMap.refreshMap();
			};
			
			// Part Functions
	                this.receiveProcessSelected = function(id, process_name) {
			    $("#"+id+".process-name").val(Sourcemap.Util.utf8decode(process_name));  
			};
	
			this.receivePartTotalsChanged = function(id) {
			    Sourcemap.Editor.updatePartPunch(id);
			};
			
			this.receivePartNameChange = function(id, name) {
			    $("#"+id+" .input-name").val(Sourcemap.Util.utf8decode(name));
			};
			
			this.receivePartDescriptionChange = function(id, description) {
			    $("#"+id+" .input-description").val(Sourcemap.Util.utf8decode(description));
			};
			
			this.receivePartWeightChange = function(id, weight) {
			    $("#"+id+" .input-weight").val(Number(weight));	
			};
			this.receivePartShippingChange = function(id, shipping) {
			    $("#"+id+" .part-shipping").text(Sourcemap.Util.roundIt(Number(shipping),2));	
			};	
			
			this.receivePartEmissionsChange = function(id, emissions) {
			    $("#"+id+" .part-punch-emissions").text(Sourcemap.Util.roundIt(Number(emissions),2));		
			};
			this.receievePartRawEmissionsChange = function(id, rawemissions) {
			    $( "#"+id+" .part-rawemissions").val(Sourcemap.Util.roundIt(Number(rawemissions),2));
			};
			this.receivePartTransportEmissionsChange = function(id, transportemissions) {	
			    $( "#"+id+" .part-punch-transportemissions").text("" + Sourcemap.Util.roundIt(Number(objectConfig.parts[id].shipping * (objectConfig.parts[id].weight/1000) * transportemissions),2));
			};
			this.receivePartViaChange = function(id, via) {
			    $( "#"+id+" .input-via").val(via);	
			};
			this.receivePartOriginChange = function(id, origin) {
			    $( "#"+id+" .input-origin").val(origin);	
			    SMap.refreshMap();
			};



	//Getting the processes list for the parts id

	var slugToProcessCache = Array();
        var slug = Array(100);	
	var defaultSelectText = "None Selected:";
	var flag = 0;
	var factor = 1;

	this.getProcessesForPart = function(id, elt) {
	    var part_id = id;
	    var part_slug = objectConfig.parts[id].linkid;
	    var slug_part =part_slug.split("/")[1];
	    Sourcemap.Editor.saveProcessForPart(slug_part, part_id, elt);
	    
	};
	
	this.saveProcessForPart = function(data, part_id, elt){
	
	    function populateProcessOptions(slug_process) {
		var options ="<option>"+defaultSelectText+"</option>";

		$.each(slug_process, function(i,e) {
			   options +='<option value="' + e.id + '">' + e.name + '</option>';			   
		       });
		
		$(elt).children("select").html(options);
	    }


	    slug = data;
	    if(slugToProcessCache[slug] != null) {
		populateProcessOptions(slugToProcessCache[slug]);
	    } else {
		
		var saveData = {
		    parts_slug : data
		    
		};
		var sendData = "data=" + JSON.stringify(saveData) + "";
		$.post(Sourcemap.siteurl+"objects/getProcesses", sendData, 
		       function(response) {
			   var objects = eval(response);
			   var options = "";
			   slugToProcessCache[slug] = objects;
			   populateProcessOptions(slugToProcessCache[slug]);
		       });

	    }

	    //Selecting a process from the list and if it exists in cache then 
	    //

	    $(".process-selected").unbind('change');
	    $(".process-selected").change(
		function() {
 		    var process_name = $(this).children("option:selected").text();

		    if(process_name == defaultSelectText) {
			 return;
		    }

		    var processAlreadyAdded = false;
		    $(".process-appearance-super span.process-display").each(
			function(i, element) {
			    if($(element).html() == process_name) {
				processAlreadyAdded = true;
				return false;
			    }
			    return true;
			});
		    if(processAlreadyAdded) {
			return;
		    }
		    

		    // Get emissions and unit from cache
		    var emissions = null;
		    var unit = null;
		    var unit_flag = false;

		    $.each(slugToProcessCache[slug], function(i,e) {
			       if(e.name == process_name) {
				   emissions = e.emissions;
				   unit = e.unit;
				   if (unit != "kg"){ unit_flag =true; }
				   return false;
			       }
			       return true;
			   });
		    var processAppearance = $(this).parents("li.part-details").children(".process-appearance-super");

		    console.log(unit_flag);
		    if (unit_flag == true) {
			processAppearance.html(processAppearance.html()
					   + "<div><span class=\"process-display\">" + process_name +"</span> <input class=\"input-process-text\" value=\""+emissions+"\"><input class=\"process-area\" value=\""+"\"><span class=\"process-unit\">"+unit+"</span> <span class=\"process-carbon\"></span><span class=\"removeprocessaction\">x</span></div>");

		    } else{
			
		    processAppearance.html(processAppearance.html()
					   + "<div><span class=\"process-display\">" + process_name +"</span> <input class=\"input-process-text\" value=\""+emissions+"\"><span class=\"process-unit\">"+unit+"</span> <span class=\"process-carbon\"></span><span class=\"removeprocessaction\">x</span></div>");
			factor = 1;
		    }
		    $(".process-area").change(function(){
						  objectConfig.updateProcessFactorByName(part_id, process_name,
											 $(this).val());
						  
					      });

		    $(".input-process-text").change(function(){
						objectConfig.updateProcessEmissionsByName(part_id, process_name,
											  $(this).val());
					      });
		    
		    objectConfig.addProcess(part_id, process_name, emissions, unit, flag, factor);		   
		});
	};
	

	//remove button removes the process from the list and from the database
	$("span.removeprocessaction").click(function(){
						var remove_name = $(this).parents(".process-details").children("span.process-display").text();
						var remove_emissions = $(this).parents(".process-details").children("span.process-text").text();
						var remove_unit = $(this).parents(".process-details").children("span.process-unit").text();
						var id = parseInt($(this).parents(".part-details").attr("id"));
						flag = 1;
						factor = 0;
						($(this).parents(".process-details").children("span.process-display")).remove();
						($(this).parents(".process-details").children("span.process-text")).remove();
						($(this).parents(".process-details").children("span.process-unit")).remove();
						($(this).parents(".process-details").children("span.process-carbon")).remove();
						($(this).parents(".process-details").children("span.removeprocessaction")).remove();
						objectConfig.addProcess(id, remove_name, remove_emissions, remove_unit, flag, factor);
						
					    });
	

		
	           // Misc Functions TODO These functions should probably be moved somewhere else...
			
			this.calculateUsage = function() {
			    var factorVal = $("#poweruselist").find(":selected").val();
			    var usage = objectConfig.usageenergy * factorVal;
			    $("#usepunch").text(Sourcemap.Util.roundIt(Number(usage),2));
			    objectConfig.usageemissions = Sourcemap.Util.roundIt(Number(usage),2);
			};
				
			this.setupPermissions = function() {
			    $(".permission_user").click(function() {
							    Sourcemap.Editor.savePermissions("useredit");
							    Sourcemap.Editor.removePermissions();
							    $(".permission_user").addClass("selected");							    
							});
			    $("#groupedit").change(function() {
						       Sourcemap.Editor.savePermissionsGroups($("#groupedit option:selected").text());
						       Sourcemap.Editor.removePermissions();
						       $(".permission_group").addClass("selected");
						   });
			    $(".permission_group").click(function() {
							     Sourcemap.Editor.savePermissionsGroups($("#groupedit option:selected").text());
							     Sourcemap.Editor.removePermissions();
							     $(".permission_group").addClass("selected");
							 });
			    
			    $(".permission_everyone").click(function() {
								Sourcemap.Editor.savePermissions("everyoneedit");
								Sourcemap.Editor.removePermissions();
								$(".permission_everyone").addClass("selected");
								$(".permission_public").addClass("selected");
								$(".permission_private").removeClass("selected");
							    });
			    $(".permission_public").click(function() {
							      objectConfig.visibility = "public";
							      Sourcemap.Editor.savePermissionsObjects("public");
							      Sourcemap.Editor.removeVisibility();
							      $(".permission_public").addClass("selected");
							  });
			    $(".permission_private").click(function() {
							       if($(".permission_public").hasClass("selected") && $(".permission_everyone").hasClass("selected")){ }
							       else {
								   objectConfig.visibility = "private";
								   Sourcemap.Editor.savePermissionsObjects("private");
								   Sourcemap.Editor.removeVisibility();
								   $(".permission_private").addClass("selected");
							       }
							   });
			};
			
			this.removeVisibility = function() {
				$(".permission_public").removeClass("selected");
				$(".permission_private").removeClass("selected");			
		    };
		
			this.removePermissions = function() {
				$(".permission_user").removeClass("selected");
				$(".permission_group").removeClass("selected");
				$(".permission_everyone").removeClass("selected");
			};

		    this.savePermissions = function(data) {
				var saveData = {
				    permission_type: data,
				    oid: objectConfig.oid
				};
			
				var sendData = "data=" + JSON.stringify(saveData) + "";
				$.post(Sourcemap.siteurl+"objects/setpermissions", sendData);			
		    };

		    this.savePermissionsGroups = function(data) {
				var saveData = {
					group_name: data,
					permission_type: "groupedit",
				    oid: objectConfig.oid
				};
			
				var sendData = "data=" + JSON.stringify(saveData) + "";
				$.post(Sourcemap.siteurl+"objects/setpermissions", sendData);			
		    };
		    
		    this.savePermissionsObjects = function(permission) {
				var saveData = {
				    visibility: permission,
				    oid: objectConfig.oid
				};
			
				var sendData = "data=" + JSON.stringify(saveData) + "";
				$.post(Sourcemap.siteurl+"objects/setvisibility", sendData);
			};		

			// Listen for object events
			$("body").bind('objectDescriptionUpdated', function(e, description) {
				Sourcemap.Editor.receiveDescriptionChange(description); 
			    });
			$("body").bind('originGeoError', function(e, id) {
				if(id == "object") { Sourcemap.Editor.receiveGeocodeError(".input-object-origin"); }
				else { Sourcemap.Editor.receiveGeocodeError("#"+id+" .input-origin"); }
			    });
			$("body").bind('originGeoMessage', function(e, id, msg) {
				if(id == "object") { Sourcemap.Editor.receiveGeocodeMessage(".input-object-origin", msg); }
				else { Sourcemap.Editor.receiveGeocodeMessage("#"+id+" .input-origin", msg); }
			    });	
                	$("body").bind("objectTotalsCalculated", function(e, emis, embod, trans, ship, weight, processTotal) {
				console.log(weight);
			        console.log(processTotal);
			        Sourcemap.Editor.receiveObjectTotals(emis,embod,trans,ship,weight, processTotal);
			    });			
			$("body").bind("saveObjectConfirmation", function(e, data) { Sourcemap.Editor.receiveSaveConfirmation(data);});	
			$("body").bind("partAdded", function(e, id, name, description, emissions, link) { 
				Sourcemap.Editor.receiveAddedPart(id, name, description, emissions, link);
			    });
			$("body").bind("partRemoved", function(e, id) {
				Sourcemap.Editor.receiveRemovedPart(id);
				SMap.refreshMap();
			    });	
			
			// Listen for part events

	                $("body").bind("addingProcess", function(e, id, process_name){
				Sourcemap.Editor.receiveProcessSelected(id, process_name);	   
			    });

			$("body").bind("partTotalsChanged", function(e, id) {
				Sourcemap.Editor.receivePartTotalsChanged(id);
			    });	
			$("body").bind("partNameUpdated", function(e, id, name) {
				Sourcemap.Editor.receivePartNameChange(id, name);
			    });	
			$("body").bind("partDescriptionUpdated", function(e, id, description) {
				Sourcemap.Editor.receivePartDescriptionChange(id, description);
			    });
			$("body").bind("partWeightUpdated", function(e, id, weight) {
				Sourcemap.Editor.receivePartWeightChange(id, weight);		
			    });
			$("body").bind("partShippingUpdated", function(e, id, shipping) {
				Sourcemap.Editor.receivePartShippingChange(id, shipping);		
			    });	
			$("body").bind("partEmissionsUpdated", function(e, id, emissions) {
				Sourcemap.Editor.receivePartEmissionsChange(id, emissions);
			    });	
			$("body").bind("partRawEmissionsUpdated", function(e, id, rawemissions) {
				Sourcemap.Editor.receievePartRawEmissionsChange(id, rawemissions);
			    });
			$("body").bind("partTransportEmissionsUpdated", function(e, id, transportemissions) {
				Sourcemap.Editor.receivePartTransportEmissionsChange(id, transportemissions);
			    });
			$("body").bind("partViaUpdated", function(e, id, via) {
				Sourcemap.Editor.receivePartViaChange(id, via);
			    });
			$("body").bind("partOriginUpdated", function(e, id, origin) {
				Sourcemap.Editor.receivePartOriginChange(id, origin);
			    });	
			
			$("body").bind("partSelected", function(e, id) {
				Sourcemap.Editor.partHighlight(id-1);
			    });
			
			$("#more-description-button").click( function() { 
				$("body").scrollTo($("#object-story"), 500);
			    });
			
			// Visualization Menu
			$("#partlist-close-button").click( function() { 
				$("#part-visualization").css("display", "none");
				SMap.fitMap(false);
			});
			$(".visualization-close-button").click( function() { 
				$(".visualization").css("display", "none");
			});
			$(".comment-visualization-button").click( function() { 
				$(".visualization").css("display", "none");
				$("#comment-visualization").slideDown();
			});
			$(".charting-visualization-button").click( function() { 
				$(".visualization").css("display", "none");
				$("#charting-visualization").slideDown();
			    });
			$(".partlist-visualization-button").click( function() { 
				$("#part-visualization").slideToggle("normal");
				SMap.fitMap(true);
			});
					
			$("#editsourcemapbutton").click( function() { 
							     Sourcemap.Editor.initializeEditMode();
							     $(".add-process").show();
							     $(".process-appearance-super").show();
							     $(".part-punch-processemissions").show();
							 } );
			$("#savesourcemapbutton").click( function() { 
							     Sourcemap.Editor.stopEditMode(); 
							     $(".add-process").hide();
							     $(".process-appearance-super").hide();
							     $(".part-punch-processemissions").hide();
							 } );
			
			if(!(Sourcemap.Template.usechooser)) { $("#addpartbutton, #partlist-menu").click( function() { objectConfig.addPart(Sourcemap.Template.partstubname, '', '0', ''); });} 
			else { $("#addpartbutton, #partlist-menu").click( function() { Sourcemap.Editor.showPartChooser(); });}
			
			$("#permission-button").click( function() { 
				$("#delivered-panel").slideUp("fast"); $("#lastleg-button").removeClass("selected");				
				$("#share-panel").slideUp("fast"); $("#share-button").removeClass("selected");			
				$("#permission-panel").slideToggle("fast"); $("#permission-button").toggleClass("selected");
			});
			$("#share-button").click( function() { 
				$("#delivered-panel").slideUp("fast"); $("#lastleg-button").removeClass("selected");
				$("#permission-panel").slideUp("fast"); $("#permission-button").removeClass("selected");						
				$("#share-panel").slideToggle("fast"); $("#share-button").toggleClass("selected");				
			});
			$("#lastleg-button").click( function() { 
				$("#share-panel").slideUp("fast"); $("#share-button").removeClass("selected");				
				$("#permission-panel").slideUp("fast"); $("#permission-button").removeClass("selected");												
				$("#delivered-panel").slideToggle("fast"); $("#lastleg-button").toggleClass("selected");
				
			});
			
			var smapOptions = {};
			if ($('#part-visualization').css("display") != "none")
			{
				var pv = $('#part-visualization');
				smapOptions.constrain = pv[0].clientWidth + '_rc';
			}
			
			smapOptions.userLocation = false;
			smapOptions.navPosition = new OpenLayers.Pixel(2,15);
			
			// Initialize Map
			SMap = new Sourcemap.OpenSourcemap( 'smap', objectConfig, smapOptions );
			if(GBrowserIsCompatible()){ SMap.geoCoder = new GClientGeocoder(); }			

			$(".list-expand").bind("click", function() { 
				$(this).parents(".part-details").toggleClass("hover");
				if($(this).text() == "+") { $(this).text("-"); } else { $(this).text("+"); }
			});
									
			$("#showfootprint").click(function() {
				if(objectConfig.showfootprint != "on") { objectConfig.showfootprint = "on";} else { objectConfig.showfootprint = "";}		
				if(objectConfig.showfootprint == "on") { checkval = true;} else { checkval = false;}
				$("#showembodied").attr('checked', checkval);
				objectConfig.showembodied = objectConfig.showfootprint;
				$("#showtransport").attr('checked', checkval);
				objectConfig.showtransport = objectConfig.showfootprint;
				$("#showprocess").attr('checked', checkval);
				objectConfig.showprocess = objectConfig.showfootprint;
				$("#showendoflife").attr('checked', checkval);
				objectConfig.showendoflife = objectConfig.showfootprint;
				
				$("#showuse").attr('checked', checkval);
				objectConfig.showuse = objectConfig.showfootprint;
				objectConfig.recalculateObjectValues();
				Sourcemap.Editor.toggleEmbodiedDisplay();
				Sourcemap.Editor.toggleTransportDisplay();
				Sourcemap.Editor.toggleProcessDisplay();
				Sourcemap.Editor.toggleEndoflifeDisplay();
			});
			
			$("#showembodied").click(function() { if(objectConfig.showembodied != "on") { objectConfig.showembodied = "on";} else { objectConfig.showembodied = "";} objectConfig.recalculateObjectValues(); Sourcemap.Editor.toggleEmbodiedDisplay();});
			$("#showtransport").click(function() { if(objectConfig.showtransport != "on") { objectConfig.showtransport = "on";} else { objectConfig.showtransport = "";} objectConfig.recalculateObjectValues(); Sourcemap.Editor.toggleTransportDisplay();});
			$("#showprocess").click(function() { if(objectConfig.showprocess != "on") { objectConfig.showprocess = "on";} else { objectConfig.showprocess = "";} objectConfig.recalculateObjectValues(); Sourcemap.Editor.toggleProcessDisplay();});
			$("#showendoflife").click(function() { if(objectConfig.showendoflife != "on") { objectConfig.showendoflife = "on";} else { objectConfig.showendoflife = "";} objectConfig.recalculateObjectValues(); Sourcemap.Editor.toggleEndoflifeDisplay();});
			$("#showuse").click(function() { if(objectConfig.showuse != "on") { objectConfig.showuse = "on";} else { objectConfig.showuse = "";} objectConfig.recalculateObjectValues();});
			$("#showstats").click(function() { if(objectConfig.showstats != "on") { objectConfig.showstats = "on";} else { objectConfig.showstats = "";} objectConfig.recalculateObjectValues();});
			
			$("#usekwh").change(function() {
				objectConfig.usageenergy = $(this).val();
				Sourcemap.Editor.calculateUsage();
			});
			$("#poweruselist").change(function() {
				var selectName = $(this).parent().find(":selected").text();
				objectConfig.usagetype = selectName;
				Sourcemap.Editor.calculateUsage();
			});
			
			// Live events
			$(".input-name").live("click", function(){ if($(this).val() == Sourcemap.Template.partstubname) { $(this).val(""); } return false;});
			$(".input-origin").live("click", function(){ if($(this).val() == Sourcemap.Template.partlocationstubname) { $(this).val(""); } return false;});
			
			// Additional UI
			$("#part-visualization, .visualization").draggable({ containment: '#map_container', revert: true, handle: "#part-shell-menu, .visualization-menu" });						    
		    
			objectConfig.recalculateObjectValues();			
			Sourcemap.Editor.processHashEvent(window.location.hash);	        		
    }
};
    
var SMap = null;

// Initial call (setup for edit mode)
$(document).ready(function() {		
	Sourcemap.Editor.init();
});
