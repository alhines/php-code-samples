function do_payment(payment_method){

	

	if (payment_method == 'cc'){//credit card

	

		var seloption = document.getElementById('pay_option').value;

		document.getElementById('frm_pay'+seloption).submit();

		

    }else if (payment_method == 'cashapp'){//cash app

	    var seloption = document.getElementById('pay_option_cashapp').value;

		document.getElementById('frm_pay_cashapp'+seloption).submit();

	}

}



function validate(validations){

	var on_no_display = false; // No run validations on display = none

	var start = 0;

	

	//Settings

	//example settings: validations.push({id:"setting",type:"validate_no_display", msg:""});

	if(validations[0].type == "validate_no_display"){

		on_no_display = true;

		start++;

	} 

	//End Settings

	

	var message = "";

	var first = true;

	var submit_form = true;

	for(var i=start;i<validations.length;i++){

		if(document.getElementById(validations[i].id)){

			document.getElementById(validations[i].id).style.border = "";

		}

	}

	for(var i=start;i<validations.length;i++){

		if(document.getElementById(validations[i].id)){

			var input = document.getElementById(validations[i].id);

			if((input.style.display != "none" && input.parentNode.style.display != "none") || on_no_display){

				//null

				if(input.value == "" && validations[i].type == "null"){

					message += validations[i].msg + "\n";

					input.style.border = "1px solid #ff0000";			

					if(first){input.focus(); first = false;}

					submit_form = false;

				}

				//numeric

				if(!IsNumeric(input.value) && validations[i].type == "numeric"){

					message += validations[i].msg + "\n";

					input.style.border = "1px solid #ff0000";

					if(first){input.focus(); first = false;}

					submit_form = false;

				}

				//no numeric

				if(IsNumeric(input.value) && validations[i].type == "no_numeric"){

					message += validations[i].msg + "\n";

					input.style.border = "1px solid #ff0000";

					if(first){input.focus(); first = false;}

					submit_form = false;

				}

				//either

				if(validations[i].type.indexOf("either:") != -1){

					comp_id = validations[i].type.replace("either:","");

					if(document.getElementById(comp_id).value == "" &&  input.value == ""){

						message += validations[i].msg + "\n";

						input.style.border = "1px solid #ff0000";

						document.getElementById(comp_id).style.border = "1px solid #ff0000";

						if(first){input.focus(); first = false;}

						submit_form = false;

					}

				}

				//equal

				if(validations[i].type.indexOf("compare:") != -1){

					comp_id = validations[i].type.replace("compare:","");

					if(document.getElementById(comp_id).value != input.value){

						message += validations[i].msg + "\n";

						input.style.border = "1px solid #ff0000";

						if(first){input.focus(); first = false;}

						submit_form = false;

					}

				}

				//equal string

				if(validations[i].type.indexOf("compare_str:") != -1){

					comp = validations[i].type.replace("compare_str:","");

					if(comp != input.value){

						message += validations[i].msg + "\n";

						input.style.border = "1px solid #ff0000";

						if(first){input.focus(); first = false;}

						submit_form = false;

					}

				}	

				//different

				if(validations[i].type.indexOf("different:") != -1){

					comp_id = validations[i].type.replace("different:","");

					if(document.getElementById(comp_id).value == input.value){

						message += validations[i].msg + "\n";

						input.style.border = "1px solid #ff0000";

						if(first){input.focus(); first = false;}

						submit_form = false;

					}

				}		

				//different string

				if(validations[i].type.indexOf("different_str:") != -1){

					comp = validations[i].type.replace("different_str:","");

					if(comp == input.value){

						message += validations[i].msg + "\n";

						input.style.border = "1px solid #ff0000";

						if(first){input.focus(); first = false;}

						submit_form = false;

					}

				}

				//bigger

				if(validations[i].type.indexOf("bigger:") != -1){

					comp_id = validations[i].type.replace("bigger:","");

					if(parseInt(document.getElementById(comp_id).value) > parseInt(input.value)){

						message += validations[i].msg + "\n";

						input.style.border = "1px solid #ff0000";

						if(first){input.focus(); first = false;}

						submit_form = false;

					}

				}

				//smaller

				if(validations[i].type.indexOf("smaller:") != -1){

					comp_id = validations[i].type.replace("smaller:","");

					if(parseInt(document.getElementById(comp_id).value) < parseInt(input.value)){

						message += validations[i].msg + "\n";

						input.style.border = "1px solid #ff0000";

						if(first){input.focus(); first = false;}

						submit_form = false;

					}

				}

				//bigger than

				if(validations[i].type.indexOf("bigger_than:") != -1){

					comp = validations[i].type.replace("bigger_than:","");

					if(parseInt(comp) < parseInt(input.value)){

						message += validations[i].msg + "\n";

						input.style.border = "1px solid #ff0000";

						if(first){input.focus(); first = false;}

						submit_form = false;

					}

				}

				//smaller than

				if(validations[i].type.indexOf("smaller_than:") != -1){

					comp = validations[i].type.replace("smaller_than:","");

					if(parseInt(comp) > parseInt(input.value)){

						message += validations[i].msg + "\n";

						input.style.border = "1px solid #ff0000";

						if(first){input.focus(); first = false;}

						submit_form = false;

					}

				}

				//count bigger

				if(validations[i].type.indexOf("bigger_length:") != -1){

					comp = validations[i].type.replace("bigger_length:","");

					if(input.value.length > parseInt(comp)){

						message += validations[i].msg + "\n";

						input.style.border = "1px solid #ff0000";

						if(first){input.focus(); first = false;}

						submit_form = false;

					}

				}

				//count smaller

				if(validations[i].type.indexOf("smaller_length:") != -1){

					comp = validations[i].type.replace("smaller_length:","");

					if(input.value.length < parseInt(comp)){

						message += validations[i].msg + "\n";

						input.style.border = "1px solid #ff0000";

						if(first){input.focus(); first = false;}

						submit_form = false;

					}

				}

				//count bigger input

				if(validations[i].type.indexOf("bigger_length_input:") != -1){

					comp = document.getElementById(validations[i].type.replace("bigger_length_input:","")).value;

					if(input.value.length > parseInt(comp)){

						message += validations[i].msg + "\n";

						input.style.border = "1px solid #ff0000";

						if(first){input.focus(); first = false;}

						submit_form = false;

					}

				}

				//count smaller input

				if(validations[i].type.indexOf("smaller_length_input:") != -1){

					comp = document.getElementById(validations[i].type.replace("smaller_length_input:","")).value;

					if(input.value.length < parseInt(comp)){

						message += validations[i].msg + "\n";

						input.style.border = "1px solid #ff0000";

						if(first){input.focus(); first = false;}

						submit_form = false;

					}

				}

				//email

				if(validations[i].type == "email"){

					var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;

					if(reg.test(input.value) == false) {

						message += validations[i].msg + "\n";

						input.style.border = "1px solid #ff0000";

						if(first){input.focus(); first = false;}

						submit_form = false;

					}

				}

				//radio

				//radio:divid,id1,id2,id3...

				if(validations[i].type.indexOf("radio:") != -1){

					full = validations[i].type.replace("radio:","");

					arr = full.split(",");

					var onecheck = false;

					for(var c = 0; c < arr.length; c++){

						if(document.getElementById(arr[c]).checked){onecheck = true;}

					}

					if(!onecheck){

						message += validations[i].msg + "\n";

						input.style.border = "1px solid #ff0000";			

						if(first){input.focus(); first = false;}

						submit_form = false;

					}

				}

				//checkbox

				//checkbox:basename_,1

				if(validations[i].type.indexOf("checkbox:") != -1){

					full = validations[i].type.replace("checkbox:","");

					arr = full.split(",");

					base_id = arr[0];

					min_checks = arr[1];

					chks = document.getElementsByTagName("input");

					count = 0;

					for(var e=0;e<chks.length;e++){

						if(chks[e].type == "checkbox"){

							if(chks[e].checked && chks[e].id.indexOf(base_id) != -1){

								count++;

							}

						}

					}

					if(count < min_checks){

						message += validations[i].msg + "\n";

						input.style.border = "1px solid #ff0000";

						submit_form = false;

					}

				}

			}

		}

	}

	if(message != ""){alert(message);}

	return submit_form;

}

