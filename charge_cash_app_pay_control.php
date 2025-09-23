<? 
session_start();

require_once("/home/gm1q5icx/domains/inspin.com/public_html/utilities/includes.php");
require_once("/home/gm1q5icx/domains/inspin.com/public_html/utilities/includes/square-php-sdk/autoload.php");

$error = false;

use Square\SquareClient;
use Square\Environment;
use Square\Exceptions\ApiException;
                
$client = new SquareClient([
    'accessToken' => $access_token,
    'environment' => Environment::PRODUCTION, //change to: PRODUCTION or SANDBOX 
]);

// buyer's cash app source_id:

$nonce = $_GET['token'];

$reguser = $_SESSION["reguser"];

$customer = get_user_obj($reguser);

$customer_email = $customer->vars["email"];
$package_id = $_SESSION["package-id"]; 

$exist_package = false;

foreach($pay_options as $popt){
	
	if($popt["encripted_package"] == $package_id){
		
		$exist_package = true;
		$data = explode("-",two_way($package_id,true));
        $package_description = "Inspin ".$data[1];
        $amount = $data[2]; 
		$final_amount = $amount * 100; //amount in cents       
        $package_description .= " subscription";
	}
}

$amount_money = new Square\Models\Money();
$amount_money->setAmount($final_amount); //payment amount in cents
$amount_money->setCurrency('USD');

$body = new Square\Models\CreatePaymentRequest($nonce, uniqid());
$body->setAmountMoney($amount_money);
$body->setAutocomplete(true);
$body->setBuyerEmailAddress($customer_email);
$body->setNote($package_description.", purchased by: ".$customer_email);

if( !empty($reguser) && isset($reguser) ){
	
	if($exist_package) {//Package exist
	  
	     try {
	
	          $api_response = $client->getPaymentsApi()->createPayment($body);
	  
	          if ($api_response->isSuccess()) {	  	   
							   
				   $days = $data[0];			  				
											 
				   $error = 0; 
										
				   if($customer->vars["expiration_date"] == "0000-00-00" || !$customer->vars["paid"]){
					  $start_date = date("Y-m-d");
				   } else{
					  $start_date = $customer->vars["expiration_date"];
				   }
								
				   $customer->vars["paid"] = 1;                            
				   $customer->vars["expiration_date"] = date("Y-m-d",strtotime($start_date." +$days days"));
				   $customer->update(array("paid","expiration_date"));
														
				   $payment = new _payment();
				   $payment->vars["user_id"]      = $customer->vars["id"];
				   $payment->vars["payment_date"] = date("Y-m-d");                            
				   $payment->vars["days_paid"]    = $days;
				   $payment->vars["amount_paid"]  = $amount;						
				   $payment->vars["payment_method"] = "cashapp";       
				   $payment->insert();										  
								  
				   send_email_ck_auth($customer->vars["email"], "Package Confirmation", $message, true);
				   send_email_ck_auth("andyh@inspin.com", "Package Confirmation - Cash App", $customer->vars["email"], true);
													
				  ?>
				   <script>window.open('/thanks/','_parent');</script>
				  <?
				  	 
			  }else{
				  
				  $errors = $api_response->getErrors();		
		          $error = true;
		
				  foreach ($errors as $error) {			
				     $error_detail = $error->getDetail();            
				  }
		
		          $error_response = "The payment was declined, Detail: ".$error_detail;					
		      }
	
		} catch (ApiException $e) {    
			 $error = true;
			 $error_response = "Error processing payment, Detail: ".$e->getMessage();
		}
		
   }else{
	   
	   $error = true;	
	   $error_response = "The chosen package is not valid.";   
	   	   
   }//Package exist 	
		
} else{
	
	$error = true;	//Not a valid user session 
	$error_response = "Invalid Customer's Session.";	
}
?>   

<? if($error){   
   send_email_ck_auth("andyh@inspin.com", "Error details - Cash App", $customer->vars["email"].": ".$error_response, true);
?>  
	  <form action="/error/" method="post" target="_top" id="py_error_btn">			
        <input type="hidden" name="error_response" value="<? echo $error_response ?>">    
      </form>    
      <script type="text/javascript">document.getElementById("py_error_btn").submit();</script>

<? } ?>
