// Add the options to all the dialogs
$(".dialog").dialog({ autoOpen: false, resizable: false, draggable: false, width: 400, height: 200, title: "Pick one", modal: true });
$( "#impacts_dialog" ).dialog( "option", "title", "Select the environmental impact" );
$( "#people_dialog" ).dialog( "option", "title", "Select an exiting author" );
$( "#unit_dialog" ).dialog( "option", "title", "Select the unit" );

$('.hide').hide();  
$('.show').show();  
$(".popup").click(function() {
    var the_id = $(this).attr("id");
    var the_name = $(this).attr("name");
    $("#"+the_id+"_dialog").dialog('open');
    $("[name='" + the_id + "_field']").val(the_name);
    //var val = $("[name='" + the_id + "_field']").val();
    // prevent the default action, e.g., following a link
}); 

$("[name*='_label']").keyup(function() {
	$(this).removeClass("linked_value");
	var label = $(this).attr('name');
    var hidden = label.replace("_label", "");
    $("[name='"+hidden+"']").val(""); 	
}); 
 
 
$("#people").click(function() {
     
    var field = $("[name='people_field']").val();
    var button = $(this).attr('name');
    var label = button.replace("button", "label");
    var label_value = $("[name='"+label+"']").val();
    var name = new Array();
    name = label_value.split(" ");
    var firstName = "";
    var lastName = "";
    if (name.length == 2) {
        firstName = name[0];
        lastName = name[1];
    } else if (name.length == 3) {
        firstName = name[0];
        lastName = name[1] + " " + name[2];     
    } else if (name.length == 1) {
        lastName = name[0];
    }
 
    var data = "";
    if (firstName != "") {
        data += "firstName=" + firstName;
    }
    if (lastName != "") {
        if (data != "") {
            data +="&";
        } 
        data += "lastName=" + lastName;
    }       
    if (data != "") {
        $.ajax({ 
            url: "/people/lookup/", 
            type: "post",
            data: data,
            success: function(data){
                var returned_info = $.parseJSON(data);          
                var results = "";
                for (var i=0; i< returned_info.length; i++) {
                    results += '<a href="javascript:void(0)" id="'+returned_info[i].uri+'" class="person">' + returned_info[i].firstName + ' ' + returned_info[i].lastName;
                    if (typeof returned_info[i].email != "undefined") {
                        results += " - " + returned_info[i].email;
                    }
                    results += "</a><br />";
                }
                $("div[id='people_dialog']").html(results);     
                 
                $('.person').click(function() {
                    var the_value = $(this).attr('id');
                    var field = $("[name='people_field']").val();
                    var the_label_field = field.replace("_button", "_label");
                    var the_hidden_field = field.replace("_button" ,"");
                    var the_label = $("[id='"+the_value+"']").text();
                    $("[name='"+the_label_field+"']").val(the_label);
					$("[name='"+the_label_field+"']").addClass("linked_value");
                    $("[name='"+the_hidden_field+"']").val(the_value);
                });     
              }
        });
    }
}); 
 
 
// Changes Unit Sub-menu 
$("[name='impacts_main']").change(function() { 
    var name = $("option[value='"+$(this).val()+"']").text();
	$("[name^='impacts'][class*='hide'][name!='impacts_main']").hide(); 
	$("[name='impacts_"+name+"']").show();
	var the_value = $(this).val();
	var field = $("[name='impacts_field']").val();
	var the_label_field = field.replace("_button", "_label");
	var the_hidden_field = field.replace("_button" ,"");
	var the_label = $("[value='"+the_value+"']").text();
	$("[name='"+the_label_field+"']").val(the_label);
	$("[name='"+the_label_field+"']").addClass("linked_value");
	$("[name='"+the_hidden_field+"']").val(the_value);
});
 
$('#impact_form').submit(function() {
	var main = $("option[value='"+$("[name='impacts_main']").val()+"']").text();
	catname = "impacts_"+main;
	var the_value = $("[name='"+catname+"']").val();
    var field = $("[name='impacts_field']").val();
    field = field.replace("impactCategory","impactCategoryIndicator");
    var the_label_field = field.replace("_button", "_label");
    var the_hidden_field = field.replace("_button" ,"");
    var the_label = $("[value='"+the_value+"']").text();
    $("[name='"+the_label_field+"']").val(the_label);
	$("[name='"+the_label_field+"']").addClass("linked_value");
    $("[name='"+the_hidden_field+"']").val(the_value);
    $( "#impacts_dialog" ).dialog( "close" )
  	return false;
});
 