function IsNumeric(input){

   return (input - 0) == input && input.length > 0;

}

function inArray(a, obj){

  for(var i = 0; i < a.length; i++) {

    if(a[i] === obj){

      return true;

    }

  }

  return false;

}



//selects value on list when load

function set_select_input(ddlID, value, change){

	var ddl = document.getElementById(ddlID);

	for (var i = 0; i < ddl.options.length; i++) {

		if (ddl.options[i].value == value) {

			if (ddl.selectedIndex != i) {

				ddl.selectedIndex = i;

				if (change){ddl.onchange();}

			}

		   break;

	   }

   }

}

//loads into element external content

function load_external_content(url, div, code){

		if(location.href.indexOf("www.") != -1){

			url = "http://www." + url;

		}else{

			url = "http://" + url;

		}

		if (window.XMLHttpRequest) {    

			req = new XMLHttpRequest();    

		}    

		else if (window.ActiveXObject) {  

			req = new ActiveXObject("Microsoft.XMLHTTP");    

		}

		return read_from_file(url, req, div, code);

}

function read_from_file(filename, req, div, code) {

	nocache = Math.random();

	if(filename.indexOf("?") != -1){

		req.open('GET', filename + "&nocache="+nocache);

	}else{

		req.open('GET', filename + "?nocache="+nocache);

	}

	req.onreadystatechange = function() {   

		if (req.readyState == 4) {

			content = req.responseText;

			if(content == ""){content = "No Information Available";}

			if(document.getElementById(div) != null){

				document.getElementById(div).innerHTML = content;

				if(code != null){setTimeout(code,1);}

			}

		} 

	}

	req.send("");

}



