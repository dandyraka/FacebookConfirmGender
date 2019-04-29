<?php
set_time_limit(0);
require_once('color.php');

function request($token){
	$a = json_decode(file_get_contents('https://graph.facebook.com/me/friendrequests?limit=5000&access_token='.$token), true);
	return $a['data'];
}
function gender($id, $tok){
	$a = json_decode(file_get_contents('https://graph.facebook.com/'.$id.'/?access_token='.$tok), true);
	$gender = $a['gender'];
	return $gender;
}

function accept($id, $token){
	$url = 'https://graph.facebook.com/me/friends/'.$id.'?access_token='.$token;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($ch);
	curl_close($ch);
	if($result == true){
		$unf = Console::green('[CONFIRMED]');
	} else {
		$unf = Console::red('[FAILED TO CONFIRM]');
	}
	return $unf;
}

echo Console::blue("     Facebook Auto Confirm\n");
echo Console::blue("           by Gender\n");
echo Console::blue("          © dandyraka\n\n");

//INPUT
echo "Facebook token : ";
$fbtoken = trim(fgets(STDIN));
echo "Gender (F/M) : ";
$gndr = strtoupper(trim(fgets(STDIN)));
echo "\n";

$count = 0;
$FL = request($fbtoken);
$totalFL = count($FL);
foreach($FL as $list){
	$count++;
	$name = $list['from']['name'];
	$id = $list['from']['id'];
	$gender = gender($id, $fbtoken);
	if($gndr == "F"){
		$inputGender = "female";
	} else if($gndr == "M"){
		$inputGender = "male";
	}
	echo Console::cyan("(" .$count. "/" .$totalFL. ")");
	if($gender == $inputGender){
		echo Console::green('['.strtoupper($inputGender).']').' '.$name.' '.accept($id, $fbtoken);
		echo "\r\n";
	}else{
		echo Console::red('['.strtoupper($gender).']').' '.$name;
		echo "\r\n";
	}
}
