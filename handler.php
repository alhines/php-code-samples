<?
require_once('/home/gm1q5icx/domains/inspin.com/public_html/utilities/db/connection.php');
require_once('/home/gm1q5icx/domains/inspin.com/public_html/utilities/db/manager.php');

function check_login($user, $pass){
	db_connect("insider");	
	$sql = "SELECT email as id FROM registered_users WHERE email = '$user' AND password = '$pass' AND active = 1";		
	return get_str($sql, true);
}
function user_email_available($email){
	db_connect("insider");
	$email = trim($email);
	$sql = "SELECT email FROM registered_users WHERE email LIKE '$email'";
	$res = get_str($sql, true);
	if(is_null($res)){return true;}else{return false;}
}
function verify_newsletter_subscription($email){
	db_connect("insider");
	$email = trim($email);
	$sql = "SELECT email FROM newsletter WHERE email LIKE '$email'";
	$res = get_str($sql, true);
	if(is_null($res)){return true;}else{return false;}
}
function get_user_email($email){
	db_connect("insider");
	$sql = "SELECT * FROM registered_users WHERE email LIKE '$email' limit 1";
	return get($sql, "_user", true);
}
function get_user_email_x_website($site,$email){
	
	$websites = get_all_websites();
   
    db_connect($websites[$site]["db"]);
	
	$sql = "SELECT * FROM registered_users WHERE email = '$email'";
	
	return get($sql, "_user", true);
}
function get_user_obj($id){
	db_connect("insider");
	$sql = "SELECT * FROM registered_users WHERE id = '$id'";
	return get($sql, "_user", true);
}
function get_all_users($paid = "",$limit=0, $num_results_x_page="",$send_method=0){
	db_connect("insider");
	
	if ($num_results_x_page != "") {	  	
	  $limit = " LIMIT $limit,$num_results_x_page";
    }	
    else {
	  $limit = "";	
    }
	
	if ($paid != "") {	  
	   $query_paid = " AND paid = '$paid' ";	
	}
	else {
	   $query_paid = "";
	}
	
	if ($send_method != 0) {
		
	   if ($send_method == 1) {		  
	      $query_send_method = " AND how_get_picks IN (0,1) ";
	   }else{
		  $query_send_method = " AND how_get_picks = '$send_method' "; 
	   }
	   
	}else {
	   $query_send_method = " AND how_get_picks IN (0,1,2,3) ";
	}	
	
	$sql = "SELECT * FROM registered_users WHERE active = 1 $query_paid $query_send_method ORDER BY paid DESC $limit";	
			
	return get($sql, "_user", false, "id");
}

function get_all_users_by_list($str_ids,$fields = ""){
	db_connect("insider");
	if($fields == "" ){ $fields = " * "; };
	$sql = "SELECT $fields FROM registered_users WHERE id IN ($str_ids)";	
	return get($sql, "_user");
}

function delete_logs_user($user_id){
	db_connect("insider");
	$sql = "DELETE FROM log_users WHERE user_id = $user_id";
	return execute($sql);
}

function delete_payments_user($user_id){
	db_connect("insider");
	$sql = "DELETE FROM payments WHERE user_id = $user_id";
	return execute($sql);
}

function delete_latest_user_manual_payment($user_id,$payment_method){
	db_connect("insider");
	
	$today = date("Y-m-d");
		
	$sql = "DELETE FROM payments WHERE payment_date >= '$today' and payment_method = '$payment_method' and user_id = $user_id";
	return execute($sql);
}

function get_all_picks($picks,$limit=0,$num_results_x_page=""){
	db_connect("insider");
	
	if ($picks != "") {
	  $query_picks = " graded = '$picks' ";	
	}
	else {
	  $query_picks = "1 ";
	}
	
	if ($num_results_x_page != "") {	  	
	  $limit = " LIMIT $limit,$num_results_x_page";
    }	
    else {
	  $limit = "";	
    }
		
	$sql = "SELECT * FROM picks WHERE $query_picks ORDER BY game_date DESC $limit";
			 
	return get_str($sql);
}
function get_pick($id){
	db_connect("insider");
	$sql = "SELECT * FROM picks WHERE id = '$id'";
	return get_str($sql, true);
}

function get_free_picks($id=""){
	db_connect("insider");
	
	$today  = date("Y-m-d");	
	$sunday = date("Y-m-d",strtotime('next sunday'));
	
	if ($id != "") {
	   $query = " id <> '$id' AND ";	
	}
	else {
	   $query = "1 AND ";
	}	
				
	$sql = "SELECT * FROM picks WHERE $query date(game_date) >= '$today' AND date(game_date) <= '$sunday' AND free_pick = 1";	
				 
	return get_str($sql);
}

function get_last_free_pick($id=""){
	db_connect("insider");
	
	$today  = date("Y-m-d");	
	$sunday = date("Y-m-d",strtotime('next sunday'));
	
	if ($id != "") {
	   $query = " id <> '$id' AND ";	
	}
	else {
	   $query = "1 AND ";
	}
	
	$sql = "SELECT * FROM picks WHERE $query date(game_date) >= '$today' AND free_pick = 1 ORDER BY ID DESC limit 1";		
				 
	return get_str($sql,true);
}

function get_teams_league($league){
	db_connect("handicapper");
	$sql = "SELECT DISTINCT(teamnamefirst), teamnamenick, teamid FROM teams WHERE sportsubtype = '$league' ORDER BY teamnamefirst";
	return get_str($sql);
}

function get_teams_league_indexed_by_teamid($league){
	db_connect("handicapper");
	$sql = "SELECT DISTINCT(teamnamefirst), teamnamenick, teamid FROM teams WHERE sportsubtype = '$league' ORDER BY teamnamefirst";
	return get_str($sql,false,"teamid");
}

function get_teams_league_api($league){
	db_connect("handicapper");
	$sql = "SELECT DISTINCT(teamnamefirst), teamnamenick,teamnameshort,sportsDataID, teamid FROM teams WHERE  sportsDataID > 0  AND sportsubtype = '$league' ORDER BY teamnamefirst";
	return get_str($sql);
}

function get_team_api_id($team_api_id,$league){
	db_connect("handicapper");
	$sql = "SELECT DISTINCT(teamnamefirst), teamnamenick, teamid,small_logo FROM teams WHERE sportsubtype = '$league' AND sportsDataID = $team_api_id ";
	//echo $sql."<BR>";
	return get_str($sql,true);
}

function get_team_trends($team,$league){
	db_connect("handicapper");
	//$sql = "SELECT DISTINCT(teamnamefirst), teamnamenick, teamid,small_logo FROM teams WHERE sportsubtype = '$league' AND teamwebname = '$team' ";
	$sql = "SELECT DISTINCT(teamnamefirst), teamnamenick, teamid,small_logo FROM teams WHERE sportsubtype = '$league' AND teamname = '$team' ";
	//Secho $sql."<BR>";
	return get_str($sql,true);
}

function get_teams_league_missing_apiID($league){
	db_connect("handicapper");
	$sql = "SELECT id,teamnameshort,sportsDataID FROM teams WHERE sportsubtype = '$league' And 	sportsDataID = 0";
	return get($sql,"_teams_handicapper",false,"teamnameshort");
}


function get_team($teamid){
	db_connect("handicapper");
	$sql = "SELECT DISTINCT(teamnamefirst), teamnamenick, teamid, small_logo, big_logo FROM teams WHERE teamid = '$teamid'";
	return get_str($sql, true);
}


function get_teams_to_trend($league){
	db_connect("handicapper");
	$sql = "SELECT id,teamname,teamnamefirst,teamwebname,teamnamenick FROM teams WHERE sportsubtype = '$league' and teamwebname = '' and small_logo != ''   ";
		
	return get($sql,"_teams_handicapper", false,"teamnamenick");
}