//Example:  onfocus="revert_value(this,'Write your name');"

function revert_value(txt_box, msg, is_pass){

	if(txt_box.value == msg){

		txt_box.value = "";

		if(is_pass){txt_box.type = 'password';}

	}else if(txt_box.value == "" || txt_box.value == " "){

		txt_box.value = msg;

		if(is_pass){txt_box.type = 'text';}

	}

}



function contains(full, search_str){

	var found = false;

	if(full.indexOf(search_str) != -1){found = true;}

	return found;

}

function display_states(country, base_id){

       

	   if(country == '') {

		  var country  = document.getElementById("country").value; 

	   }

	   

	   var usa_list = document.getElementById(base_id + "_usa");

       var can_list = document.getElementById(base_id + "_can");

       var def_list = document.getElementById(base_id + "_none");	  

	   

       switch(country){

        case "United States" :

            def_list.style.display = "none";

            def_list.name = "nothing";

            usa_list.style.display = "block";

            usa_list.name = base_id;

            can_list.style.display = "none";

            can_list.name = "nothing";           

        break;

        case "Canada" :

            def_list.style.display = "none";

            def_list.name = "nothing";

            usa_list.style.display = "none";

            usa_list.name = "nothing";

            can_list.style.display = "block";

            can_list.name = base_id;            

        break;        

        default:

            def_list.style.display = "block";

            def_list.name = base_id;

			def_list.value= "";

            usa_list.style.display = "none";

            usa_list.name = "nothing";

            can_list.style.display = "none";

            can_list.name = "nothing";            

       }    

}



function change_page(value){

	document.location.href = "https://www.inspin.com/wp-admin/" + value;

}

function confirm_action(value, type, action){

	var answer = confirm ("Are you sure you want to "+action+" this "+type+"?")

	if (answer){change_page(value)}

}

