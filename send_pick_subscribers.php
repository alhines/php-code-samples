<? 
require_once("/home/gm1q5icx/domains/inspin.com/public_html/utilities/includes.php");

date_default_timezone_set('America/New_York');

//PARAMETERS

$sport         = param("sport");
$date          = secure_input($_POST["date"]);
$start_hour    = param("start_hour");
$start_minute  = param("start_minute");
$start_data    = param("start_data");
$rot_away      = param("rot_away"); 
$rot_home      = param("rot_home");
$team_away     = param("team_away"); 
$team_home     = param("team_home");
$number_stars  = param('number_stars');
$whale_package = param('whale_package');

$whale_package_info = get_whale_package($whale_package);

$free_pick     = param('free_pick');

if($number_stars == 1){
	$stars_text = "STAR";
}else{
	$stars_text = "STARS";
}

$pick_info  = explode("**",$_POST["pick"]);
$pick         = $pick_info[0];

$ids          = param("ids");
$array_ids    = explode(",",$ids);
$start_time   = $date." ".$start_hour.":".$start_minute." ".$start_data;
$start_time   = date("Y-m-d, g:i a",strtotime($start_time));

$customers_purchased_whale_package = get_customers_purchased_whale_package($whale_package);

$subscribers = get_all_users_by_list($ids,"id,email,how_get_picks,paid,unsubscribe_picks_by_email,expiration_date"); // get current subscribers

if($free_pick){
	$top_title_pick = "FREE ";
}else{
	$top_title_pick = $number_stars." ".$stars_text;
}

$telegram_users = get_all_telegram_user_by_email(1);

$telegram = new _Bot("");
		
$reminder = "We know you chose TELEGRAM as the method to receive our expert picks, however it looks like you haven't completed telegram registration process yet: <BR><BR>";

$reminder .= $telegram_steps;

$today = date("Y-m-d");

