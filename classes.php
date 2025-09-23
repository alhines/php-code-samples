<?
//date_default_timezone_set('America/New_York');


class _user{
	var $vars = array();
	function initial(){}
	function update($specific = NULL){
	   db_connect("insider");
	   return update($this, "registered_users", $specific);
	}
	function update_site($specific = NULL,$site){
	   $websites = get_all_websites();			
	   db_connect($websites[$site]["db"]);
	   return update($this, "registered_users", $specific);
	}	
	function insert(){
		db_connect("insider");		
		$this->vars["id"] = insert($this, "registered_users");
	}	
	function delete(){
		db_connect("insider");
		return delete("registered_users",$this->vars["id"] );
	}
	function delete_logs(){
		db_connect("insider");
		return delete_logs_user($this->vars["id"]);
	}
	function delete_payments(){
		db_connect("insider");
		return delete_payments_user($this->vars["id"]);
	}
	function get_user_units(){
		db_connect("insider");
		return get_total_units_user($this->vars["id"]);
	}
	function get_user_payments(){
		db_connect("insider");
		return get_user_payments($this->vars["id"]);
	}
	function get_last_user_payment(){
		db_connect("insider");
		return get_latest_payment_date($this->vars["id"]);
    }	
	function get_user_whale_packages($active=1){
		db_connect("insider");
		return get_user_whale_packages($this->vars["id"],$active);
	}
	function verify_exist_customer_active_wp($wp_id){
		db_connect("insider");
		return verify_exist_customer_active_wp($this->vars["id"],$wp_id);
	}			
}

class _payment{
	var $vars = array();
	function initial(){}
	function insert(){
		db_connect("insider");
		$this->vars["id"] = insert($this, "payments");
	}
	function insert_site($site){
		$websites = get_all_websites();			
		db_connect($websites[$site]["db"]);
		$this->vars["id"] = insert($this, "payments");
	}		
}

class _pick{
	
	var $vars = array();
	function initial(){}
	function update($specific = NULL){
	   db_connect("insider");
	   return update($this, "picks", $specific);
	}
	function insert(){
		db_connect("insider");
		$this->vars["id"] = insert($this, "picks");
	}
	function delete(){
		db_connect("insider");
		return delete("picks",$this->vars["id"] );
	}
	function get_chosen_websites_picks($pick_id){	
		db_connect("insider");
		return get_chosen_websites_picks($this->vars["id"]);
	}
	function insert_chosen_website_pick($site_id){
		$res = new _chosen_website_pick();
		$res->vars["site_id"] = $site_id;
		$res->vars["inspin_pick_id"] = $this->vars["id"];
		$res->insert();		
		return $res;
	}
	function insert_parlay_details_by_pick($team_id,$price){
		$res = new _parlay_detail_by_pick();		
		$res->vars["pick_id"]  = $this->vars["id"];
		$res->vars["team_id"]  = $team_id;
		$res->vars["price"]  = $price;		
		$res->insert();		
		return $res;
	}
	function get_parlay_details_by_pick(){	
		db_connect("insider");
		return get_parlay_details_by_pick($this->vars["id"]);
	}	
}

class _parlay_detail_by_pick{
	var $vars = array();
	function initial(){}
	function update($specific = NULL){
	   db_connect("insider");
	   return update($this, "parlay_details_by_pick", $specific);
	}
	function insert(){
		db_connect("insider");
		$this->vars["id"] = insert($this, "parlay_details_by_pick");
	}
	function delete(){
		db_connect("insider");
		return delete("parlay_details_by_pick",$this->vars["id"] );
	}	
}

class _chosen_website_pick{
	var $vars = array();
	function initial(){}
	function update($specific = NULL){
	   db_connect("insider");
	   return update($this, "chosen_websites_picks", $specific);
	}
	function insert(){
		db_connect("insider");
		$this->vars["id"] = insert($this, "chosen_websites_picks");
	}
	function delete(){
		db_connect("insider");
		return delete("chosen_websites_picks",$this->vars["id"] );
	}	
}

class _team{
	var $vars = array();
	function initial(){}		
}

class _log_user{
	var $vars = array();
	function initial(){}	
	function insert(){
		db_connect("insider");
		return insert($this, "log_users");
	}		
}	

class _website_page{
	var $vars = array();
	function initial(){}		
}

//Tickets

