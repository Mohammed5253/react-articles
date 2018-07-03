<?php


use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


// function nx_testsendemail( Request $request, Response $response )
// {
// 	$res = array( 'message_code' => 999, 'message_text' => 'Functional part is commented.' );

// 	if (LIVEorDEV == "LIVE")
// 	{
// 		require_once ('/var/www/html/PHPmailer/class.phpmailer.php');
// 		$to = "SLimje@Torinit.com";
// 		$message = "Test mail";
// 		$subject = "Your Registration is successful at Nafex.com!";
		
// 		$from = "info@nafex.com";
// 		$mail = new PHPMailer();
// 		$mail->IsSMTP(true); // SMTP
// 		$mail->SMTPAuth   = true;  // SMTP authentication
// 		$mail->Mailer = "smtp";
// 		$mail->Host= "tls://smtp.gmail.com"; 
// 		$mail->Port = 465;  // SMTP Port
// 		$mail->Username = "info@nafex.com";  // SMTP  Username
// 		$mail->Password = "Sai@prema";  // SMTP Password
// 		$mail->SetFrom($from, 'Nafex Admin');
// 		$mail->AddReplyTo($from,'Nafex Admin');
// 		$mail->Subject = $subject;
// 		$mail->MsgHTML($message);
// 		$address = $to;

// 		$mail->AddCC("SLimje@Torinit.com", "Sunil");

// 		$res = array( '0' => "Testing@test.com" , '1' => 'Testing');
// 		$mail->AddAddress($res['0'], $res['1']);

// 		//$mail->AddAddress($address,"Sunil Limje");

// 		if (!$mail->Send())
// 			$res = array( 'message_code' => 1000, 'message_text' => 'Error');
// 		else
// 			$res = array( 'message_code' => 1000, 'message_text' => 'Done on LIVE.  Please check email: ' . $to  );
// 	}
// 	else
// 	{
// 		require_once ('/var/www/html/PHPmailer/class.phpmailer.php');
// 		$to = "SLimje@Torinit.com";
// 		$message = "Test mail";
// 		$subject = "Your Registration is successful at Nafex.com!";
		
// 		$from = "info@nafex.com";
// 		$mail = new PHPMailer();
// 		$mail->IsSMTP(true); // SMTP
// 		$mail->SMTPAuth   = true;  // SMTP authentication
// 		$mail->Mailer = "smtp";
// 		$mail->Host= "tls://smtp.gmail.com"; 
// 		$mail->Port = 465;  // SMTP Port
// 		$mail->Username = "info@nafex.com";  // SMTP  Username
// 		$mail->Password = "Sai@prema";  // SMTP Password
// 		$mail->SetFrom($from, 'Nafex Admin');
// 		$mail->AddReplyTo($from,'Nafex Admin');
// 		$mail->Subject = $subject;
// 		$mail->MsgHTML($message);
// 		$address = $to;

// 		$mail->AddCC("SLimje@Torinit.com", "Sunil");

// 		$res = array( '0' => "Testing@test.com" , '1' => 'Testing');
// 		$mail->AddAddress($res['0'], $res['1']);

// 		//$mail->AddAddress($address,"Sunil Limje");

// 		if (!$mail->Send())
// 			$res = array( 'message_code' => 1000, 'message_text' => 'Error');
// 		else
// 			$res = array( 'message_code' => 1000, 'message_text' => 'Done on Dev.  Please check email: ' . $to  );
// 	}
// 	return $response->withJson( $res, 200 );

// }