if(!empty($subscribers) && $pick != ""){ // Always check before a Foreach that the array is not empty, otherwise this is going to fail

	foreach($subscribers as $subs){
		
		$expiration_date = date("Y-m-d", strtotime($subs->vars["expiration_date"]));		
		$customer = get_user_obj($subs->vars["id"]);		
		$units = $customer->get_user_units();
        $total_units_user = $units["total_units_user"];				

        if(empty($total_units_user)){
	       $total_units_user = 0;
        }
		
		$unsubscribe_email = "<br><br>Important: <a href='https://www.inspin.com/unsubscribe/?email=".$subs->vars["email"]."'>Click here</a> to unsubscribe if you no longer wish to receive our expert picks by email.";
		
		$content = "";
		$msj = "";	
		
		$content      = $top_title_pick." PICK<br>";
	    $content     .= "Game DateTime: ".$start_time."<br>";
	    $content     .= "Sport: ".$sport."<br>";
	    $content     .= "Rotation # Away: ".$rot_away."<br>";
	    $content     .= "Rotation # Home: ".$rot_home."<br>";
	    $content     .= $team_away." vs. ".$team_home."<br>";
	
	    $msj = "---------------------------------------\n".
	    "    ".$top_title_pick." PICK    \n".
	    "--------------------------------------\n";

	    $msj .= "Game DateTime: ".$start_time." \n";
	    $msj .= "Sport: ".$sport." \n";       
	    $msj .= "Rot Away: ".$rot_away." \n";       
	    $msj .= "Rot Home: ".$rot_home." \n"; 
	    $msj .= $team_away." vs. ".$team_home." \n";
		
		if($free_pick){
			
	      $content .= "Pick: ".$pick;		   
	      $msj .= "Pick: ".$pick." \n";       
	      $msj .= "---------------------------------------\n";
		      					   
	    }else{//Regular or whale package pick			
						
			if($whale_package != 0){//Whale package pick
			
			    if (isset($customers_purchased_whale_package[$subs->vars["id"]])){//The customer purchased a whale package, so send him the pick
				   
				   $content .= "Pick: ".$pick;
				   $msj .= "Pick: ".$pick." \n";
				   $msj .= "---------------------------------------\n";
				
				}else{//Otherwise, send him this message: Join Now and buy the whale package for $.
				
				   $content .=  "<a href='https://www.inspin.com/join/#JoinForm'>Join Now</a> and buy the ".$whale_package_info->vars["name"]." for $".$whale_package_info->vars["price"]."!";
				   $msj .= "Click on this link: https://www.inspin.com/join/#JoinForm to Join Now and buy the ".$whale_package_info->vars["name"]." for $".$whale_package_info->vars["price"]."!\n";       
				   $msj .= "---------------------------------------\n";
				}		
				
			}else{//Regular package pick	 		
			
				//if( $subs->vars["paid"] == 1 ){//Paid Current Pick Package
				if( $expiration_date > $today or $total_units_user < 0 ){//Customer renews or paid another package  
				   $content .= "Pick: ".$pick;
				   $msj .= "Pick: ".$pick." \n";
				   $msj .= "---------------------------------------\n";         
				//}elseif($subs->vars["paid"] == 0){//Not Paid Current Pick Package
				}else{
				   $content .=  "<a href='https://www.inspin.com/join/#JoinForm'>Join Now</a> and buy a Package for as little as $24.99!";
				   $msj .= "Click on this link: https://www.inspin.com/join/#JoinForm to Join Now and buy a Package for as little as $24.99!\n";       
				   $msj .= "---------------------------------------\n";	
				}	
				
			}			
												
		}//free pick		

        $not = new _notifications();

		switch ($subs->vars["how_get_picks"]){

  	    	case 0 : // By email
			case 1 : // By email
			
			 if($subs->vars["unsubscribe_picks_by_email"] != 1){//Customers that want to receive picks by email			   			
  				send_email_ck_auth($subs->vars["email"], "Pick Information", $content.$unsubscribe_email, true, $from = "support@inspin.com");
				
  				$not->save_not("pe",$subs->vars["email"],$content);
			 }
			 
            break;

  			case 2: // telegram
			
  			  if(isset($telegram_users[$subs->vars["email"]]["phone_id"])){ // if the user is Registered to Telegram
                $result = json_decode($telegram->envioMensajeProcesos($telegram_users[$subs->vars["email"]]["phone_id"],$msj),true);
                 if($result['ok']){
                  $not->save_not("pt",$subs->vars["email"],$msj);
                 }
  			  }else { //USER chose Telegram but is not Registered, So we will send the pick with a reminder by Email.
  			  	  //$reminder .= $subs->vars["email"];
                  //$content .= "<BR><BR>";
                  //$content .= $reminder;
				  
				  //if($subs->vars["unsubscribe_picks_by_email"] != 1){//Customers that want to receive picks by email
				  
                  //send_email_ck_auth($subs->vars["email"], "Pick Information", $content.$unsubscribe_email, true, $from = "support@inspin.com");
				  //$not->save_not("pe",$subs->vars["email"],$content);
				  
				  //}				  
                  
  			  }
  			   break;
			   	
  			 case 3: // Email & telegram  
  			                    
			  if($subs->vars["unsubscribe_picks_by_email"] != 1){//Customers that want to receive picks by email
			    send_email_ck_auth($subs->vars["email"], "Pick Information", $content.$unsubscribe_email, true, $from = "support@inspin.com");
				$not->save_not("pe",$subs->vars["email"],$content);
			  }             
  			                  
              if(isset($telegram_users[$subs->vars["email"]]["phone_id"])){ // if the user is Registered to Telegram
                     //$telegram->envioMensajeProcesos($telegram_users[$subs->vars["email"]]["phone_id"],$msj);
                    $result = json_decode($telegram->envioMensajeProcesos($telegram_users[$subs->vars["email"]]["phone_id"],$msj),true);
                    if($result['ok']){
                      $not = new _notifications();
                      $not->save_not("pt",$subs->vars["email"],$msj);
                    }
  			        }else { //USER chose Telegram but is not Registered, So we will send the pick with a reminder by Email.
  			  	     
                     //$content .= "<BR><BR>";
                     //$content .= $reminder;
					 
					//if($subs->vars["unsubscribe_picks_by_email"] != 1){//Customers that want to receive picks by email 
                    /*send_email_ck_auth($subs->vars["email"], "Pick Information", $content.$unsubscribe_email, true, $from = "support@inspin.com");					
					
                    $not = new _notifications();
                    $note = "- Not Telegram Registered - ".$msj;
                    $not->save_not("pe",$subs->vars["email"],$note);*/
					
					//}
					
  			      }
  			   break;	
  			}

  		}			
  	}

	  
?>