class _tickets{
	var $vars = array();
	function initial(){}
	function update($specific = NULL){
	   db_connect("insider");
	   return update($this, "tickets", $specific);
	}
	function insert(){
		db_connect("insider");
		$this->vars["id"] = insert($this, "tickets");
	}
	function delete(){
		db_connect("insider");
		return delete("tickets",$this->vars["id"] );
	}	
}

class _response{
	var $vars = array();
	function initial(){}
	function update($specific = NULL){
	   db_connect("insider");
	   return update($this, "response", $specific);
	}
	function insert(){
		db_connect("insider");
		$this->vars["id"] = insert($this, "response");
	}
	function delete(){
		db_connect("insider");
		return delete("response",$this->vars["id"] );
	}	
}

//reviews

class _reviews{
	var $vars = array();
	function initial(){}
	function update($specific = NULL){
	   db_connect("insider");
	   return update($this, "reviews", $specific);
	}
	function insert(){
		db_connect("insider");
		$this->vars["id"] = insert($this, "reviews");
	}
	function delete(){
		db_connect("insider");
		return delete("reviews",$this->vars["id"] );
	}
	function delete_responses(){
		db_connect("insider");
		return delete_review_responses($this->vars["id"]);
	}	
}

class _reviews_responses{
	var $vars = array();
	function initial(){}
	function update($specific = NULL){
	   db_connect("insider");
	   return update($this, "reviews_responses", $specific);
	}
	function insert(){
		db_connect("insider");
		$this->vars["id"] = insert($this, "reviews_responses");
	}
	function delete(){
		db_connect("insider");
		return delete("reviews_responses",$this->vars["id"] );
	}		
}

class _chosen_result_streak_tool{
	var $vars = array();
	function initial(){}
	function update($specific = NULL){
	   db_connect("insider");
	   return update($this, "chosen_results_streak_tool", $specific);
	}
	function insert(){
		db_connect("insider");
		$this->vars["id"] = insert($this, "chosen_results_streak_tool");
	}
	function delete(){
		db_connect("insider");
		return delete("chosen_results_streak_tool",$this->vars["id"] );
	}
	function get_win_results(){
      return get_chosen_win_results($this->vars["id"]); 
	}	
}

class _chosen_streak_tool_win_result{
	var $vars = array();
	function initial(){}
	function update($specific = NULL){
	   db_connect("insider");
	   return update($this, "chosen_streak_tool_win_results", $specific);
	}
	function insert(){
		db_connect("insider");
		$this->vars["id"] = insert($this, "chosen_streak_tool_win_results");
	}
	function delete(){
		db_connect("insider");
		return delete("chosen_streak_tool_win_results",$this->vars["id"] );
	}	
}

//tickets customer profile

class _ticket{
	var $vars = array();
	function initial(){}
	function insert(){
	  db_connect("tickets");	
	   $this->vars["id"] = insert($this, "ticket");
	}
	function update($specific = NULL){
	  db_connect("tickets");	
	   return update($this, "ticket", $specific);
	}
	function insert_response($message, $from, $clerk = ""){
		$res = new _ticket_response();
		$res->vars["rdate"] = date("Y-m-d H:i:s");
		$res->vars["_by"] = $from;
		$res->vars["message"] = $message;
		$res->vars["ticket"] = $this->vars["id"];
		if($clerk!=""){$res->vars["clerk"] = $clerk;}
		$res->insert();
		$this->vars["pread"] = 0;
		
		$this->update(array("pread"));
		return $res;
	}
	static function sort_by_date($a, $b){
		return sort_object($a->vars["tdate"], $b->vars["tdate"],"DESC");
   }
	function is_me($name){
		if($this->vars["name"] == $name){
			$is = true;
		}else{$is = false;}
		return $is;
	}
	function get_password(){
		$key1 = mt_rand()."O";
		$key2 = "O".mt_rand();
		return biencript($key1.$this->vars["id"].$key2);
	}
	function str_status(){
		if($this->vars["open"]){
			$str = "Open";
		}else{
			$str = "Closed";
		}
		return $str;
	}
}


class _ticket_response{
	var $vars = array();
	function initial(){}
	function insert(){
	  db_connect("tickets");	
	   $this->vars["id"] = insert($this, "response");
	}
	function update($specific = NULL){
	   db_connect("tickets");	
	   return update($this, "response", $specific);
	}
}