function add_remove_records(id){

	var container = document.getElementById("delete_records_form");

	var input = document.createElement("input");

		

	if(document.getElementById('record_'+id).checked){		

        input.type  = "checkbox";

        input.name  = 'record['+id+']';

		input.id    = 'record['+id+']';

	    input.value = id;

		input.style.display = "none";

		input.checked = true;        			

        container.insertBefore(input, document.getElementById('delete_records'));		

	}else{

		var record = document.getElementById('record['+id+']');

            record.parentNode.removeChild(record);

	}

}

function f_check_all_send_checkboxes(){

	

	var inputs = document.getElementsByClassName("checks_send");

	

	for (var i = 0; i < inputs.length; i++) {

		

		if (inputs[i].type == "checkbox") {  //Check only the checked checkboxes	   		   

		  			 

			myarr = inputs[i].name.split("_");

			id   = myarr[2];					

			

			if (document.getElementById('send_record_'+id).checked == false){

				document.getElementById('send_record_'+id).checked = true;			   

			    send_checked_records(id);

			}			

		

		}

	}

}





function f_check_custom_send_checkboxes(class_name){

	

	var inputs = document.getElementsByClassName(class_name);

	

	for (var i = 0; i < inputs.length; i++) {

		

		if (inputs[i].type == "checkbox") {  //Check only the checked checkboxes	   		   

		  			 

			myarr = inputs[i].name.split("_");

			id   = myarr[2];					

			

			if (document.getElementById('send_record_'+id).checked == false){

				document.getElementById('send_record_'+id).checked = true;			   

			    send_checked_records(id);

			}			

		

		}

	}

}





function f_uncheck_all_send_checkboxes(){

	

	var inputs = document.getElementsByClassName("checks_send");

	

	for (var i = 0; i < inputs.length; i++) {

		

		if (inputs[i].type == "checkbox") {  //Check only the checked checkboxes	   		   

		  			 

			myarr = inputs[i].name.split("_");

			id   = myarr[2];					

			

			if (document.getElementById('send_record_'+id).checked == true){

				document.getElementById('send_record_'+id).checked = false;			   

			    send_checked_records(id);

			}			

		

		}

	}

}



function send_checked_records(id){

	var container = document.getElementById("send_checked_records_form");

	var input = document.createElement("input");

		

	if(document.getElementById('send_record_'+id).checked){		

        input.type  = "checkbox";

        //input.name  = 'send_record['+id+']';

		input.name  = 'send_record[]';

		input.id    = 'send_record['+id+']';

	    input.value = id;

		input.style.display = "none";

		input.checked = true;        			

        container.insertBefore(input, document.getElementById('send_pick_info'));		

	}else{

		var record = document.getElementById('send_record['+id+']');

            record.parentNode.removeChild(record);

	}

}



function f_geo_targeting_restriction(){

	

	var country;	

			

	$.getJSON("https://www.inspin.com/utilities/process/actions/get_visitor_geo_targeting.php", function(data){		

    

		country = data.country;	

		

		if (country == "United States") {

			

		   if(document.getElementById("gtbets_728x90")){					

		      document.getElementById("gtbets_728x90").style.display = "block";

		   }

		   

		   if(document.getElementById("gtbets_300x250")){					

		      document.getElementById("gtbets_300x250").style.display = "block";

		   }

		   

		   if(document.getElementById("gtbets_160x600")){					

		      document.getElementById("gtbets_160x600").style.display = "block";

		   }

		   

		}else{

		   	

		   if(document.getElementById("gtbets_728x90")){	

		      document.getElementById("gtbets_728x90").style.display = "none";	

		   }

		   

		   if(document.getElementById("gtbets_300x250")){					

		      document.getElementById("gtbets_300x250").style.display = "none";

		   }

		   

		   if(document.getElementById("gtbets_160x600")){					

		      document.getElementById("gtbets_160x600").style.display = "none";

		   }

		   

		}



    });					

	

}


function submitUserForm() {
    var response = grecaptcha.getResponse();
    if(response.length == 0) {
        document.getElementById('g-recaptcha-error').innerHTML = '<span style="color:red;">This field is required.</span>';
        return false;
    }
    return true;
}
 
function verifyCaptcha() {
    document.getElementById('g-recaptcha-error').innerHTML = '';
}