// Changes Unit Sub-menu 
$("[name='unit_main']").change(function() { 
	$("[name^='unit'][class*='hide'][name!='unit_main']").hide(); 
	$("[name='unit_"+$(this).val()+"']").show();
});
 
// Save the values and the links when submitting unit form
$('#unit_form').submit(function() {
  	var main = $("[name='unit_main']").val();
	catname = "unit_"+main;
	var the_value = $("[name='"+catname+"']").val();
    var field = $("[name='unit_field']").val();
    var the_label_field = field.replace("button", "label");
    var the_hidden_field = field.replace("_button" ,"");
    var the_label = $("[value='"+the_value+"']").text();
    $("[name='"+the_label_field+"']").val(the_label);
	$("[name='"+the_label_field+"']").addClass("linked_value");
    $("[name='"+the_hidden_field+"']").val(the_value);
	$( "#unit_dialog" ).dialog( "close" )
  	return false;
});


function addField(name, path) { 
    var array_path = "[" + path.replace(/-/g, "][") + "]";
    var to_copy = "#div_" + name;
    var to_paste = "#div_multiple_" + name + "_" + path;    
    var to_duplicate = $(to_copy).html();
    var to_count = "#" + name + "_counter_" + path; 
    var increment = $(to_count).val();
    increment = parseInt(increment) + 1;
    if (path.length > 2) {
    var one_less = path.slice(0, path.length -2);
 
    var new_array_path = '_[' + path.slice(0, path.length -2).replace(/-/g, "][") + ']' + '[' + increment + ']'; 
    var new_path = '_' + path.slice(0, path.length -2) + '-' + increment;       
    } else {
        var new_array_path = '_[' + increment + ']"';
        var new_path = '_' + increment;
    }   
    array_path = "_" + array_path;
    path = "_" + path;  
    var duplicated = to_duplicate;
    while (duplicated.indexOf(path) != -1) {
        duplicated = duplicated.replace(path,new_path);
    }
    while (duplicated.indexOf(array_path) != -1) {
        duplicated = duplicated.replace(array_path,new_array_path);
    }
    $(to_paste).append(duplicated);
    $(to_count).val(increment);
	$(".popup").click(function() {
	    var the_id = $(this).attr("id");
	    var the_name = $(this).attr("name");
	    $("#"+the_id+"_dialog").dialog('open');
	    $("[name='" + the_id + "_field']").val(the_name);
	    //var val = $("[name='" + the_id + "_field']").val();
	    //console.log(val);
	    console.log(the_id);
	    // prevent the default action, e.g., following a link
	});
}
 
function toggle_delete(field) {
    if ($("input[name='" + field + "']").is(':disabled') == false) {
        $("input[name='" + field + "']").attr('disabled', 'disabled');      
    } else {
        $("input[name='" + field + "']").removeAttr('disabled');
    }
}

$("[name='submit_']").click(function() {
    var to_submit = true;
  $(".required").each(function() {
		var error_field = $(this).attr('name')+"error";
        if($(this).val() == "") {
            $(this).addClass('require');  
      		$(this).removeClass('require_ok');   
			$("[name='"+error_field+"']").html('Required!'); 
            to_submit = false;
        } else if ($(this).val() != "" && $("[name='"+error_field+"']").html() == "Required!") {
            $(this).addClass('require_ok');   
			$(this).removeClass('require');  
			$("[name='"+error_field+"']").html(''); 
        }
    });
  $(".require").each(function() {
        to_submit = false;
    });
	if (to_submit == true) {
		$('#register').submit();
	} 
});

var email_reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;	
$(".email_validation").keyup(function() {
	var error_field = $(this).attr('name')+"error";
		if ($(this).val() != "") {		
	        if(email_reg.test($(this).val()) == false) {
	            $(this).addClass('require');  
				$(this).removeClass('require_ok');   
				var error_field = $(this).attr('name')+"error";
				$("[name='"+error_field+"']").html('email@format.pls');  		
	        } else {
	            $(this).addClass('require_ok');   
				$(this).removeClass('require');     
				$("[name='"+error_field+"']").html('');
	        }
		}
  });
