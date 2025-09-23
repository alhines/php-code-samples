<? 
session_start();

require_once("/home/gm1q5icx/domains/inspin.com/public_html/utilities/includes.php");
require_once("/home/gm1q5icx/domains/inspin.com/public_html/utilities/includes/authorize-net/AuthorizeNetPayment.php");

date_default_timezone_set('America/New_York');

$reguser = $_SESSION["reguser"];

$client = get_user_obj($reguser);

$_POST["customer-first-name"] = $client->vars["first_name"];
$_POST["customer-last-name"]  = $client->vars["last_name"];
$_POST["customer-email"] = $client->vars["email"];
$_POST["invoice-number"] = $_SESSION["invoice-number"];

if( isset($_SESSION["chosen_payment"]) && !empty($_SESSION["chosen_payment"])){

	$package_id = $_POST["package-id"];
	
	$exist_package = false;
	
	foreach($pay_options as $popt){
		
		if($popt["encripted_package"] == $package_id){
			
			$exist_package = true;
			$data = explode("-",two_way($package_id,true));
			$package_description = "Inspin ".$data[1];
			$amount = $data[2];
			$_POST["amount"] = $amount;
			$package_description .= " subscription";
			$_POST["description"] = $package_description;	
		}
	}
	
}elseif( isset($_SESSION["chosen_whale_page"]) && !empty($_SESSION["chosen_whale_page"]) ){
	
	$exist_package = false;
	
	$whale_package = get_whale_package($_SESSION["chosen_whale_page"]);
	
	if(!empty($whale_package)){	
		
		$exist_package = true;
		$package_description = "Inspin ".$whale_package->vars["name"];
	    $amount = $whale_package->vars["price"];
		$_POST["amount"] = $amount;
		$package_description .= " subscription";
		$_POST["description"] = $package_description;
		
	}
}