/// Api Ticket Class
class _api_ticket{
	function send($type,$ticket){

	  switch($type)	{
	  	case 1: // Tickets 
	  	       $result = api_create_ticket($ticket);
	  	     
	  	       break;
        case 2: //Responses
              
               $result = api_create_response($ticket);
        	 break;

        default: break;
      }	

     return $result;
    }

  function total_responses($ticket_category){

	    $result = api_total_responses($ticket_category);
	  
     return $result;
  }

  function new_responses($ticket_category){

	   $result = api_new_responses($ticket_category);
	   return $result;
  }
  
  function update_response($response){
       $result = api_update_response($response);
       return $result;

  }  

  function update_ticket($ticket){
       $result = api_update_ticket($ticket);
       return $result;

  }

  function total_response_update($ticket_category){
       $result = api_total_responses_update($ticket_category);
       return $result;
  }

  function update_response_update($ticket_category){
       $result = api_new_update_responses($ticket_category);
       return $result;
  }

  function total_ticket_update($ticket_category){
     $result = api_total_ticket_update($ticket_category);
     return $result;
  }

  function update_total_update($ticket_category){
  	   $result = api_new_update_ticket($ticket_category);
       return $result;

  }

}

// TELEGRAM
class _Bot{
	private $btoken;private $website;
	private $data;private $update;
	private $nombre;private $apellido;
	private $chatID;private $chatType;
	private $message;

	function __construct($data){
		//$this->btoken="2058052960:AAE10ngZ6omInedeC7beGiWQSgYHLRESoOE";
		$this->btoken="2088478187:AAE0EHHM5v-Kq4SnYeJZEI2LFDZeXPb2whM";
		$this->website="https://api.telegram.org/bot".$this->btoken;
	}


//////////////////////////////////////////////////
//
//////////////////////////////////////////////////

public function explodeMensaje($msg){
	$dato=explode(" ", $msg);
	return $dato;
}


public function checkUser($entrada){
		$mensaje=$this->fileGetContents($entrada);
		$dato=$this->explodeMensaje($mensaje["message"]);
		
		$correo=$dato[1];
		$comando=strtolower($dato[0]);

		return array("email"=>$correo,"id_phone"=>$mensaje["chatID"]);

		
}

public function getMessage($entrada){
		$mensaje=$this->fileGetContents($entrada);
		$dato=$this->explodeMensaje($mensaje["message"]);
		unset($dato[0]);
    	$msj= implode(" ",$dato);
		return array("msj"=>$msj,"id_phone"=>$mensaje["chatID"]);

}


//////////////////////////////////////////////////
//
//////////////////////////////////////////////////

	public function envioMensaje($response,$fgc){
		$resultado=$this->fileGetContents($fgc);

		$params=['chat_id'=>$resultado["chatID"],'text'=>$response,];
		$ch = curl_init($this->website . '/sendMessage');
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, ($params));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		return $result = curl_exec($ch);
		curl_close($ch);
	}
	
	
	public function envioMensajeProcesos($chatid,$response){

		$params=['chat_id'=>$chatid,'text'=>$response,];
		$ch = curl_init($this->website . '/sendMessage');
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, ($params));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		return $result = curl_exec($ch);
		curl_close($ch);
	}

	public function broadcastMessage($response,$site=""){

		$phones = get_all_telegram_user_by_email($site);
		//print_r($phones);
		foreach ($phones as $user ){
			$chatid= $user['phone_id'];
			$telegram = new _Bot("");
			$result = json_decode($telegram->envioMensajeProcesos($chatid,$response),true);
			//print_r($result);
			if($result['ok']){
				$not = new _notifications();
				$not->save_not("bm",$user['email'],$response);

			}
		   
		} 

		
	}

//////////////////////////////////////////////////
//
//////////////////////////////////////////////////

	public function fileGetContents($fgc){
		$var=file_get_contents($fgc);
		$var=json_decode($var, TRUE);

		$nombre=$var['message']['chat']['first_name'];
		$apellidos=$var['message']['chat']['last_name'];
		$chatID=$var['message']['chat']['id'];
		$chatType=$var['message']['chat']['type'];
		$message=$var['message']['text'];
		return array("chatID"=>$chatID,"chatType"=>$chatType,"message"=>$message);
	}
}



