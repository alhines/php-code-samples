<?
session_start();
include ('/home/gm1q5icx/domains/inspin.com/public_html/utilities/includes.php');

$sport = strtoupper(param("sport"));

$picks = get_non_graded_picks_sport($sport);
$i=0;

foreach($picks as $item){	
	$id   = $item->vars["id"];
	$type = $item->vars["type"];
	     
	if($type == "P"){//Parlay pick
	   $i++;
	   $pick = "Parlay Pick # ".$i;
	}else{
	   $pick = $item->vars["pick"];	
	}	
		
	$array_picks[] = array("pick" => $pick, "id" => $id);
}


if(!empty($array_picks)){					
   echo json_encode($array_picks);	
}else{
   echo "";
}
?>