if( !empty($reguser) && isset($reguser) ){
	
	  if($exist_package) {//Package exist 	
  	
		$authorizeNetPayment = new AuthorizeNetPayment();
			
		$response = $authorizeNetPayment->chargeCreditCard($_POST);
			
		if ($response != null)
		{	   	   
		   $tresponse = $response->getTransactionResponse();
		   
		   if ($tresponse != null){
		   		   		   				
			   $signatureKey = hex2bin(SIGNATURE_KEY);       
			   $transId = $tresponse->getTransId();
			   $string = '^'.API_LOGIN_ID.'^'.$transId.'^'.$amount.'^';		   
			   $hash = $tresponse->getTransHashSha2();				
			   $digest = strtoupper(HASH_HMAC('sha512',$string,$signatureKey));
			   
			   /*echo "signatureKey: ".$signatureKey;
			   echo "<br>";
			   echo "transId: ".$transId;
			   echo "<br>";
			   echo "string: ".$string;
			   echo "<br>";
			   echo "hash: ".$hash;
			   echo "<br>";			   
			   echo "digest: ".$digest;
			   echo "<br>";
			   echo "amount: ".$amount;
			   echo "<br>";
			   echo "API_LOGIN_ID: ".API_LOGIN_ID;
			   echo "<br>";*/
							   
			   $days = $data[0];
							
			   if(hash_equals($digest,$hash)){	
							
					   if (($tresponse != null) && ($tresponse->getResponseCode()=="1"))
					   {					
							$final_user = get_user_obj($reguser);					
										 
							$error = 0; 
							
							if( isset($_SESSION["chosen_payment"]) && !empty($_SESSION["chosen_payment"])){
									
								$email_subject = "Package Confirmation";
																
								if($final_user->vars["expiration_date"] == "0000-00-00" || !$final_user->vars["paid"]){
									$start_date = date("Y-m-d");
								}
								else{
									$start_date = $final_user->vars["expiration_date"];
								}
								
								$final_user->vars["paid"] = 1;                            
								$final_user->vars["expiration_date"] = date("Y-m-d",strtotime($start_date." +$days days"));
								$final_user->update(array("paid","expiration_date"));
														
								$payment = new _payment();
								$payment->vars["user_id"]      = $final_user->vars["id"];
								$payment->vars["payment_date"] = date("Y-m-d");                            
								$payment->vars["days_paid"]    = $days;
								$payment->vars["amount_paid"]  = $amount;						
								$payment->vars["payment_method"] = "cc";       
								$payment->insert();
								
								$message .= "Subscription name: ".$package_description."<br>";
								$message .= "Charged amount: $".$amount."<br>";
								$message .= "Purchase date: ".date("Y-m-d")."<br>";
								$message .= "Expiration date: ".$final_user->vars["expiration_date"]."<br><br>";
								$message .= "Important:<br>";
								$message .= "<ul>
         <li>
         This is a one-time charge for the package you chose. You will not be automatically billed or recharged. To continue service, you must manually pay again at your convenience if you are happy with the service.<br /><br />
         </li>
         <li>If your betting results based on our picks are not net positive during the trial, you’ll continue to receive access until a positive outcome is achieved. No additional charges will occur until your account shows a net positive, and then you can decide to renew your package yourself. We will not charge automatically.<br /><br /></li>
         <li>All purchases are final. This subscription does not auto-renew, but after you purchase, you cannot cancel until your time is completed. You will not be charged again unless you choose to sign up for a new subscription. If, for any reason, the website, email system, etc., does not work, we will add the allotted time to your package.</li>
         </ul>          
 <br />";
								$message .= "Support contact details: support@inspin.com<br><br>";
								$message .= "Thanks in advance and we look forward to a profitable relationship.<br><br>- Inspin.com<br><br>";		  
							
							}elseif( isset($_SESSION["chosen_whale_page"]) && !empty($_SESSION["chosen_whale_page"]) ){
							    $email_subject = $package_description;
								
								$message = "Thank you so much for purchasing an ".$package_description."<br><br>You are now fully active to receive all of our exclusive picks information.<br><br>Thanks in advance and we look forward to a profitable relationship.<br><br>- Inspin.com";
								
								$wpc = new _whale_package_x_customer();
								$wpc->vars["user_id"] = $final_user->vars["id"];
								$wpc->vars["whale_package_id"] = $_SESSION["chosen_whale_page"];
								$wpc->vars["payment_date"] = date("Y-m-d");							
								$wpc->vars["amount_paid"]  = $amount;
								$wpc->vars["active"] = 1;						
								$wpc->vars["payment_method"] = "cc";       
								$wpc->insert();
								   
							}
							
	                        send_email_ck_auth($final_user->vars["email"], $email_subject, $message, true);
												
							?>
							<script>
							window.open('/thanks/','_parent');
							</script>
                       <?	 
													
					   }else{ 
						   $error = 1; // 2—Declined or 3—Error or 4—Held for Review			   
						   $error_response = "Charge Credit Card ERROR :  Invalid response.";
					   }		
						
				}else{
						
					$error = 1;	
					$error_response = "Charge Credit Card ERROR : The transaction response is not valid or it did not came from Authorize.Net.";
						
				}
				
			}else{
		     $error = 1;	 
		     $error_response= "Charge Credit Card ERROR : The transaction response is null.";
		    }	
			
		}else{
		   $error = 1;	 
		   $error_response= "Charge Credit Card Null response returned.";
		}
		
   }else{
	   
	   $error = 1;	
	   $error_response = "Charge Credit Card ERROR : The chosen package is not valid.";   
	   	   
   }//Package exist 	
		
} else{
	$error = 1;	//Not a valid user session 
	$error_response = "Invalid Customer Session.";
}
?>   

<? if($error == 1){ ?>  
	  <form action="/error/" method="post" target="_top" id="py_error_btn">			
		 <input type="hidden" name="error_response" value="<? echo $error_response ?>">   
	  </form>
	  <script type="text/javascript">document.getElementById("py_error_btn").submit();</script>
<? } ?>