jQuery(document).ready(function(){
			
	const form_fields_array = ["send_method", "sport", "date", "start_hour", "start_minute", "start_data", "rotation_number_away", "rotation_number_home", "pick", "number_stars", "team_away", "team_home", "free_pick"];
	
	jQuery(document).on("click", "#send_button", function(e){
	
			e.preventDefault();
			var send_method, sport, date, start_hour, start_minute, start_data, rot_away, rot_home, pick, error, c, number_stars, whale_package, team_away, team_home, free_pick;
					
			jQuery("#error").show();
						   
			/*send_method = jQuery("#send_method").val();			
						
			if(send_method == ""){			
				jQuery("#error").html("<h1 style='color:#F00;'>The send method is required.</h1>");
				jQuery("#send_method").focus();
				return;
			}*/
			
			/*else if(send_method == "1"){
				c=1;				
			}else if(send_method == "2"){
				c=2;							
			}*/		
			
			sport = jQuery("#sport").val();				
			
			if(sport == ""){			
				jQuery("#error").html("<h1 style='color:#F00;'>The sport is required.</h1>");
				jQuery("#sport").focus();
				return;
			}
			
			date = jQuery("#date").val();
			
			if(date == ""){			
				jQuery("#error").html("<h1 style='color:#F00;'>The game date is required.</h1>");
				jQuery("#date").focus();
				return;
			}				
			
			start_hour = jQuery("#start_hour").val();			
						
			start_minute = jQuery("#start_minute").val();			
						
			start_data = jQuery("#start_data").val();			
						
			rot_away = jQuery("#rotation_number_away").val();			
						
			if(rot_away == ""){			
				jQuery("#error").html("<h1 style='color:#F00;'>The away rotation number is required.</h1>");
				jQuery("#rotation_number_away").focus();
				return;
			}
			
			rot_home = jQuery("#rotation_number_home").val();			
						
			if(rot_home == ""){			
				jQuery("#error").html("<h1 style='color:#F00;'>The home rotation number is required.</h1>");
				jQuery("#rotation_number_home").focus();
				return;
			}
			
			team_away = jQuery("#team_away").val();			
			
			if(team_away == ""){			
				jQuery("#error").html("<h1 style='color:#F00;'>The away team is required.</h1>");
				jQuery("#team_away").focus();
				return;
			}
			
			team_home = jQuery("#team_home").val();			
			
			if(team_home == ""){			
				jQuery("#error").html("<h1 style='color:#F00;'>The home team is required.</h1>");
				jQuery("#team_home").focus();
				return;
			}			
			
			pick = jQuery("#pick").val();
						
			let pickData = new URLSearchParams();
			
			pickData.append("",pick);
			
			pick = pickData.toString();	
			
			pick = pick.replace("=", "");		
									
			if(pick == "" || !pick){			
				jQuery("#error").html("<h1 style='color:#F00;'>The pick information is required.</h1>");
				jQuery("#pick").focus();
				return;
			}
			
			number_stars = jQuery("#number_stars").val();
			
			whale_package = jQuery("#whale_package").val();	
			
			free_pick = jQuery("#free_pick").val();
								
			var ids = new Array();
			
			jQuery('input[name="send_record[]"]:checked').each(function(){
			   ids.push(jQuery(this).val());
			});
			
			if (ids.length === 0){			
				jQuery("#error").html("<h1 style='color:#F00;'>Check at least one subscriber to send the pick information.</h1>");
				return;
			}
			
			jQuery("#working-progress").show();
			jQuery("#send-message-success").hide();

			//console.log("https://www.inspin.com/utilities/process/actions/send_pick_subscribers.php?sport="+sport+"&date="+date+"&start_hour="+start_hour+"&start_minute="+start_minute+"&start_data="+start_data+"&rot_away="+rot_away+"&rot_home="+rot_home+"&pick="+pick+"&ids="+ids+"&number_stars="+number_stars+"&whale_package="+whale_package);
										
			jQuery.ajax({
				type: "POST",
				url: "https://www.inspin.com/utilities/process/actions/send_pick_subscribers.php",
				data: "sport="+sport+"&date="+date+"&start_hour="+start_hour+"&start_minute="+start_minute+"&start_data="+start_data+"&rot_away="+rot_away+"&rot_home="+rot_home+"&pick="+pick+"&ids="+ids+"&number_stars="+number_stars+"&whale_package="+whale_package+"&team_away="+team_away+"&team_home="+team_home+"&free_pick="+free_pick,		
				success: function(data) {
				   jQuery("#error").html("");
				   jQuery("#error").hide();				   							   				   				   			   	
				   jQuery("#working-progress").hide();
				   jQuery("#send-message-success").show();			   
				},
				error: function(err){
					console.log(err);
				}
			});	
			
	});
	
	jQuery(document).on("change", "#sport", function(e){		
		
		var sport = jQuery("#sport").val();
		
		$("#date").val("");
		$("#rotation_number_away").val("");
		$("#rotation_number_home").val("");
		$("#team_away").val("");
		$("#team_home").val("");
		$("#free_pick").val("");
		$("#number_stars").val("");
		$("#start_hour").val("01");
		$("#start_minute").val("00");
		$("#start_data").val("PM");
						
		jQuery.ajax({
		 url: 'https://www.inspin.com/utilities/process/actions/get_non_graded_picks.php',
		 type: 'post',
		 data: {sport:sport},
		 dataType: 'json',	 
		 success:function(response){	 
						 
			if (response != ""){	    
			 			 
				var len = response.length;
				
				jQuery("#pick").empty();
				
				jQuery("#pick").append("<option value=''>Choose one pick</option>");		
														 
				for(var i = 0; i<len; i++){
				  var pick = response[i]['pick'];
				  var id = response[i]['id'];				  
				  jQuery("#pick").append("<option value='"+pick+'**'+id+"'>"+pick+"</option>");
				}//for			
		     
			 }//if response
			 
		 },
         error: function(err){
             console.log(err);
			 jQuery("#pick").empty();
			 jQuery("#pick").append("<option value=''></option>");		
         }
		 
	   }); 
				
	});	
	
	jQuery(document).on("change", "#pick", function(e){
		
		var pick = jQuery("#pick").val();	
		
		var id = pick.split('**');
	    id = id[1];	
									
		jQuery.ajax({
		 url: 'https://www.inspin.com/utilities/process/actions/get_pick_data.php',
		 type: 'post',
		 data: {id:id},
		 dataType: 'json',	 
		 success:function(response){			
									 
			if (response != ""){
					    			 			 				
				var number_stars = response[0]["number_stars"];
				var game_date = response[0]["game_date"];
				var rot_away = response[0]["rot_away"];																
				var rot_home = response[0]["rot_home"];
				var team_away = response[0]["team_away"];																
				var team_home = response[0]["team_home"];
				var start_hour = response[0]["start_hour"];
				var start_minute = response[0]["start_minute"];
				var start_data = response[0]["start_data"];
				var whale_package = response[0]["whale_package"];
				var free_pick = response[0]["free_pick"];								
				
				$("#number_stars").val(number_stars);
				$("#date").val(game_date);
				$("#rotation_number_away").val(rot_away);
				$("#rotation_number_home").val(rot_home);
				$("#team_away").val(team_away);
				$("#team_home").val(team_home);
				$("#start_hour").val(start_hour);
				$("#start_minute").val(start_minute);
				$("#start_data").val(start_data);
				$("#whale_package").val(whale_package);
				$("#free_pick").val(free_pick);
				
				$("#start_hour > option").each(function() {
				   
                 if(start_hour == $(this).val()){
					$("#start_hour option[value='" + $(this).val() + "']").attr("selected","selected");					
				 }else{
					$("#start_hour option[value='" + $(this).val() + "']").removeAttr("selected"); 
				 }
				 
               });	   
			   
			   
			   $("#start_minute > option").each(function() {
				   
                 if(start_minute == $(this).val()){
					$("#start_minute option[value='" + $(this).val() + "']").attr("selected","selected");					
				 }else{
					$("#start_minute option[value='" + $(this).val() + "']").removeAttr("selected"); 
				 }
				 
               });		   
			   	   
			   			   
			   $("#start_data > option").each(function() {
				   
                 if(start_data == $(this).val()){
					$("#start_data option[value='" + $(this).val() + "']").attr("selected","selected");					
				 }else{
					$("#start_data option[value='" + $(this).val() + "']").removeAttr("selected"); 
				 }
				 
               });			   			   				
								
			}//if response
			 
		 },
         error: function(err){
             console.log(err);			 
         }
		 
	   }); 
				
	});	
	
	//f_render_code(form_fields_array);
	
});


function f_render_code(form_fields_array){
	
	jQuery.each(form_fields_array, function(index, item) {
										
		jQuery(document).on("change", "#"+item, function(e){
										
			e.preventDefault();       
			
			f_set_value_field(item);		
	    });
       
    });
}


function f_set_value_field(field){
	
	var i;
	
	var total_pages;
		
    total_pages = document.getElementById("total_pages").value;
			
	for (i = 1; i <= total_pages; i++) {		
	   document.getElementById(field+'_pag_'+i).value = document.getElementById(field).value;	   
	}		
	
	document.getElementById(field+'_all').value = document.getElementById(field).value;	
}