class _telegram{
    var $vars = array();
    function _construct($pvars = array()){$this->vars = $pvars;}
	function initial(){}
	function update($specific = NULL){
	    db_connect("telegram");	
	   return update($this, "telegram", $specific);
	}
	function insert(){
	    db_connect("telegram");	
	   $this->vars["id"] = insert($this, "telegram");
	   return $this->vars["id"];
	}
	function delete(){
	    db_connect("telegram");	
	   delete("telegram", $this->vars["id"]);
	}
	
}

class _notifications{
    var $vars = array();
    function _construct($pvars = array()){$this->vars = $pvars;}
	function initial(){}
	function update($specific = NULL){
	    db_connect("telegram");	
	   return update($this, "notifications", $specific);
	}
	function insert(){
	    db_connect("telegram");	
	   $this->vars["id"] = insert($this, "notifications");
	   return $this->vars["id"];
	}
	function delete(){
	    db_connect("telegram");	
	   delete("notifications", $this->vars["id"]);
	}

   function save_not($type,$email,$msj){ // type t=  telegram pt= picks telegram pe = picks email

   	   $this->vars['email'] = $email;
   	   $this->vars['type'] = $type;
   	   $this->vars['message'] = $msj;
   	   $this->vars['date'] = date("Y-m-d H:i:s");
   	   $this->insert();


   }

	
}

class _newsletter{
	var $vars = array();
	function initial(){}
	function update($specific = NULL){
	   db_connect("insider");
	   return update($this, "newsletter", $specific);
	}
	function insert(){
		db_connect("insider");		
		$this->vars["id"] = insert($this, "newsletter");
	}
	function delete(){
		db_connect("insider");
		return delete("newsletter",$this->vars["id"] );
	}	
}

//teams handdicappers

class _teams_handicapper{
	var $vars = array();
	function initial(){}
	function update($specific = NULL){
	   db_connect("handicapper");
	   return update($this, "teams", $specific);
	}
	
}

//* Clases for Api SportsDataIO **//


class _SportsData_api{	

  var $queryString ;
  var $leagues = [];




  function initial(){

  	
                   
  }

  function get_sports_news($league){


  	  $this->leagues['NHL']['url'] = "https://api.sportsdata.io/v3/nhl/news-rotoballer/json/RotoBallerPremiumNews";
      $this->leagues['NHL']['key'] = "dd0a9fcd7309486a9ea35eb1b64aaf8e";
      $this->leagues['NFL']['url'] = "https://api.sportsdata.io/v3/nfl/news-rotoballer/json/RotoBallerPremiumNews";
      $this->leagues['NFL']['key'] = "6d589081344f4ea583b4a4cb352d76cc";
      $this->leagues['NBA']['url'] = "https://api.sportsdata.io/v3/nba/news-rotoballer/json/RotoBallerPremiumNews";
      $this->leagues['NBA']['key'] = "5f5b5b6b14fd4be5b916707449aa1665";
  	  $this->leagues['MLB']['url'] = "https://api.sportsdata.io/v3/mlb/news-rotoballer/json/RotoBallerPremiumNews";
  	  $this->leagues['MLB']['key'] = "b633b579d2d24a17876c13eff7ab10a5";

   $this->queryString = http_build_query([
    'key' => $this->leagues[$league]['key']
    ]);
  
   $ch = curl_init(sprintf('%s?%s', $this->leagues[$league]['url'] , $this->queryString));
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   $json = curl_exec($ch);
   curl_close($ch);
   $apiResult = json_decode($json, true);	
   return $apiResult;

  }
	
}

class _api_news{
	var $vars = array();
	function initial(){
		if($this->vars['id']){
		 $this->vars['Ago']	= get_time_ago( strtotime($this->vars['Updated']));
		 $this->vars['Team'] =  get_team_api_id($this->vars['TeamID'],$this->vars['league']);
		}
	}
	function update($specific = NULL){
	   db_connect("feeds");
	   return update($this, "news", $specific);
	}
	function insert(){
		db_connect("feeds");		
		$this->vars["id"] = insert($this, "news");
	}
	function delete(){
		db_connect("feeds");
		return delete("news",$this->vars["id"] );
	}	
}

//trends