function get_total_units_user($id){
	db_connect("insider");	
	
	/*$sql = "SELECT ROUND(SUM(game_result), 1) as total_units_user
            FROM picks
            WHERE game_result IS NOT NULL AND
            date(game_date) >= ( SELECT date_format(max(payment_date), '%Y-%m-%d')
                                FROM  payments
							    WHERE user_id = '$id'
						       )";*/
							   
	$sql = "SELECT 
            CASE 
              WHEN SUM(game_result) > 0 THEN CONCAT('+', ROUND(SUM(game_result), 1))
              WHEN SUM(game_result) < 0 THEN ROUND(SUM(game_result), 1)
              ELSE '0'
            END AS total_units_user
            FROM picks
            WHERE game_result IS NOT NULL
            AND date(game_date) >= ( SELECT DATE_FORMAT(MAX(payment_date), '%Y-%m-%d')
                                     FROM payments
                                     WHERE user_id = '$id'
                                   )";	
	return get_str($sql, true);
}
function get_picks_units_user($id){
	db_connect("insider");	
	
	$sql = "SELECT *
            FROM picks
            WHERE game_result IS NOT NULL AND
                  date(game_date) >= ( SELECT date_format(max(payment_date), '%Y-%m-%d')
                                       FROM  payments
						               WHERE user_id = '$id'
						              )
			ORDER BY game_date DESC";			
	
	return get_str($sql);
}
function get_latest_payment_date($id){
	db_connect("insider");	
	
	$sql = "SELECT date_format(max(payment_date), '%Y-%m-%d') as last_payment_date
            FROM  payments
			WHERE user_id = '$id'";
	
	return get_str($sql, true);
}
function get_latest_payment_customer($id){
	db_connect("insider");	
	
	$sql = "SELECT *
            FROM  payments
			WHERE user_id = '$id' order by id desc limit 1";
	
	return get_str($sql, true);
}
function get_chosen_website_pick($id){
	db_connect("insider");
	$sql = "SELECT * FROM chosen_websites_picks WHERE id = '$id'";
	return get($sql, "_chosen_website_pick" ,true);
}
function get_chosen_websites_picks($pick_id){
	db_connect("insider");
	$sql = "SELECT * FROM chosen_websites_picks  WHERE inspin_pick_id = '$pick_id'";
	return get($sql, "_chosen_website_pick" ,false, "site_id");
}
function get_parlay_detail($id){
	db_connect("insider");
	$sql = "SELECT * FROM parlay_details_by_pick WHERE id = '$id'";
	return get($sql, "_parlay_detail_by_pick" ,true);
}
function get_parlay_details_by_pick($pick_id){
	db_connect("insider");
	$sql = "SELECT * FROM parlay_details_by_pick  WHERE pick_id = '$pick_id' ORDER BY id ASC";	
	return get($sql, "_parlay_detail_by_pick" ,false);
}
function delete_parlay_details_by_pick($pick_id){
	db_connect("insider");
	$sql = "DELETE FROM parlay_details_by_pick
            WHERE pick_id = '$pick_id'";
    return execute($sql);
}
function delete_chosen_website_pick($site_id, $inspin_pick_id){
	db_connect("insider");
	$sql = "DELETE FROM chosen_websites_picks
            WHERE site_id = $site_id AND
			      inspin_pick_id = $inspin_pick_id";
    return execute($sql);
}
function get_user_payments($id){
	db_connect("insider");	
	
	$sql = "SELECT *
            FROM payments
            WHERE user_id = '$id'
			ORDER BY payment_date DESC";
	
	return get_str($sql);
}
function get_all_paids(){
	db_connect("insider");	
	
	$sql= "SELECT DISTINCT user_id FROM payments";
	
	return get_str($sql,false,"user_id");
}

//tickets

function get_all_email_tickets($email,$pending = false){
	db_connect("insider");
	if($pending){ $sql_pending = " AND pending_answer = 1 "; }
	$sql = "SELECT * FROM tickets where email = '".$email."' $sql_pending order by tdate DESC";
	return get($sql, "_tickets");
}

function get_email_ticket($tid){
	db_connect("insider");
	$sql = "SELECT * FROM tickets Where id = $tid";
	return get($sql, "_tickets",true);
}

function get_all_email_tickets_responses($tid){
	db_connect("insider");
	$sql = "SELECT * FROM response Where tid = $tid";
	return get($sql, "_response");
}

function get_email_ticket_last_response($tid){
	db_connect("insider");
	$sql = "SELECT * FROM response Where tid = $tid order by id desc limit 1";
	return get($sql, "_response",true);
}

//Reviews

function get_all_email_reviews($email,$pending = false){
	db_connect("insider");
	if($pending){ $sql_pending = " AND pending_answer = 1 "; }
	$sql = "SELECT * FROM reviews Where email = '".$email."' $sql_pending  order by pending_answer DESC";
	
	return get($sql, "_reviews");
}

function get_email_review($tid){
	db_connect("insider");
	$sql = "SELECT * FROM reviews Where id = $tid";
	return get($sql, "_reviews",true);
}

function get_all_email_reviews_responses($tid){
	db_connect("insider");
	$sql = "SELECT * FROM reviews_responses Where rid = $tid and message != ''";
	return get($sql, "_reviews_responses");
}

function get_email_review_last_response($tid){
	db_connect("insider");
	$sql = "SELECT * FROM reviews_responses Where tid = $tid order by id desc limit 1";
	return get($sql, "_reviews_responses",true);
}

function get_approved_reviews($field = "*",$website = "",$rank = "",$manual = "",$limit=0, $num_results_x_page=""){
  db_connect("insider");
  
  if ($num_results_x_page != "") {	  	
	  $limit = " LIMIT $limit,$num_results_x_page";
  }	
  else {
	  $limit = "";	
  }
  
  if($field != "*"){ $sql_field = " $field "; }  
  if($manual != ""){ $sql_manual = " AND manual = '".$manual."' "; } 
  if($website != ""){ $sql_website = " AND website_page = '".$website."' "; }  
  if($rank != ""){ $sql_rank = " AND rank >= '".$rank."' "; }  
  $sql = "SELECT $field FROM reviews Where approved = 1 $sql_manual $sql_website $sql_rank ORDER BY rdate DESC $limit";
       
  return get($sql, "_reviews");	
}

function delete_review_responses($rid){
	db_connect("insider");
	$sql = "DELETE FROM reviews_responses WHERE rid = $rid";
	return execute($sql);
}

function get_all_website_pages(){
	db_connect("insider");
	$sql = "SELECT * FROM website_pages where id not in (1,2,8) order by id asc";
	return get_str($sql);
}

function get_all_main_website_pages(){
	db_connect("insider");
	$sql = "SELECT * FROM website_pages where id in (1,2,8,9,10,11) order by id asc";
	return get_str($sql);
}

function get_website_page($id){
	db_connect("insider");
	$sql = "SELECT * FROM website_pages WHERE id = '$id'";
	return get_str($sql, true);
}

function get_website_page_by_prefix($prefix){
	db_connect("insider");
	$sql = "SELECT * FROM website_pages WHERE prefix = '$prefix'";
	return get_str($sql, true);
}

//newsletter
function get_newsletter_subscribers($field = "*",$limit=0, $num_results_x_page=""){
  db_connect("insider");
  
  if ($num_results_x_page != "") {	  	
	  $limit = " LIMIT $limit,$num_results_x_page";
  }	
  else {
	  $limit = "";	
  }
  
  if($field != "*"){ $sql_field = " $field "; }    
  $sql = "SELECT $field FROM newsletter ORDER BY email DESC $limit";
       
  return get($sql, "_newsletter");	
}
//Streak tool