function PrepareEmail($purpose,$data)
{
	
	// if (LIVEorDEV == "LIVE")
	// {
	// 	$BaseURL = "http://www.nafex.com/";
	// }
	// else
	// {
	// 	$BaseURL = "http://13.59.118.35/nafex2dev/";
	// }
	$BaseURL = "http://theapplink.net/sivicstaging/Sivic/";
	 $logoURL = $BaseURL."src/images/logo2.png";
	 $verifyImageURL = $BaseURL."src/images/verify-icon2.png";
	 $logoGray = $BaseURL."src/images/logo-grey.png";
	// $googlePlayStoreURL = $BaseURL . "/images/googlestore.png";
	// $applePlayStoreURL = $BaseURL . "images/applestore.png";
	// $dropURL = $BaseURL . "images/drop.png";
	// $trackUrl = $BaseURL . "trackO/";
	// $loginUrl = $BaseURL;
	// $documentUrl = $BaseURL . "api/";
	// $acceptBidUrl = $BaseURL . "acceptBestRate/";   //"api/nafex/acceptbestrate/";
	// $downloadimage = $BaseURL . "images/download.png";

	
	$Message = "";
	if ($purpose == "WELCOMEMAIL") 
	{
		// $data[0] = "UserEmal" , $data[1] = Verification Link 
		$Message = file_get_contents("templates/welcome.html");

		// $Message = str_replace("TOKEN_LOGOURL", $logoURL, $Message);
		// $Message = str_replace("TOKEN_GOOGLEPLAYSTOREURL", $googlePlayStoreURL, $Message);
		// $Message = str_replace("TOKEN_APPLEPLAYSTOREURL", $applePlayStoreURL, $Message);
		$Message = str_replace("SIVIC_LOGO", $logoURL, $Message);
		$Message = str_replace("VERIFY_IMG", $verifyImageURL, $Message);
		$Message = str_replace("LOGO_GRAY", $logoGray, $Message);
		$encodeEmail = base64_encode($data[0]);
		$Message = str_replace("TOKEN_PERSONEMAIL", $data[0], $Message);
		$Message = str_replace("TOKEN_VARIFICATIONLINK", $data[1], $Message);
		$Message = str_replace("TOKEN_ENCODEEMAIL", $encodeEmail, $Message);
		// $Message = str_replace("TOKEN_MOBILENUMBER", $data[2], $Message);
		// $Message = str_replace("TOKEN_TEMPPASSWORD", $data[3], $Message);

		
	}
	if ($purpose == "FORGETPASSWORD") 
	{

		$Message = file_get_contents("templates/forgetpassword.html");
		$Message = str_replace("TOKEN_PERSONEMAIL", $data[0], $Message);
		$Message = str_replace("TOKEN_LINK", $data[1], $Message);
		$Message = str_replace("TOKEN_NEWPASSWORD", $data[2], $Message);

		$encodeEmail = base64_encode($data[0]);
		$Message = str_replace("TOKEN_ENCODEEMAIL", $encodeEmail, $Message);

		
	}

	if ($purpose == "FFMCMANAGERREGISTRATION") 
	{
		$Message = file_get_contents("templates/ffmcManagerRegistration.html");

		$Message = str_replace("TOKEN_LOGOURL", $logoURL, $Message);
		$Message = str_replace("TOKEN_GOOGLEPLAYSTOREURL", $googlePlayStoreURL, $Message);
		$Message = str_replace("TOKEN_APPLEPLAYSTOREURL", $applePlayStoreURL, $Message);

		$Message = str_replace("TOKEN_PERSONNAME", $data[0], $Message);
		$Message = str_replace("TOKEN_USERNAME", $data[1], $Message);
		$Message = str_replace("TOKEN_MOBILENUMBER", $data[2], $Message);
		$Message = str_replace("TOKEN_TEMPPASSWORD", $data[3], $Message);

		
	}
	

	return $Message;

}


function SendEmailwithHeader( $toEmail, $toName, $subject, $message, $purpose, $tocc)
{

		require_once ('/var/www/html/PHPmailer/class.phpmailer.php');
		$from = "info@sivic.com";
		$mail = new PHPMailer();
		$mail->IsSMTP(true); // SMTP
		$mail->SMTPAuth   = true;  // SMTP authentication
		$mail->Mailer = "smtp";
		$mail->Host= "ssl://smtp.gmail.com"; 
		$mail->Port = 465;  // SMTP Port
		$mail->Username = "info@nafex.com";  // SMTP  Username
		$mail->Password = "Sai@prema";  // SMTP Password
		$mail->SetFrom($from, 'Sivic.com');
		// $mail->AddReplyTo($from,'Nafex.com');
		$mail->Subject = $subject;
		$mail->MsgHTML($message);
		// $address = $to;
		$mail->AddAddress($toEmail, $toName);
		return $mail->Send();

}




