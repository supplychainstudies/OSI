$(".dialog").dialog({
            autoOpen: false,
            title: 'Basic Dialog'
        });
$('.hide').hide();  
$('.show').show();  
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
 
 
$("#people").click(function() {
     
    var field = $("[name='people_field']").val();
    console.log(field);
     
     
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
$("[name='"+the_hidden_field+"']").val(the_value);
});
 
$("[name^='impacts'][name!='impacts_main']").change(function() {
    var the_value = $(this).val();
    var field = $("[name='impacts_field']").val();
    field = field.replace("impactCategory","impactCategoryIndicator");
    var the_label_field = field.replace("_button", "_label");
    var the_hidden_field = field.replace("_button" ,"");
    var the_label = $("[value='"+the_value+"']").text();
    $("[name='"+the_label_field+"']").val(the_label);
    $("[name='"+the_hidden_field+"']").val(the_value);
    //alert(the_value);
});
 
 
// Changes Unit Sub-menu 
$("[name='unit_main']").change(function() { 
$("[name^='unit'][class*='hide'][name!='unit_main']").hide(); 
$("[name='unit_"+$(this).val()+"']").show();
});
 
$("[name^='unit'][name!='unit_main']").change(function() {
    var the_value = $(this).val();
    var field = $("[name='unit_field']").val();
    var the_label_field = field.replace("_button", "_label");
    var the_hidden_field = field.replace("_button" ,"");
    var the_label = $("[value='"+the_value+"']").text();
    $("[name='"+the_label_field+"']").val(the_label);
    $("[name='"+the_hidden_field+"']").val(the_value);
    //alert(the_value);
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
}
 
function toggle_delete(field) {
    if ($("input[name='" + field + "']").is(':disabled') == false) {
        $("input[name='" + field + "']").attr('disabled', 'disabled');      
    } else {
        $("input[name='" + field + "']").removeAttr('disabled');
    }
}
 
$("form").submit(function() {
    var to_submit = true;
  $(".required").each(function() {
        if($(this).val() == "") {
            $(this).addClass('require');        
            to_submit = false;
        } else {
            $(this).addClass('require_ok');     
        }
    });
    return to_submit;
});