function get_all_picks_st($sport="",$filter=0){
	db_connect("insider");
	
	$sql_last_days = "";
	$sql_star_plays = "";
	$limit = "";
			
	if($sport != ""){ 
	   $select_sport = "SELECT sport, count(*) as Total,";
	   $sql_sport = " AND sport = '".$sport."' ";
	   $group_by = "";
	   $order_by = "";	  
    }else{
	   $select_sport = "SELECT a.sport, count(*) as Total,";
	   $sql_sport = " AND a.sport = sport";	  
	   $group_by = "group by sport";
	   $order_by = "order by sport";	   	  	
	}
	
	switch ($filter) {
    case 1: //Last 10 picks        
		$limit = "limit 0, 10";		
        break;
		
    case 2: //Last 25 picks        
		$limit = "limit 0, 25";		 
        break;
		
    case 3: //Last 14 Days	    
	    $sql_last_days = " AND date(game_date) >= ( CURDATE() - INTERVAL 14 DAY ) AND ( date(game_date) <= CURDATE() )";            
        break;
		
	case 4: //Last 30 Days	   
	    $sql_last_days = " AND date(game_date) >= ( CURDATE() - INTERVAL 30 DAY ) AND ( date(game_date) <= CURDATE() )";            
        break;
		
	case 5: //Last 60 Days
	    $sql_last_days = " AND date(game_date) >= ( CURDATE() - INTERVAL 60 DAY ) AND ( date(game_date) <= CURDATE() )";             
        break;
		
	case 6: //Last 90 Days	    
	    $sql_last_days = " AND date(game_date) >= ( CURDATE() - INTERVAL 90 DAY ) AND ( date(game_date) <= CURDATE() )";              
        break;
		
	case 7: //1 star plays
	    $sql_star_plays = " AND number_stars = 1 ";	                 
        break;
	
	case 8: //2 stars plays
	    $sql_star_plays = " AND number_stars = 2 ";	                 
        break;
	
	case 9: //3 stars plays
	    $sql_star_plays = " AND number_stars = 3 ";	                
        break;
		
	case 10: //4 stars plays
	    $sql_star_plays = " AND number_stars = 4 ";	                
        break;
		
	case 11: //5 stars plays
	    $sql_star_plays = " AND number_stars = 5 ";	                
        break;
		
	case 12: //6 stars plays
	    $sql_star_plays = " AND number_stars = 6 ";	                
        break;
		
	case 13: //whale package
	    $sql_whale_package = " AND whale_package = 1 ";	                
        break;						
}	
					
	if($filter == 1 or $filter == 2){
		
		$union = " UNION ";
		
		if($sport != ""){		
		    $sql = "Select * from picks WHERE grading_result IN ('WIN','LOSS') and graded = 1 AND sport = '$sport'         order by date(game_date) DESC $limit";
		}else{			
			$le_list = get_leagues(); 
			$count_le_list = count($le_list);
			$count = 0;
			foreach($le_list as $le){$count++;
				
				if($count == $count_le_list){
					$union = "";
				}
						
				$sql .= "(Select * from picks WHERE grading_result IN ('WIN','LOSS') and graded = 1 AND sport = '".$le["league"]."' order by sport, date(game_date) DESC $limit) $union ";
			}
		}
				
	}else{	
	
		$sql = $select_sport." 
		(select count(*) FROM picks WHERE grading_result = 'WIN' and graded = 1 $sql_sport $sql_star_plays $sql_whale_package $sql_last_days ) as Total_Win,
		(select count(*) FROM picks WHERE grading_result = 'LOSS' and graded = 1 $sql_sport $sql_star_plays $sql_whale_package $sql_last_days ) as Total_Loss, (select ROUND(SUM(game_result),2) FROM picks WHERE grading_result IN ('WIN','LOSS') and graded = 1 $sql_sport $sql_star_plays $sql_whale_package $sql_last_days ) as Total_Result 
		FROM picks as a
		WHERE grading_result IN ('WIN','LOSS') and 
			  graded = 1 $sql_star_plays $sql_whale_package $sql_last_days $sql_sport
		$group_by
		$order_by";	
	}
	
	//echo $sql;
			 
	return get($sql, "_pick");
}

function get_pick_st($id){
	db_connect("insider");
	$sql = "SELECT * FROM picks WHERE id = '$id'";
	return get($sql, "_pick" ,true);
}

function get_non_graded_picks_sport($sport){
	db_connect("insider");
	
	date_default_timezone_set('America/New_York'); 
	
	$today = date("Y-m-d");
	
	$sql = "SELECT * FROM picks WHERE sport = '$sport' and graded = 0 and date(game_date) >= '$today' ORDER BY id DESC";
	
	return get($sql, "_pick" ,false);
}


function get_chosen_results(){
	db_connect("insider");
	
	$sql = "SELECT a.id, a.sport, a.id_filter, a.id_record, a.total, a.wins, a.losses, a.win_perc, a.total_result
	        FROM chosen_results_streak_tool as a, leagues as b 
            WHERE a.sport = b.league 
            ORDER BY a.sport ASC, a.id_filter ASC";
	return get($sql, "_chosen_result_streak_tool");
}

function get_chosen_results_by_filters($sport,$id_filter,$id_record){
	db_connect("insider");
	$sql = "SELECT * 
	        FROM chosen_results_streak_tool
	        WHERE  sport = '$sport' AND
			       id_filter = '$id_filter' AND
				   id_record = '$id_record'
	        LIMIT 1";
	return get($sql, "_chosen_result_streak_tool", true);
}

function get_chosen_result($id){
	db_connect("insider");
	$sql = "SELECT * FROM chosen_results_streak_tool WHERE id = '$id'";
	return get($sql, "_chosen_result_streak_tool" ,true);
}

function get_chosen_win_results($id_chosen_result){
	db_connect("insider");
	$sql = "SELECT * FROM chosen_streak_tool_win_results WHERE id_chosen_result = '$id_chosen_result'";
	return get($sql, "_chosen_streak_tool_win_result");
}

function get_all_chosen_win_results(){
	db_connect("insider");
	$sql = "SELECT * FROM chosen_streak_tool_win_results";
	return get($sql, "_chosen_streak_tool_win_result");
}

function get_chosen_win_result($id){
	db_connect("insider");
	$sql = "SELECT * FROM chosen_streak_tool_win_results WHERE id = '$id'";
	return get($sql, "_chosen_streak_tool_win_result" ,true);
}

function delete_chosen_results($sport,$id_filter,$id_record){
	db_connect("insider");
	$sql = "DELETE FROM chosen_results_streak_tool
	        WHERE sport = '$sport' AND
			      id_filter = '$id_filter' AND
				  id_record = '$id_record'";
	return execute($sql);
}

function delete_win_results($id_chosen_result){
	db_connect("insider");
	$sql = "DELETE FROM chosen_streak_tool_win_results
	        WHERE  id_chosen_result = '$id_chosen_result' OR id_chosen_result = 0 OR win_result = ''";
				
	return execute($sql);
}

function get_leagues(){
	db_connect("insider");
	$sql = "SELECT * FROM leagues order by id ASC";
	return get_str($sql, false);
}

function get_league($id){
	db_connect("insider");
	$sql = "SELECT * FROM leagues WHERE id = $id";
	return get_str($sql, true);
}

function get_streak_tool_filters(){
	db_connect("insider");	
	$sql = "SELECT * FROM streak_tool_filters order by id ASC";	
	return get_str($sql, false,"id");
}

function get_streak_tool_filter($id){
	db_connect("insider");
	$sql = "SELECT * FROM streak_tool_filters WHERE id = $id";
	return get_str($sql, true);
}

function get_league_filter($league,$filter,$type = 1){
    db_connect("insider");
	$sql = "Select * FROM leagues_streak_filter WHERE league = '$league' AND filter = '$filter' AND type = $type";
	return  get($sql,'_leagues_streak_filter', true);
}
function get_all_league_filters($type=1){
    db_connect("insider");
	$sql = "Select CONCAT_WS('_',league,filter) as control FROM leagues_streak_filter WHERE type = $type";
	
	//echo $sql;
		
	return  get_str($sql, false,"control");
}
function get_all_active_filters($league="",$type = 1){
 db_connect("insider");
 if($league != ""){
 	$sql_league = " AND l.league = '$league' ";
 }
 $sql = "SELECT DISTINCT s.id,s.description FROM leagues_streak_filter l, streak_tool_filters s WHERE s.id = l.filter AND type = $type sql_league";
 // echo $sql."<BR>";
  return  get_str($sql, false);
}

//End streak tool functions

//tickets customer profile

function get_ticket($id){
	 db_connect("tickets");	
	$sql = "SELECT * FROM ticket WHERE id = '$id'";
	return get($sql, "_ticket", true);
}


function get_ticket_external($id){
	 db_connect("tickets");	
	$sql = "SELECT * FROM ticket WHERE id_external = '$id'";
	return get($sql, "_ticket", true);
}



