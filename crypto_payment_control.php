<? 
require_once("/home/gm1q5icx/domains/inspin.com/public_html/utilities/includes.php");

$error = 0;
$email       = param("account");
$website     = param("website");
$package_id  = $_POST["detail"];
$method      = param("method");

switch ($website) {
	
   case "inspin.com":
   $site = 1;      
   break;
   
   case "sportshandicapper.com":
   $site = 2;
   break;
   
   case "sportsbettinghandicapper.com":
   $site = 3;
   break;
   
   case "handicapper911.com":
   $site = 4;
   break;
  
   case "squatchpicks.com":
   $site = 5;
   break;    
       
   case "scratchcaddy.com":
   $site = 6;
   break;
   
   case "handicapperchic.com":
   $site = 7;
   break;
   
   case "bettingoddsforfree.com":
   $site = 8;
   break;  
}

$client = get_user_email_x_website($site,$email);

$data = explode("-",two_way($package_id,true));

if( !empty($data[3]) && isset($data[3]) ){//wp = whale package indicator

    $package_description = $data[1]. " picks subscription.";
    $amount = $data[2];
	$id_whale_package = $data[0];	
	
	$additional_message = "<br><br>Thank you so much for purchasing a ".$package_description."<br><br>You are now fully active to receive all of our exclusive picks information.<br><br>Thanks in advance and we look forward to a profitable relationship.<br><br>- ".$website;
   
}else{//Regular package

	$package_description = $data[1]. " picks subscription package.";
    $amount = $data[2];
	
	$additional_message = "<br><br>You are now fully active to receive all of our picks as well as any value-added content we have on the website.<br><br>Keep in mind if your package does not generate a profit during the term you will automatically be renewed for another term.<br><br>Thanks in advance and we look forward to a profitable relationship.<br><br>- ".$website;	
	
}

$message_purchase = "Dear: ".$client->vars["first_name"].", thank you very much for purchase a: ".$package_description.$additional_message;

if( !empty($client) && isset($client) ){ 
   
   if( !empty($data[3]) && isset($data[3]) ){//wp = whale package indicator
   
       $wpc = new _whale_package_x_customer();
	   $wpc->vars["user_id"] = $client->vars["id"];
	   $wpc->vars["whale_package_id"] = $id_whale_package;
	   $wpc->vars["payment_date"] = date("Y-m-d");							
	   $wpc->vars["amount_paid"]  = $amount;
	   $wpc->vars["active"] = 1;						
	   $wpc->vars["payment_method"] = $method;       
	   $wpc->insert_site($site);
	   
	   $record_id = $wpc->vars["id"];
			 
	   if($record_id <= 0){
		  $error = 1;		   
	   }
   
   }else{//Regular package
   
	   $days = $data[0];									 
											
	   if($client->vars["expiration_date"] == "0000-00-00" || !$client->vars["paid"]){
		  $start_date = date("Y-m-d");
	   }else{
		  $start_date = $client->vars["expiration_date"];
	   }
								
	   $client->vars["paid"] = 1;                            
	   $client->vars["expiration_date"] = date("Y-m-d",strtotime($start_date." +$days days"));
	   $return_update = $client->update_site(array("paid","expiration_date"),$site); 
	   
	   if ($return_update){ 
														
		   $payment = new _payment();
		   $payment->vars["user_id"] = $client->vars["id"];
		   $payment->vars["payment_date"] = date("Y-m-d");                            
		   $payment->vars["days_paid"]    = $days;
		   $payment->vars["amount_paid"]  = $amount;						
		   $payment->vars["payment_method"] = $method;       
		   $payment->insert_site($site);
		   
		   $record_id = $payment->vars["id"];
			 
		   if($record_id <= 0){
			   $error = 1;		   
		   }
		   
	   }else{
		   $error = 1;	  
	   }
   
   }   
   
   if ($error == 0){
	  echo "ok"; 
	  //@send_email_ck_auth($client->vars["email"], "Purchased Package Confirmation", $message_purchase, true);
	  @send_email_ck_auth($client->vars["email"], "Purchased Package Confirmation", $message_purchase, true, "support@".$website,$website);	  	  
   }   	
}
?>