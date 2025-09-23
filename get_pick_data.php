<?
session_start();
include ('/home/gm1q5icx/domains/inspin.com/public_html/utilities/includes.php');

date_default_timezone_set('America/New_York'); 

$id = param("id");

$item = get_pick_st($id);

$rot_away     = $item->vars["rotation_number_away"];
$rot_home     = $item->vars["rotation_number_home"];

$team_id_away = $item->vars["team_id_away"];

if(contains($team_id_away,":")){
   $team_away = get_team($team_id_away);
   $team_away = $team_away["teamnamefirst"]." ".$team_away["teamnamenick"];
}else{
   $team_away = $item->vars["team_id_away"];	
}

$team_id_home = $item->vars["team_id_home"];

if(contains($team_id_home,":")){	
   $team_home = get_team($team_id_home);
   $team_home = $team_home["teamnamefirst"]." ".$team_home["teamnamenick"];	
}else{
   $team_home = $item->vars["team_id_home"];	
}

$number_stars = $item->vars["number_stars"];
$game_date    = $item->vars["game_date"];
$game_date_param  = date("Y-m-d", strtotime($game_date));
$time         = date("h:i:A", strtotime($game_date));
$time         = explode(":",$time);
$start_hour   = $time[0];
$start_minute = $time[1];
$start_data   = $time[2];
$whale_package = $item->vars["whale_package"];
$free_pick     = $item->vars["free_pick"];				

$array_pick[] = array("number_stars" => $number_stars, "rot_away" => $rot_away, "rot_home" => $rot_home, "team_away" => $team_away, "team_home" => $team_home, "game_date" => $game_date_param, "start_hour" => $start_hour, "start_minute" => $start_minute, "start_data" => $start_data, "whale_package" => $whale_package, "free_pick" => $free_pick);

if(!empty($array_pick)){					
   echo json_encode($array_pick);	
}else{
   echo "";
}
?>