function search_tickets($from, $to, $open, $email,  $keyword = "", $acc="",$site="",$count=false,$display=0,$index=0){
	
	db_connect("tickets");	
	
	if($site != ""){
		$sql_site = " AND website = '$site' ";
	}
	
	if($acc != ""){
		$sql_acc = " AND email = '$acc' ";
	}
	
	
	
	if($from != ""){$sql_from = " AND DATE(tdate) >= DATE('$from') ";}
	if($to != ""){$sql_to = " AND DATE(tdate) <= DATE('$to') ";}
	if($open != ""){$sql_open = " AND open = '$open' ";}
	if($keyword != ""){$sql_keyword = " AND ( message LIKE '%$keyword%' ||  subject LIKE '%$keyword%' )";}
	
	if($display>0){
		$sql_limit = " LIMIT $index, $display";
	}
	
	if($count){
		$sql_select = "COUNT(*) as total";
	}else{
		$sql_select = "*";
	}
	
	$sql = "SELECT $sql_select FROM ticket WHERE email LIKE '%$email%' 
	$sql_from $sql_to $sql_open   $sql_keyword  
	AND deleted = 0  $sql_site  $sql_acc ORDER BY id DESC
	$sql_limit";	
					
	if($count){$res = get_str($sql, true);}else{$res = get($sql, "_ticket");}
	// echo $sql."<BR>";
	return $res;
}

function get_ticket_response($rid){
    db_connect("tickets");	
	$sql = "SELECT * FROM response WHERE id = '$rid'";
	return get($sql, "_ticket_response",true);
}


function get_ticket_responses($tid){
	db_connect("tickets");	
	$sql = "SELECT * FROM response WHERE ticket = '$tid' ORDER BY rdate ASC";
	return get($sql, "_ticket_response");
}
function get_ticket_last_response($tid){
	db_connect("tickets");	
	$sql = "SELECT * FROM response WHERE ticket = '$tid' ORDER BY id DESC LIMIT 0,1";
	return get($sql, "_ticket_response", true);
}

function get_tickets_by_customer($email,$website){
	db_connect("tickets");
	$sql = "SELECT * FROM ticket WHERE email LIKE '$email' AND website = '$website' AND deleted = 0 ORDER BY id DESC";
	//echo $sql;
	return get($sql, "_ticket");
}

function count_unread_tickets_by_customer($email,$website){
	db_connect("tickets");
	$sql = "SELECT count(*) as num FROM ticket WHERE email LIKE '$email' AND website = '$website' AND pread = 0 AND deleted = 0";	
	return get_str($sql,true);
}

function get_all_customer_by_site($site){
  
    $website = get_all_websites();
    
    db_connect($website[$site]["db"]);	

	$sql = "SELECT  id, CONCAT_WS(' ',first_name,last_name) as 'customer',email,phone  FROM `registered_users` WHERE 1 order by first_name ASC";
	return get_str($sql);
	
}

function get_all_customers_by_site($site){
  
    $website = get_all_websites();
    
    db_connect($website[$site]["db"]);	

	$sql = "SELECT  id, CONCAT_WS(' ',first_name,last_name) as 'customer',email,phone  FROM `registered_users` WHERE 1 order by first_name ASC";
	return get_str($sql,false,"id");
	
}


function get_customer_by_site($site,$cid){
  
    $website = get_all_websites();
   
    db_connect($website[$site]["db"]);	
   
	$sql = "SELECT * FROM `registered_users` WHERE id = $cid";
	
	return get_str($sql,true);
	
}

function get_customer_by_site_x_email($site,$email){	
  
    $website = get_all_websites();
   
    db_connect($website[$site]["db"]);	
   
	$sql = "SELECT * FROM registered_users WHERE email = '$email'";
		
	return get_str($sql,true);	
}

function get_all_websites(){
  
   db_connect("tickets");

   $sql = "SELECT * FROM websites ORDER BY id";
   return get_str($sql,false,"id");
	
}

function get_website($id){
	  
   db_connect("tickets");

   $sql = "SELECT * FROM websites where id = '$id'";
   return get_str($sql,true);	
}

//
function get_tickets_to_sync($new = false){
	db_connect("tickets");
	
	if($new){
		$sql_new = " AND id_external = 0 ";
	}else{
		$sql_new = "";
	}
	
	$sql = "SELECT * FROM ticket WHERE updated = 0 $sql_new";	
	
	return  get($sql, "_ticket");
}

function get_ticket_responses_to_sync($new = false){
	db_connect("tickets");	
	if($new){
		$sql_new = " AND id_external = 0 ";
	}else{
		$sql_new = "";
	}
	$sql = "SELECT * FROM response WHERE updated = 0 $sql_new ORDER BY id ASC";
	
	return get($sql, "_ticket_response");
}


// TELEGRAM ///

function get_telegram_user_by_email($site,$email){
	db_connect("telegram");
	$sql = "SELECT * FROM telegram WHERE website = $site AND email = '".$email."'";	
	
	return  get($sql, "_telegram",true);
}


function get_all_telegram_user_by_email($website){
	db_connect("telegram");
	if($website){ $sql_website = " AND website = $website " ; }
	$sql = "SELECT email,phone_id  FROM telegram WHERE 1 $sql_website";	
	
	return  get_str($sql,false,'email');
}


function get_phone_id_by_website($site,$phone_id){
	db_connect("telegram");
	$sql = "SELECT * FROM telegram WHERE website = $site AND phone_id = '".$phone_id."'";	
	
	return  get($sql, "_telegram",true);
}


function get_telegram_users_by_email($email){
	db_connect("telegram");
	$sql = "SELECT * FROM telegram WHERE email = '".$email."'";

	return  get($sql, "_telegram");
}


