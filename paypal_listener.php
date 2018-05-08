<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: index.php');exit();
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "cmd=_notify-validate&" . http_build_query($_POST));
$response = curl_exec($ch);
curl_close($ch);

if ($response == "VERIFIED") {
	
	$ans = array();
	foreach ($_POST as $key => $value){
		$ans[$key] = $value;
	}
	$email = $ans["payer_email"];
	$message = '
			 <html>
				<head>
				 <title>Welcome on Hoppy Learning !</title>
				</head>
				<body>
				 <h1>Thanks '.$ans["payer_email"].' for buying the plan : '.$ans["item_name"].' !</1>
				 <p>Cest good</p>
				</body>
			 </html>
			 ';

	$sub = 0;
	$now = date('Y-m-j');

	switch($ans["item_name"]){
		case 'Hoppy monthly':
			$sub = 1;
			$date =  explode('-', $now);
			$month = (intval($date[1])+1)%12;
			$over = $date[0].'-'.$month.'-'.$date[2];
		break;
		case 'Hoppy semester':
			$sub = 2;
			$date =  explode('-', $now);
			$month = (intval($date[1])+6)%12;
			$over = $date[0].'-'.$month.'-'.$date[2];
		break;
		case 'Hoppy yearly':
			$sub = 3;
			$date =  explode('-', $now);
			$year = intval($date[0])+1;
			$over = $year.'-'.$date[1].'-'.$date[2];
		break;
	}
	
	include 'bdd.php';
	$req = $pdo->prepare("UPDATE hoppy_users
												SET User_Subscription=:sub, 
												User_Subscription_DateBegin=':now', 
												User_Subscription_DateOver=':over'
												WHERE User_Email=:id");
	$req->execute(array(
		':sub' => $sub,
		':now' => $now,
		':over' => $over,
		':id' => $email
	));
	$subject = 'Hoppy';
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= 'From: Hoppy Learning <hoppy@efrei.fr>' . "\r\n" . 'Reply-to: Admin <theo175@gmail.com>' . "\r\n";

	mail($email, $subject, $message, $headers);
}

?>