var url_reg = /(http):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;	
$(".url_validation").keyup(function() {
	var error_field = $(this).attr('name')+"error";
		if ($(this).val() != "") {		
	        if(url_reg.test($(this).val()) == false) {
	            $(this).addClass('require');  
				$(this).removeClass('require_ok');   
				var error_field = $(this).attr('name')+"error";
				$("[name='"+error_field+"']").html('This must be a valid URL');  			
	        } else {
	            $(this).addClass('require_ok');   
				$(this).removeClass('require');     
				$("[name='"+error_field+"']").html('');
	        }
		} else {
            $(this).addClass('require_ok');   
			$(this).removeClass('require');     
			$("[name='"+error_field+"']").html('');			
		}
  });

$(".email_taken").keyup(function() {
		var e_the_field_name = $(this).attr('name');
		if ($(this).val() != "" && $("[name='"+e_the_field_name +"error']").html() != 'email@format.pls') {
			var e_answer = "false";
			var e_data = "email="+$(this).val();
			$.ajax({ 
	          url: "/users/takenEmail/", 
	          type: "post",
			  data: e_data,
	          success: function(data){
					e_answer = data.toString().trim();
					if(e_answer == "true") {
			            $("[name='"+e_the_field_name +"']").addClass('require');  
						$("[name='"+e_the_field_name +"']").removeClass('require_ok');   
						$("[name='"+e_the_field_name +"error']").html('Already taken');  		
				    } else {
						$("[name='"+e_the_field_name +"error']").html('');
						$("[name='"+e_the_field_name +"']").addClass('require_ok');   
						$("[name='"+e_the_field_name +"']").removeClass('require');  
				    }
	            },
				failure: function(jqXHR, textStatus, errorThrown) {
					var error = jqXHR + " - " + textStatus + " - " + errorThrown;
					alert(error);
				}
	     	}); 
  	}
  });
	$(".name_taken").keyup(function() {
			if ($(this).val() != "") {
				var answer = "false";
				var data = "name="+$(this).val();
				var the_field_name = $(this).attr('name');
				$.ajax({ 
		          url: "/users/takenName/", 
		          type: "post",
				  data: data,
		          success: function(data){
						answer = data.toString().trim();
						if(answer == "true") {
				            $("[name='"+the_field_name +"']").addClass('require');  
							$("[name='"+the_field_name +"']").removeClass('require_ok');   
							$("[name='"+the_field_name +"error']").html('Already taken');  			
					    } else {
				            $("[name='"+the_field_name +"']").addClass('require_ok');   
							$("[name='"+the_field_name +"']").removeClass('require');   
							$("[name='"+the_field_name +"error']").html(' ');  
					    }
		            }
		      	});
			}
	    });
		$(".password_match").keyup(function() {
				var field1_name = $(this).attr('name');
				if (field1_name.substr(field1_name.length-2,2) == "2_") {
					var field2_name = field1_name.substr(0,field1_name.length-2)+"_";
				} else {
					var field2_name = field1_name.substr(0,field1_name.length-1)+"2_";
				}
				var val1 = $(this).val();
				var val2 = $("[name='"+field2_name+"']").val();
				if (val1 != val2) {
			            $("[name='"+field1_name +"']").addClass('require');  
						$("[name='"+field1_name +"']").removeClass('require_ok');   
						$("[name='"+field2_name +"']").addClass('require');  
						$("[name='"+field2_name +"']").removeClass('require_ok');
						$("[name='"+field1_name +"error']").html('Passwords must match');  		
						$("[name='"+field2_name +"error']").html('Passwords must match'); 	
				    } else {
			            $("[name='"+field1_name +"']").removeClass('require');  
						$("[name='"+field1_name +"']").addClass('require_ok');   
						$("[name='"+field2_name +"']").removeClass('require');  
						$("[name='"+field2_name +"']").addClass('require_ok');
						$("[name='"+field1_name +"error']").html('');
						$("[name='"+field2_name +"error']").html('');
				    }
		    });