function get_phone_id_to_send($website,$free,$reg,$wp,$wp_id){
	db_connect("telegram");
       
	if($website){ $sql_website = " AND website = $website "; }

	if($free == 'true' && $reg == 'false' && $wp == 'false'){ //option 1
		
	  $sql = "SELECT DISTINCT phone_id, email FROM telegram WHERE 1 $sql_website";
      $data =  get_str($sql);
    } //opt1 

    if($free == 'false' && $reg == 'true' && $wp == 'false'){  //opt 2
     switch ($website) {
      	case '1':
      		db_connect("insider");
	  		$sql = "SELECT   email  from  registered_users where paid = 1  ";
      		$data =  get_str($sql,false,'email');
      		break;
        case '2':
      		db_connect("handicapper");
	  		$sql = "SELECT   email  from  registered_users where paid = 1  ";
      		$data =  get_str($sql,false,'email');
      		break;	
      	case '3':
      		db_connect("SBH");
	  		$sql = "SELECT   email  from  registered_users where paid = 1  ";
      		$data =  get_str($sql,false,'email');
      		break;	

      	case '4':
      		db_connect("HAN911");
	  		$sql = "SELECT   email  from  registered_users where paid = 1  ";
      		$data =  get_str($sql,false,'email');
      		break;	
        case '5':
      		db_connect("SQPICKS");
	  		$sql = "SELECT   email  from  registered_users where paid = 1  ";
      		$data =  get_str($sql,false,'email');
      		break;	
      	case '6':
      		db_connect("scratchcaddy");
	  		$sql = "SELECT   email  from  registered_users where paid = 1  ";
      		$data =  get_str($sql,false,'email');
      		break;
		case '7':
      		db_connect("HC");
	  		$sql = "SELECT   email  from  registered_users where paid = 1  ";
      		$data =  get_str($sql,false,'email');
      		break;	
		case '8':
      		db_connect("BOFF");
	  		$sql = "SELECT   email  from  registered_users where paid = 1  ";
      		$data =  get_str($sql,false,'email');
      		break;			

         case '0':	
           
            db_connect("insider");
	  		$sql = "SELECT   email  from  registered_users where paid = 1  ";
      		$data1 =  get_str($sql,false,'email');
            
      		db_connect("handicapper");
	  		$sql = "SELECT   email  from  registered_users where paid = 1  ";
      		$data2 =  get_str($sql,false,'email');
      		

      		db_connect("SBH");
	  		$sql = "SELECT   email  from  registered_users where paid = 1  ";
      		$data3 =  get_str($sql,false,'email');
      		
      		db_connect("HAN911");
	  		$sql = "SELECT   email  from  registered_users where paid = 1  ";
      		$data4 =  get_str($sql,false,'email');

      		
      		db_connect("SQPICKS");
	  		$sql = "SELECT   email  from  registered_users where paid = 1  ";
      		$data5 =  get_str($sql,false,'email');

      		
      		db_connect("scratchcaddy");
	  		$sql = "SELECT   email  from  registered_users where paid = 1  ";
      		$data6 =  get_str($sql,false,'email');
			
			db_connect("HC");
	  		$sql = "SELECT   email  from  registered_users where paid = 1  ";
      		$data7 =  get_str($sql,false,'email');
			
			db_connect("BOFF");
	  		$sql = "SELECT   email  from  registered_users where paid = 1  ";
      		$data8 =  get_str($sql,false,'email');
      		

     		$data = array_merge($data1,$data2,$data3,$data4,$data5,$data6,$data7,$data8);
      		break;		
      	     default:break;
      } //switch

     
    } 	//opt 2	
	
	if($free == 'false' && $reg == 'false' && $wp == 'true'){  //opt 3
     switch ($website) {
      	case '1':
      		db_connect("insider");
	  		$sql = "SELECT email from registered_users as a, whale_packages_x_customer as b where a.id = b.user_id AND b.active = 1 AND b.whale_package_id = '$wp_id'  ";
      		$data =  get_str($sql,false,'email');
      		break;
        case '2':
      		db_connect("handicapper");
	  		$sql = "SELECT email from registered_users as a, whale_packages_x_customer as b where a.id = b.user_id AND b.active = 1 AND b.whale_package_id = '$wp_id'  ";
      		$data =  get_str($sql,false,'email');
      		break;	
      	case '3':
      		db_connect("SBH");
	  		$sql = "SELECT email from registered_users as a, whale_packages_x_customer as b where a.id = b.user_id AND b.active = 1 AND b.whale_package_id = '$wp_id'  ";
      		$data =  get_str($sql,false,'email');
      		break;	

      	case '4':
      		db_connect("HAN911");
	  		$sql = "SELECT email from registered_users as a, whale_packages_x_customer as b where a.id = b.user_id AND b.active = 1 AND b.whale_package_id = '$wp_id'  ";
      		$data =  get_str($sql,false,'email');
      		break;	
        case '5':
      		db_connect("SQPICKS");
	  		$sql = "SELECT email from registered_users as a, whale_packages_x_customer as b where a.id = b.user_id AND b.active = 1 AND b.whale_package_id = '$wp_id'  ";
      		$data =  get_str($sql,false,'email');
      		break;	
      	case '6':
      		db_connect("scratchcaddy");
	  		$sql = "SELECT email from registered_users as a, whale_packages_x_customer as b where a.id = b.user_id AND b.active = 1 AND b.whale_package_id = '$wp_id'  ";
      		$data =  get_str($sql,false,'email');
      		break;
		case '7':
      		db_connect("HC");
	  		$sql = "SELECT email from registered_users as a, whale_packages_x_customer as b where a.id = b.user_id AND b.active = 1 AND b.whale_package_id = '$wp_id'  ";
      		$data =  get_str($sql,false,'email');
      		break;	
		case '8':
      		db_connect("BOFF");
	  		$sql = "SELECT email from registered_users as a, whale_packages_x_customer as b where a.id = b.user_id AND b.active = 1 AND b.whale_package_id = '$wp_id'  ";
      		$data =  get_str($sql,false,'email');
      		break;			

         case '0':	
           
            db_connect("insider");
	  		$sql = "SELECT email from registered_users as a, whale_packages_x_customer as b where a.id = b.user_id AND b.active = 1 AND b.whale_package_id = '$wp_id'  ";
      		$data1 =  get_str($sql,false,'email');
            
      		db_connect("handicapper");
	  		$sql = "SELECT email from registered_users as a, whale_packages_x_customer as b where a.id = b.user_id AND b.active = 1 AND b.whale_package_id = '$wp_id'  ";
      		$data2 =  get_str($sql,false,'email');      		

      		db_connect("SBH");
	  		$sql = "SELECT email from registered_users as a, whale_packages_x_customer as b where a.id = b.user_id AND b.active = 1 AND b.whale_package_id = '$wp_id'  ";
      		$data3 =  get_str($sql,false,'email');
      		
      		db_connect("HAN911");
	  		$sql = "SELECT email from registered_users as a, whale_packages_x_customer as b where a.id = b.user_id AND b.active = 1 AND b.whale_package_id = '$wp_id'  ";
      		$data4 =  get_str($sql,false,'email');
      		
      		db_connect("SQPICKS");
	  		$sql = "SELECT email from registered_users as a, whale_packages_x_customer as b where a.id = b.user_id AND b.active = 1 AND b.whale_package_id = '$wp_id'  ";
      		$data5 =  get_str($sql,false,'email');

      		
      		db_connect("scratchcaddy");
	  		$sql = "SELECT email from registered_users as a, whale_packages_x_customer as b where a.id = b.user_id AND b.active = 1 AND b.whale_package_id = '$wp_id'  ";
      		$data6 =  get_str($sql,false,'email');
			
			db_connect("HC");
	  		$sql = "SELECT email from registered_users as a, whale_packages_x_customer as b where a.id = b.user_id AND b.active = 1 AND b.whale_package_id = '$wp_id'  ";
      		$data7 =  get_str($sql,false,'email');
			
			db_connect("BOFF");
	  		$sql = "SELECT email from registered_users as a, whale_packages_x_customer as b where a.id = b.user_id AND b.active = 1 AND b.whale_package_id = '$wp_id'  ";
      		$data8 =  get_str($sql,false,'email');      		

     		$data = array_merge($data1,$data2,$data3,$data4,$data5,$data6,$data7,$data8);
      		break;		
      	     default:break;
      } //switch
     
    }//opt 3	
	
	if($free == 'false' && $reg == 'true' && $wp == 'true'){  //opt 4
     switch ($website) {
      	case '1':
      		db_connect("insider");
	  		$sql = "SELECT email from registered_users where paid = 1 UNION	SELECT email from registered_users as a, whale_packages_x_customer as b where a.id = b.user_id AND b.active = 1 AND b.whale_package_id = '$wp_id'  ";
      		$data =  get_str($sql,false,'email');
      		break;
        case '2':
      		db_connect("handicapper");
	  		$sql = "SELECT email from registered_users where paid = 1 UNION	SELECT email from registered_users as a, whale_packages_x_customer as b where a.id = b.user_id AND b.active = 1 AND b.whale_package_id = '$wp_id'  ";
      		$data =  get_str($sql,false,'email');
      		break;	
      	case '3':
      		db_connect("SBH");
	  		$sql = "SELECT email from registered_users where paid = 1 UNION	SELECT email from registered_users as a, whale_packages_x_customer as b where a.id = b.user_id AND b.active = 1 AND b.whale_package_id = '$wp_id'  ";
      		$data =  get_str($sql,false,'email');
      		break;	

      	case '4':
      		db_connect("HAN911");
	  		$sql = "SELECT email from registered_users where paid = 1 UNION	SELECT email from registered_users as a, whale_packages_x_customer as b where a.id = b.user_id AND b.active = 1 AND b.whale_package_id = '$wp_id'  ";
      		$data =  get_str($sql,false,'email');
      		break;	
        case '5':
      		db_connect("SQPICKS");
	  		$sql = "SELECT email from registered_users where paid = 1 UNION	SELECT email from registered_users as a, whale_packages_x_customer as b where a.id = b.user_id AND b.active = 1 AND b.whale_package_id = '$wp_id'  ";
      		$data =  get_str($sql,false,'email');
      		break;	
      	case '6':
      		db_connect("scratchcaddy");
	  		$sql = "SELECT email from registered_users where paid = 1 UNION	SELECT email from registered_users as a, whale_packages_x_customer as b where a.id = b.user_id AND b.active = 1 AND b.whale_package_id = '$wp_id'  ";
      		$data =  get_str($sql,false,'email');
      		break;
		case '7':
      		db_connect("HC");
	  		$sql = "SELECT email from registered_users where paid = 1 UNION	SELECT email from registered_users as a, whale_packages_x_customer as b where a.id = b.user_id AND b.active = 1 AND b.whale_package_id = '$wp_id'  ";
      		$data =  get_str($sql,false,'email');
      		break;	
		case '8':
      		db_connect("BOFF");
	  		$sql = "SELECT email from registered_users where paid = 1 UNION	SELECT email from registered_users as a, whale_packages_x_customer as b where a.id = b.user_id AND b.active = 1 AND b.whale_package_id = '$wp_id'  ";
      		$data =  get_str($sql,false,'email');
      		break;			

         case '0':	
           
            db_connect("insider");
	  		$sql = "SELECT email from registered_users where paid = 1 UNION	SELECT email from registered_users as a, whale_packages_x_customer as b where a.id = b.user_id AND b.active = 1 AND b.whale_package_id = '$wp_id'  ";
      		$data1 =  get_str($sql,false,'email');
            
      		db_connect("handicapper");
	  		$sql = "SELECT email from registered_users where paid = 1 UNION	SELECT email from registered_users as a, whale_packages_x_customer as b where a.id = b.user_id AND b.active = 1 AND b.whale_package_id = '$wp_id'  ";
      		$data2 =  get_str($sql,false,'email');      		

      		db_connect("SBH");
	  		$sql = "SELECT email from registered_users where paid = 1 UNION	SELECT email from registered_users as a, whale_packages_x_customer as b where a.id = b.user_id AND b.active = 1 AND b.whale_package_id = '$wp_id'  ";
      		$data3 =  get_str($sql,false,'email');
      		
      		db_connect("HAN911");
	  		$sql = "SELECT email from registered_users where paid = 1 UNION	SELECT email from registered_users as a, whale_packages_x_customer as b where a.id = b.user_id AND b.active = 1 AND b.whale_package_id = '$wp_id'  ";
      		$data4 =  get_str($sql,false,'email');
      		
      		db_connect("SQPICKS");
	  		$sql = "SELECT email from registered_users where paid = 1 UNION	SELECT email from registered_users as a, whale_packages_x_customer as b where a.id = b.user_id AND b.active = 1 AND b.whale_package_id = '$wp_id'  ";
      		$data5 =  get_str($sql,false,'email');
      		
      		db_connect("scratchcaddy");
	  		$sql = "SELECT email from registered_users where paid = 1 UNION	SELECT email from registered_users as a, whale_packages_x_customer as b where a.id = b.user_id AND b.active = 1 AND b.whale_package_id = '$wp_id'  ";
      		$data6 =  get_str($sql,false,'email');
			
			db_connect("HC");
	  		$sql = "SELECT email from registered_users where paid = 1 UNION	SELECT email from registered_users as a, whale_packages_x_customer as b where a.id = b.user_id AND b.active = 1 AND b.whale_package_id = '$wp_id'  ";
      		$data7 =  get_str($sql,false,'email');
			
			db_connect("BOFF");
	  		$sql = "SELECT email from registered_users where paid = 1 UNION	SELECT email from registered_users as a, whale_packages_x_customer as b where a.id = b.user_id AND b.active = 1 AND b.whale_package_id = '$wp_id'  ";
      		$data8 =  get_str($sql,false,'email');      		

     		$data = array_merge($data1,$data2,$data3,$data4,$data5,$data6,$data7,$data8);
      		break;		
      	     default:break;
      } //switch
     
    }//opt 4	
	
    if($free == 'true' && $reg == 'true' && $wp == 'true'){ //opt 5   
     
      db_connect("telegram");
      $sql = "SELECT email,phone_id FROM telegram WHERE 1 $sql_website";
      $data0 =   get_str($sql,false,'email');

       switch ($website) {
      	case '1':
      		db_connect("insider");
	  		$sql = "SELECT email from registered_users where active = 1  ";
      		$data =  get_str($sql,false,'email');
      		break;
        case '2':
      		db_connect("handicapper");
	  		$sql = "SELECT email from registered_users where active = 1  ";
      		$data =  get_str($sql,false,'email');
      		break;	
      	case '3':
      		db_connect("SBH");
	  		$sql = "SELECT email from registered_users where active = 1  ";
      		$data =  get_str($sql,false,'email');
      		break;	

      	case '4':
      		db_connect("HAN911");
	  		$sql = "SELECT email from registered_users where active = 1  ";
      		$data =  get_str($sql,false,'email');
      		break;	
        case '5':
      		db_connect("SQPICKS");
	  		$sql = "SELECT email from registered_users where active = 1  ";
      		$data =  get_str($sql,false,'email');
      		break;	
      	case '6':
      		db_connect("scratchcaddy");
	  		$sql = "SELECT email from registered_users where active = 1  ";
      		$data =  get_str($sql,false,'email');
      		break;
		case '7':
      		db_connect("HC");
	  		$sql = "SELECT email from registered_users where active = 1  ";
      		$data =  get_str($sql,false,'email');
      		break;	
		case '8':
      		db_connect("BOFF");
	  		$sql = "SELECT email from registered_users where active = 1  ";
      		$data =  get_str($sql,false,'email');
      		break;			

         case '0':	
           
            db_connect("insider");
	  		$sql = "SELECT email from registered_users where active = 1  ";
      		$data1 =  get_str($sql,false,'email');

      		db_connect("handicapper");
	  		$sql = "SELECT email from registered_users where active = 1  ";
      		$data2 =  get_str($sql,false,'email');

      		db_connect("SBH");
	  		$sql = "SELECT email from registered_users where active = 1  ";
      		$data3 =  get_str($sql,false,'email');

      		db_connect("HAN911");
	  		$sql = "SELECT email from registered_users where active = 1  ";
      		$data4 =  get_str($sql,false,'email');

      		db_connect("SQPICKS");
	  		$sql = "SELECT email from registered_users where active = 1  ";
      		$data5 =  get_str($sql,false,'email');

      		db_connect("scratchcaddy");
	  		$sql = "SELECT email from registered_users where active = 1  ";
      		$data6 =  get_str($sql,false,'email');
			
			db_connect("HC");
	  		$sql = "SELECT email from registered_users where active = 1  ";
      		$data7 =  get_str($sql,false,'email');
			
			db_connect("BOFF");
	  		$sql = "SELECT email from registered_users where active = 1  ";
      		$data8 =  get_str($sql,false,'email');

      		$data = array_merge($data1,$data2,$data3,$data4,$data5,$data6,$data7,$data8);
      		break;

      		default	: break; 
        } //switch	
     
      $data = array_merge($data,$data0);
    } //opt5
	
	if($free == 'true' && $reg == 'true' && $wp == 'false'){ //opt 6   
     
      db_connect("telegram");
      $sql = "SELECT email,phone_id FROM telegram WHERE 1 $sql_website";
      $data0 =   get_str($sql,false,'email');

       switch ($website) {
      	case '1':
      		db_connect("insider");
	  		$sql = "SELECT email from registered_users where paid = 1  ";
      		$data =  get_str($sql,false,'email');
      		break;
        case '2':
      		db_connect("handicapper");
	  		$sql = "SELECT email from registered_users where paid = 1  ";
      		$data =  get_str($sql,false,'email');
      		break;	
      	case '3':
      		db_connect("SBH");
	  		$sql = "SELECT email from registered_users where paid = 1  ";
      		$data =  get_str($sql,false,'email');
      		break;	

      	case '4':
      		db_connect("HAN911");
	  		$sql = "SELECT email from registered_users where paid = 1  ";
      		$data =  get_str($sql,false,'email');
      		break;	
        case '5':
      		db_connect("SQPICKS");
	  		$sql = "SELECT email from registered_users where paid = 1  ";
      		$data =  get_str($sql,false,'email');
      		break;	
      	case '6':
      		db_connect("scratchcaddy");
	  		$sql = "SELECT email from registered_users where paid = 1  ";
      		$data =  get_str($sql,false,'email');
      		break;
		case '7':
      		db_connect("HC");
	  		$sql = "SELECT email from registered_users where paid = 1  ";
      		$data =  get_str($sql,false,'email');
      		break;	
		case '8':
      		db_connect("BOFF");
	  		$sql = "SELECT email from registered_users where paid = 1  ";
      		$data =  get_str($sql,false,'email');
      		break;			

         case '0':	
           
            db_connect("insider");
	  		$sql = "SELECT email from registered_users where paid = 1  ";
      		$data1 =  get_str($sql,false,'email');

      		db_connect("handicapper");
	  		$sql = "SELECT email from registered_users where paid = 1  ";
      		$data2 =  get_str($sql,false,'email');

      		db_connect("SBH");
	  		$sql = "SELECT email from registered_users where paid = 1  ";
      		$data3 =  get_str($sql,false,'email');

      		db_connect("HAN911");
	  		$sql = "SELECT email from registered_users where paid = 1  ";
      		$data4 =  get_str($sql,false,'email');

      		db_connect("SQPICKS");
	  		$sql = "SELECT email from registered_users where paid = 1  ";
      		$data5 =  get_str($sql,false,'email');

      		db_connect("scratchcaddy");
	  		$sql = "SELECT email from registered_users where paid = 1  ";
      		$data6 =  get_str($sql,false,'email');
			
			db_connect("HC");
	  		$sql = "SELECT email from registered_users where paid = 1  ";
      		$data7 =  get_str($sql,false,'email');
			
			db_connect("BOFF");
	  		$sql = "SELECT email from registered_users where paid = 1  ";
      		$data8 =  get_str($sql,false,'email');

      		$data = array_merge($data1,$data2,$data3,$data4,$data5,$data6,$data7,$data8);
      		break;

      		default	: break; 
        } //switch	
     
      $data = array_merge($data,$data0);
    } //opt6
	
	if($free == 'true' && $reg == 'false' && $wp == 'true'){ //opt 7   
     
      db_connect("telegram");
      $sql = "SELECT email,phone_id FROM telegram WHERE 1 $sql_website";
      $data0 =   get_str($sql,false,'email');

       switch ($website) {
      	case '1':
      		db_connect("insider");
	  		$sql = "SELECT email from registered_users as a, whale_packages_x_customer as b where a.id = b.user_id AND b.active = 1 AND b.whale_package_id = '$wp_id'  ";
      		$data =  get_str($sql,false,'email');
      		break;
        case '2':
      		db_connect("handicapper");
	  		$sql = "SELECT email from registered_users as a, whale_packages_x_customer as b where a.id = b.user_id AND b.active = 1 AND b.whale_package_id = '$wp_id'  ";
      		$data =  get_str($sql,false,'email');
      		break;	
      	case '3':
      		db_connect("SBH");
	  		$sql = "SELECT email from registered_users as a, whale_packages_x_customer as b where a.id = b.user_id AND b.active = 1 AND b.whale_package_id = '$wp_id'  ";
      		$data =  get_str($sql,false,'email');
      		break;	

      	case '4':
      		db_connect("HAN911");
	  		$sql = "SELECT email from registered_users as a, whale_packages_x_customer as b where a.id = b.user_id AND b.active = 1 AND b.whale_package_id = '$wp_id'  ";
      		$data =  get_str($sql,false,'email');
      		break;	
        case '5':
      		db_connect("SQPICKS");
	  		$sql = "SELECT email from registered_users as a, whale_packages_x_customer as b where a.id = b.user_id AND b.active = 1 AND b.whale_package_id = '$wp_id'  ";
      		$data =  get_str($sql,false,'email');
      		break;	
      	case '6':
      		db_connect("scratchcaddy");
	  		$sql = "SELECT email from registered_users as a, whale_packages_x_customer as b where a.id = b.user_id AND b.active = 1 AND b.whale_package_id = '$wp_id'  ";
      		$data =  get_str($sql,false,'email');
      		break;
		case '7':
      		db_connect("HC");
	  		$sql = "SELECT email from registered_users as a, whale_packages_x_customer as b where a.id = b.user_id AND b.active = 1 AND b.whale_package_id = '$wp_id'  ";
      		$data =  get_str($sql,false,'email');
      		break;	
		case '8':
      		db_connect("BOFF");
	  		$sql = "SELECT email from registered_users as a, whale_packages_x_customer as b where a.id = b.user_id AND b.active = 1 AND b.whale_package_id = '$wp_id'  ";
      		$data =  get_str($sql,false,'email');
      		break;			

         case '0':	
           
            db_connect("insider");
	  		$sql = "SELECT email from registered_users as a, whale_packages_x_customer as b where a.id = b.user_id AND b.active = 1 AND b.whale_package_id = '$wp_id'  ";
      		$data1 =  get_str($sql,false,'email');

      		db_connect("handicapper");
	  		$sql = "SELECT email from registered_users as a, whale_packages_x_customer as b where a.id = b.user_id AND b.active = 1 AND b.whale_package_id = '$wp_id'  ";
      		$data2 =  get_str($sql,false,'email');

      		db_connect("SBH");
	  		$sql = "SELECT email from registered_users as a, whale_packages_x_customer as b where a.id = b.user_id AND b.active = 1 AND b.whale_package_id = '$wp_id'  ";
      		$data3 =  get_str($sql,false,'email');

      		db_connect("HAN911");
	  		$sql = "SELECT email from registered_users as a, whale_packages_x_customer as b where a.id = b.user_id AND b.active = 1 AND b.whale_package_id = '$wp_id'  ";
      		$data4 =  get_str($sql,false,'email');

      		db_connect("SQPICKS");
	  		$sql = "SELECT email from registered_users as a, whale_packages_x_customer as b where a.id = b.user_id AND b.active = 1 AND b.whale_package_id = '$wp_id'  ";
      		$data5 =  get_str($sql,false,'email');

      		db_connect("scratchcaddy");
	  		$sql = "SELECT email from registered_users as a, whale_packages_x_customer as b where a.id = b.user_id AND b.active = 1 AND b.whale_package_id = '$wp_id'  ";
      		$data6 =  get_str($sql,false,'email');
			
			db_connect("HC");
	  		$sql = "SELECT email from registered_users as a, whale_packages_x_customer as b where a.id = b.user_id AND b.active = 1 AND b.whale_package_id = '$wp_id'  ";
      		$data7 =  get_str($sql,false,'email');
			
			db_connect("BOFF");
	  		$sql = "SELECT email from registered_users as a, whale_packages_x_customer as b where a.id = b.user_id AND b.active = 1 AND b.whale_package_id = '$wp_id'  ";
      		$data8 =  get_str($sql,false,'email');

      		$data = array_merge($data1,$data2,$data3,$data4,$data5,$data6,$data7,$data8);
      		break;

      		default	: break; 
        } //switch	
     
      $data = array_merge($data,$data0);
    } //opt7	
  
	return  $data;
}