class _trends{
	var $vars = array();
	function initial(){
		if($this->vars['id']){
		 
		 $this->vars['Team_away'] =  get_team_trends($this->vars['away'],$this->vars['league']);
		 $this->vars['Team_home'] =  get_team_trends($this->vars['home'],$this->vars['league']);
		}
	}
	function update($specific = NULL){
	   db_connect("feeds");
	   return update($this, "trends", $specific);
	}
	function insert(){
		db_connect("feeds");		
		$this->vars["id"] = insert($this, "trends");
	}
	function delete(){
		db_connect("feeds");
		return delete("trends",$this->vars["id"] );
	}	
}

class _leagues_streak_filter{
	var $vars = array();
	function initial(){}
	function update($specific = NULL){
	   db_connect("insider");
	   return update($this, "leagues_streak_filter", $specific);
	}
	function insert(){
		db_connect("insider");		
		$this->vars["id"] = insert($this, "leagues_streak_filter");
	}
	function delete(){
		db_connect("insider");
		return delete("leagues_streak_filter",$this->vars["id"] );
	}	
}

/*Super Bowl contest classes*/

class _contest_superbowl{
	
	var $vars = array();
	function initial(){}
	function update($specific = NULL){
	   db_connect("superbowl_contest");
	   return update($this, "contests", $specific);
	}
	function insert(){
	   db_connect("superbowl_contest");
	   $this->vars["id"] = insert($this, "contests");
	}		
	function picks($id_site=0){
      return get_picks_superbowl_contest($this->vars["id"],$id_site);
	}	
	function teams(){
      return get_teams_superbowl_contest($this->vars["id"]);
	}
	function winners($is_admin=0,$id_site=0){
      return get_winners_superbowl_contest($this->vars["id"],$is_admin,$id_site);
	}		
}

class _winner_superbowl_contest{
	
	var $vars = array();
	function initial(){}
	function update($specific = NULL){
	   db_connect("superbowl_contest");
	   return update($this, "winners", $specific);
	}
	function insert(){
		db_connect("superbowl_contest");
		$this->vars["id"] = insert($this, "winners");
	}		
}

class _team_superbowl_contest{
	
	var $vars = array();
	function initial(){}
	function update($specific = NULL){
	   db_connect("superbowl_contest");
	   return update($this, "teams", $specific);
	}
	function insert(){
		db_connect("superbowl_contest");
		$this->vars["id"] = insert($this, "teams");
	}			
}

class _pick_superbowl_contest{
	
	var $vars = array();
	function initial(){}
	function update($specific = NULL){
	   db_connect("superbowl_contest");
	   return update($this, "picks", $specific);
	}
	function insert(){
		db_connect("superbowl_contest");
		$this->vars["id"] = insert($this, "picks");
	}		
}

class _user_superbowl_contest{
	
	var $vars = array();
	function initial(){}
	function update($specific = NULL){
	   db_connect("superbowl_contest");
	   return update($this, "users", $specific);
	}
	function insert(){
		db_connect("superbowl_contest");
		$this->vars["id"] = insert($this, "users");
	}			
}
/*End Super Bowl contest classes*/

//braket
class _braket_player{
	var $vars = array();
	function initial(){}
	function insert(){
	   db_connect("braket");
	   $this->vars["id"] = insert($this, "brakets_count");
	}	
	function update($specific = NULL){
	   db_connect("braket");
	   return update($this, "brakets_count", $specific);
	} 
}
//End braket

//Whale packages

class _whale_package{
	
	var $vars = array();
	function initial(){}
	function update($specific = NULL){
	   db_connect("insider");
	   return update($this, "whale_packages", $specific);
	}
	function insert(){
		db_connect("insider");
		$this->vars["id"] = insert($this, "whale_packages");
	}			
}

class _whale_package_x_customer{
	var $vars = array();
	function initial(){}
	function update($specific = NULL){
	   db_connect("insider");
	   return update($this, "whale_packages_x_customer", $specific);
	}
	function insert(){
		db_connect("insider");
		$this->vars["id"] = insert($this, "whale_packages_x_customer");
	}
	function insert_site($site){
		$websites = get_all_websites();			
		db_connect($websites[$site]["db"]);
		$this->vars["id"] = insert($this, "whale_packages_x_customer");
	}
	function delete(){
		db_connect("insider");
		return delete("whale_packages_x_customer",$this->vars["id"] );
	}		
}
//End whale packages
?>