// functions for Api SportsDataIo Feeds

function get_api_news($sql_select = "*",$league = "",$limit = 50,$team = ""){
	db_connect("feeds");
	$yesterday = date("Y-m-d",time()-86400);
	if($league != "" ){ $sql_league = " AND  league = '".$league."' ";}
	if($team != "" ){ $sql_team = " AND  TeamShort = '".$team."' ";}
	$sql = "SELECT $sql_select FROM news WHERE  DATE(Updated) >= '$yesterday'  $sql_league $sql_team ORDER BY NewsID DESC LIMIT $limit ";
	
	return  get($sql,'_api_news', false,'NewsID');
}



function get_max_league_api_news(){
	db_connect("feeds");
	$today = date("Y-m-d");
	$sql = "SELECT league, COUNT(*) as Total FROM news WHERE DATE(Updated) >='$today'  GROUP BY league Order by Total DESC limit 1 ";
	return  get_str($sql,true);
}

//trends

function delete_trends($league){
	db_connect("feeds");
	$today = date("Y-m-d");
	$sql = "DELETE FROM trends WHERE league = '$league' AND date = '$today'";
	return execute($sql);
}

function get_all_trends_date($date){

    db_connect("feeds");
	$sql = "SELECT CONCAT_WS('_',league,away,home) as 'control' from trends WHERE date = '".$date."' ";
	return  get_str($sql,false,'control');

}

function get_trends($sql_select = "*",$league = "",$limit = 50,$team = ""){
	db_connect("feeds");
	$today = date("Y-m-d");
	if($league != "" ){ $sql_league = " AND  league = '".$league."' ";}
	if($team != "" ){ $sql_team = " AND  TeamShort = '".$team."' ";}
	$sql = "SELECT $sql_select FROM trends WHERE  DATE(trends.date) >= '$today'  $sql_league $sql_team ORDER BY trends.id ASC LIMIT $limit ";
	//echo $sql."<BR>";
	return  get($sql,'_trends', false);
}

function get_max_league_trends(){
	db_connect("feeds");
	$today = date("Y-m-d");
	$sql = "SELECT league, COUNT(*) as Total FROM trends WHERE trends.date >='$today'  GROUP BY league Order by Total DESC limit 1 ";
	//echo $sql."<BR>";
	return  get_str($sql,true);
}

function payment_methods($type=""){
	db_connect("insider");
	
	if ($type != "") {
	   $query_type = " WHERE type = '$type' ";	
	}
	else {
	   $query_type = "";
	}
		
	$sql = "SELECT * FROM payment_methods $query_type ORDER BY id ASC";	
		
	return get_str($sql, false, "prefix");
}

//Superbowl contest functions
function get_contest_superbowl_contest($id){
	db_connect("superbowl_contest");
	$sql = "SELECT * FROM contests WHERE id = '$id'";
	return get($sql, "_contest_superbowl" ,true);
}

function get_contests_superbowl_contest(){
	db_connect("superbowl_contest");
	$sql = "SELECT * FROM contests ORDER BY id ASC";
	return get($sql, "_contest_superbowl" ,false);
}

function get_all_contests_superbowl_contest(){
	db_connect("superbowl_contest");
	$sql = "SELECT * FROM contests ORDER BY id ASC";
	return get($sql, "_contest_superbowl" ,false, "id");
}

function get_picks_superbowl_contest($id_contest,$id_site=0){
	db_connect("superbowl_contest");
	
	if ($id_site == 0) {
	   $query_site = "";	
	}
	else {
	   $query_site = " AND id_site = '$id_site' ";
	}	
		
	$sql = "SELECT * FROM picks WHERE id_contest = '$id_contest' $query_site ORDER BY id_player ASC";	
			
	return get($sql, "_pick_superbowl_contest" ,false);
}

function get_pick_superbowl_contest($id){
	db_connect("superbowl_contest");
	$sql = "SELECT * FROM picks WHERE id = '$id'";	
	return get($sql, "_pick_superbowl_contest" ,true);
}

function get_teams_superbowl_contest($id_contest){
	db_connect("superbowl_contest");
	$sql = "SELECT * FROM teams WHERE id_contest = '$id_contest' ORDER BY id ASC";	
	return get($sql, "_team_superbowl_contest" ,false);
}

function get_team_superbowl_contest($id){
	db_connect("superbowl_contest");
	$sql = "SELECT * FROM teams WHERE id = '$id'";	
	return get($sql, "_team_superbowl_contest" ,true);
}

function get_winners_superbowl_contest($id_contest,$is_admin=0,$id_site=0){
	
	db_connect("superbowl_contest");
	
	if($is_admin == 0){
	   $sql_publish = " AND b.publish = 1 ";
	}else{
	   $sql_publish = "";	
	}
	
	$sql = "
	SELECT * 
	FROM winners as a, contests as b 
	WHERE a.id_contest = b.id AND
	      a.id_contest = '$id_contest' AND
		  a.id_site    = '$id_site'	  
	      $sql_publish		   
	ORDER BY a.id asc";	
	
	return get_str($sql, false);
}

function get_pick_player_superbowl_contest($id_player,$id_contest, $id_site){
	db_connect("superbowl_contest");
	$sql = "SELECT * FROM picks WHERE id_player = '$id_player' AND id_contest = '$id_contest' AND id_site = '$id_site'";
	return get($sql, "_pick_superbowl_contest" ,true);
}

function delete_winners_superbowl_contest($id_contest){
	db_connect("superbowl_contest");
	$sql = "DELETE FROM winners WHERE id_contest = '$id_contest'";
	return execute($sql);
}

function update_picks_as_not_graded_superbowl_contest($id_contest){
	db_connect("superbowl_contest");
	$sql = "UPDATE picks SET graded = 0 WHERE id_contest = '$id_contest'";
	return execute($sql);
}

function update_teams_as_not_winners_superbowl_contest($id_contest){
	db_connect("superbowl_contest");
	$sql = "UPDATE teams SET winner = 0 WHERE id_contest = '$id_contest'";
	return execute($sql);
}

function get_user_superbowl_contest($username, $password){
	db_connect("superbowl_contest");
	$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password' AND active = 1";
	return get($sql, "_user_superbowl_contest" ,true);
}

function get_users_superbowl_contest(){
	db_connect("superbowl_contest");
	$sql = "SELECT * FROM users ORDER BY id ASC";
	return get($sql, "_user_superbowl_contest" ,false);
}

function get_user_superbowl_contest_by_id($id){
	db_connect("superbowl_contest");
	$sql = "SELECT * FROM users WHERE id = '$id'";
	return get($sql, "_user_superbowl_contest" ,true);
}
//End Superbowl contest functions

//Braket
function get_brakets_player($player, $contest, $site){
	db_connect("braket");
	$sql = "SELECT * FROM brakets_count WHERE player = '$player' AND contestID = $contest AND id_site = $site";	
	return get($sql, "_braket_player", true);
}
//End Braket

//Whale packages:

function get_whale_packages(){
	db_connect("insider");
			
	$sql = "SELECT * FROM whale_packages ORDER BY id DESC";
	
	return get($sql, "_whale_package" ,false, "id");
}

function verify_exist_customer_active_wp($user_id,$wp_id){
	db_connect("insider");
			
	$sql = "SELECT *
            FROM whale_packages_x_customer
            WHERE user_id = '$user_id' AND
			      whale_package_id = '$wp_id' AND
				  active = 1";
	
	return get($sql, "_whale_package" ,true);
}

function get_non_expired_whale_packages(){
	db_connect("insider");
	
	date_default_timezone_set('America/New_York');
	
	$today = date("Y-m-d");	
			
	$sql = "SELECT * FROM whale_packages WHERE end_season_date >= '$today' ORDER BY id DESC";
		
	return get($sql, "_whale_package" ,false);
}

function get_expired_whale_packages(){
	db_connect("insider");
	
	date_default_timezone_set('America/New_York');
	
	$today = date("Y-m-d");	
			
	$sql = "SELECT * FROM whale_packages WHERE end_season_date < '$today' ORDER BY id DESC";
	
	return get($sql, "_whale_package" ,false);
}

function get_whale_package($id){
	db_connect("insider");
	$sql = "SELECT * FROM whale_packages WHERE id = '$id'";
	return get($sql, "_whale_package" ,true);
}

function get_user_whale_packages($id,$active=1){
	db_connect("insider");
	
	if($active){
	   $sql_active = " AND active = 1 ";
	}else{
	   $sql_active = "";	
	}	
	
	$sql = "SELECT *
            FROM whale_packages_x_customer
            WHERE user_id = '$id'
			      $sql_active
			ORDER BY id ASC";	
	
	return get($sql, "_whale_package_x_customer" ,false, "whale_package_id");
}

function get_wp_customer($wp_id){
	db_connect("insider");
			
	$sql = "SELECT *
            FROM whale_packages_x_customer
            WHERE id = '$wp_id'";
	
	return get($sql, "_whale_package_x_customer" ,true);
}

function set_expired_whale_packages_x_customer($wp_id){
	db_connect("insider");
	
	$today = date("Y-m-d");
	
	$sql = "UPDATE whale_packages_x_customer 
	        SET active = 0 
			WHERE whale_package_id = '$wp_id'";
	return execute($sql);
}

function get_all_users_whale_package_by_list($str_ids,$fields = "",$wp_id){
	db_connect("insider");
	if($fields == "" ){ $fields = " * "; };
	
	$sql = "SELECT $fields FROM registered_users as a, whale_packages_x_customer as b WHERE a.id = b.user_id AND b.active = 1 AND b.whale_package_id = '$wp_id' AND a.id IN ($str_ids)";
			
	//echo $sql;
	
	return get($sql, "_user");
}

//New Function
function get_customers_purchased_whale_package($wp_id){
	db_connect("insider");
	
	$sql = "SELECT * FROM whale_packages_x_customer WHERE active = 1 AND whale_package_id = '$wp_id'";
			
	return get($sql, "_whale_package_x_customer", false, "user_id");
}
//End whale packages
?>