<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

function sivic_register_user(Request $request, Response $response)
{	
	$res = array( 'message_code' => 999, 'message_text' => 'Functional part is commented.' );
	$db = database();
	$data = json_decode($request->getBody());

	
	if (isset($data->fname))
		$fname = $data->fname;

	if (isset($data->lname))
		$lname = $data->lname;

	if (isset($data->address))
		$address = $data->address;

	if (isset($data->zipcode))
		$zipcode = $data->zipcode;

	if (isset($data->city))
		$city = $data->city;

	if (isset($data->state))
		$state = $data->state;

	if (isset($data->email))
		$email = $data->email;

	if (isset($data->phone))
		$phone = $data->phone;

	if (isset($data->password))
		$password = md5($data->password);

	if (isset($data->dob))
		$dob = $data->dob;

	if (isset($data->socialId))
		$socialId = $data->socialId;	
	
	if (isset($data->lat))
		$lat = $data->lat;
	
	if (isset($data->long))
		$long = $data->long;

	if (isset($data->socialPlatformId))
		$socialPlatformId = $data->socialPlatformId;

	if (isset($data->gender))
		$gender = $data->gender;
	

	// $role_id=0;


	// //validation start
	if ($fname == null || $fname=="") 
		$res = array( 'message_code' => 999, 'message_text' => 'Please provide First Name.');
	else if ($address == null || $address=="")
	$res = array( 'message_code' => 999, 'message_text' => 'Please provide Address.');
	else if ( $zipcode == null || $zipcode=="" )
		$res = array( 'message_code' => 999, 'message_text' => 'Please provide Zip Code.');
	else if ( $city == null || $city=="" )
	$res = array( 'message_code' => 999, 'message_text' => 'Please provide City.');
	else if ( $state == null || $state=="" )
	$res = array( 'message_code' => 999, 'message_text' => 'Please provide State.');
	else if ( $email == null || $email=="" )
	$res = array( 'message_code' => 999, 'message_text' => 'Please provide Email.');
	else if ( $password == null || $password=="" )
	$res = array( 'message_code' => 999, 'message_text' => 'Please provide Password.');
	else if ( $gender == null || $gender=="" )
	$res = array( 'message_code' => 999, 'message_text' => 'Please provide Gender.');
	
	else
	{
	// //validation end

		$get_email_count = "SELECT count(*) FROM users WHERE email = '".$email."' " ;
		$result = $db->get_var($get_email_count);
		
		if($result==0)
		{
			$base_query = "INSERT INTO users 
			(first_name, last_name, email, phone , password,gender_type_id,city,state,zipcode,dob,address,social_id,social_platform_id,latitude,longitude)
			VALUES ('" . $fname . "', '" . $lname . "', '" . $email . "','" . $phone . "','" . $password . "','".$gender."','".$city."','".$state."','".$zipcode."','".$dob."','".$address."','".$socialId."','".$socialPlatformId."','".$lat."','".$long."')";
			
			if($db->query($base_query))
			
			{
				$email_encrypt_link="ss";
				$emailData = array('0' => $email, "1"=>$email_encrypt_link);

				$Message = PrepareEmail("WELCOMEMAIL",$emailData);
				$subject = "Congratulation - Sivic Team";
				
			
				$to = array('0' => $email, '1' => "Appwelt Test");
				
				
				SendEmailwithHeader($to['0'], $to['1'], $subject, $Message, "WELCOMEMAIL","");
				
				$res = array( 'message_code' => 1000, 'message_text' => ' Successfully  Register.' );
			}
			else
			{
				$res = array( 'message_code' => 999, 'message_text' => "Execution Failed" );
			}
		}
		else
		{
			$res = array( 'message_code' => 999, 'message_text' => "Duplicate Email Id" );
		}
	 }
	return $response->withJson( $res, 200 );	
}


function sivic_login( Request $request, Response $response ){
	$res = array( 'message_code' => 999, 'message_text' => 'Functional part is commented.' );
	$db = database();
    $data = json_decode($request->getBody());
	if (isset($data->email))
		$email = $data->email;

	if (isset($data->password))
		$password = md5($data->password);
	
	if (isset($data->urlemail))
		$urlemail = $data->urlemail;
	else
	    $urlemail = '';		

	// if ($email == NULL || $email == "")
	// 	$res = array( 'message_code' => 999, 'message_text' => 'Please provide your Email for login.');
    if ( ($password == NULL) || ($password=="") ) {
		$res = array( 'message_code' => 999, 'message_text' => 'Please provide your Password for login.');
	}
	else if( ($urlemail != NULL) AND  ($email == "") )
	{
		$email = base64_decode($urlemail);
		$model = new Model();
		$res = $model->userverifyemail($email); 
    	return $response->withJson($res, 200);

	}
	else
	{

		$Where  = "";
		$Where  = " email ='" . $email . "'";
		$cnt = $db->get_var("SELECT count(user_id) FROM `users` WHERE " . $Where);

	   if ($cnt == 0)
		    $res = array( 'message_code' => 999, 'message_text' => 'This email is not registered with Sivic. Please contact Sivic support team.');
        else
        {
        	$cnt = $db->get_var("SELECT count(user_id) FROM `users` WHERE " . $Where . " and password='" . $password . "'");
	        if ($cnt == 0)
		     $res = array( 'message_code' => 999, 'message_text' => 'Password does not match. Please contact Sivic support team.');
            else
            {
                $base_query = "SELECT * FROM `users` WHERE " . $Where . " and password ='" . $password . "'";

                $result = $db->get_results( $base_query );
	            if ( isset( $result ) && !empty( $result ) )
	            {
	            	$res = array( 'message_code' => 1000, 'message_text' => $result[0]);
	            }
	           else if ( $result == null )
		            $res = array( 'message_code' => 999, 'message_text' => 'User data does not exists!. Please contact Nafex support team' );
	           else 
		            $res = array( 'message_code' => 999, 'message_text' => 'Unable to load leads. Please contact Nafex support team.' );
            }
	 	}
    }

	return $response->withJson( $res, 200 );

}

function sivic_user_verify_email( Request $request, Response $response ){

	$body = $request->getParsedBody();

	$email = $body['email'];

    $db = new Model();

    $res = $db->userverifyemail($email);

    return $response->withJson($res, 200);

}


function sivic_forget_password( Request $request, Response $response ){

		$data = json_decode($request->getBody());
		
		if (isset($data->useremail))
		$email = $data->useremail;

	    $db = new Model();
	  
	    $res = $db->forgetPassword($email);

	    return $response->withJson($res, 200);

}

function sivic_change_password( Request $request, Response $response ){

	// $body = $request->getParsedBody();

	$data = json_decode($request->getBody());

	// $new_password = $body['new_password'];
	// $confirm_new_password = $body['confirm_new_password'];

	$new_password = $data->password;
	$confirm_new_password = $data->confirmpassword;
	$email = $data->email;

	$db = new Model();

    if ($email == NULL) {

	$res = $db->changePassword($body['user_id'], $body['old_password'], $new_password, $confirm_new_password);
    }else {

	$res = $db->resetPassword($email, $new_password, $confirm_new_password);
    }
    
return $response->withJson($res, 200);

}

function sivic_codemaster( Request $request, Response $response ){

	$res = array( 'message_code' => 999, 'message_text' => 'Functional part is commented.' );
	$db = new Model();
	$res = $db->codeMaster();
	return $response->withJson($res, 200);
}


function sivic_user_issues( Request $request, Response $response ){

	// $body = $request->getParsedBody();
	// $headers = $request->getHeaders();

	// $user_id = $body['user_id'];
	// $issue_id = $body['issue_id'];
	$data = json_decode($request->getBody());

	if (isset($data->user_id))
		$user_id = $data->user_id;
	if (isset($data->selectedIssue))
		$issue_id = $data->selectedIssue;

		// $res = array( 'message_code' => 999, 'message_text' => $issue_id );	
	// 
	// if (strpos($issue_id, ',') !== false) {
        $issue_id1 = implode(',', $issue_id);
    //  }
	// $res = array( 'message_code' => 999, 'message_text' => $issue_id );	

    $db = new Model();

	$res = $db->userIssues($user_id, $issue_id);
	// $res = array( 'message_code' => 999, 'message_text' => $issue_id1 );

    return $response->withJson($res, 200);
}


function resendVerificationEmail(Request $request, Response $response)
{
	$res = array( 'message_code' => 999, 'message_text' => 'Functional part is commented.' );
	$db = database();
	$data = json_decode($request->getBody());

	
	if (isset($data->email))
		$fname = $data->email;

	$checkEmail = "SELECT count(*) FROM users WHERE email= '" .$email. "' ";

	$result = $db->get_var($checkEmail);

	if($result==1)
	{
		$checkStatus = "SELECT email_verified FROM users where email= '" .$email. "' ";

		$status = $db->get_var($checkStatus);

		if($status == 'No')
		{
				$Message = PrepareEmail("WELCOMEMAIL");
				$subject = "Congratulation - Sivic Team";
				
			
				$to = array('0' => $email, '1' => "Appwelt Test");
				
				// print_r($to);
				SendEmailwithHeader($to['0'], $to['1'], $subject, $Message, "WELCOMEMAIL","");
				
					// $res = array( 'message_code' => 1000, 'message_text' => $Message);
				
				$res = array( 'message_code' => 1000, 'message_text' => ' Successfully  Register.' );
		}
		else
		{
			$res = array( 'message_code' => 999, 'message_text' => 'You are already Verified' );
		}
	}
	else
	{
		$res = array( 'message_code' => 999, 'message_text' => 'This Email Id not Exists' );
	}
}

function sivic_list_wall( Request $request, Response $response ){

    $db = new Model();

    $res = $db->listSivicOnWall();

    return $response->withJson($res, 200);

}

function sivic_create( Request $request, Response $response ){
  
    $body = json_decode($request->getBody());

    $influencer_id = $body->influencer_id;

    $media = $body->media;

    if (strpos($influencer_id, ',') !== false) {
	$influencer_id = explode(',', $influencer_id);
	}

    $db = new Model();

    $res = $db->createSivic($body, $influencer_id, $media);

    return $response->withJson($res, 200);

}

function sivic_script_according_issue( Request $request, Response $response ){

	$body = json_decode($request->getBody());

    $issue_id = $body->issue_id;

    $db = new Model();

    $res = $db->issueScript($issue_id);

    return $response->withJson($res, 200);

}

function sivic_search_influencer( Request $request, Response $response ){

	$body = json_decode($request->getBody());

    $searchname = $body->searchname;

    $db = new Model();

    $res = $db->searchInfluencer($searchname);

    return $response->withJson($res, 200);

}

// function sivic_social_login( Request $request, Response $response ){

// 	$body = json_decode($request->getBody());

// 	if($body->provider == "facebook"){
// 		$body->social_platform_id = 1;
// 	}

// 	if($body->provider == "google"){
// 		$body->social_platform_id = 2;
// 	}

//     $db = new Model();

//     $res = $db->socialLogin($body);

//     return $response->withJson($res, 200);

// }


function sivic_social_login( Request $request, Response $response ){
	

	$body = json_decode($request->getBody());

	if($body->provider == "facebook"){
		$body->social_platform_id = 1;
	}

	if($body->provider == "google"){
		$body->social_platform_id = 2;
	}
	if (($body->fname == null) || ($body->fname=="")) 
	$res = array( 'message_code' => 999, 'message_text' => 'Please provide First Name.');
	else if (($body->address == null) || ($body->address==""))
	$res = array( 'message_code' => 999, 'message_text' => 'Please provide Address.');
	else if ( ($body->zipcode == null) || ($body->zipcode=="") )
		$res = array( 'message_code' => 999, 'message_text' => 'Please provide Zip Code.');
	else if ( ($body->city == null) || ($body->city=="") )
	$res = array( 'message_code' => 999, 'message_text' => 'Please provide City.');
	else if ( ($body->state == null) || ($body->state=="") )
	$res = array( 'message_code' => 999, 'message_text' => 'Please provide State.');
	else if ( ($body->email == null) || ($body->email=="") )
	$res = array( 'message_code' => 999, 'message_text' => 'Please provide Email.');
	else if ( ($body->gender == null) || ($body->gender=="") )
	$res = array( 'message_code' => 999, 'message_text' => 'Please provide Gender.');
	else{
		$db = new Model();

		$res = $db->socialLogin($body);
	}
	return $response->withJson($res, 200);


}


function sivic_social_check( Request $request, Response $response ){

	$data = json_decode($request->getBody());

	$email = $data->email;

	$db = new Model();

	$res = $db->checkSocialLoginRegister($email);

    return $response->withJson($res, 200);

}

function sivic_update_flag_register( Request $request, Response $response ){

	$body = json_decode($request->getBody());

	$db = new Model();
	
    $res = $db->updateFlagwhileRegister($body);

    return $response->withJson($res, 200);

}

function sivic_issue_influencer_list( Request $request, Response $response )
{
	$db = database();
	$data = json_decode($request->getBody());
		
		if (isset($data->issue_id))
		$issue_id = $data->issue_id;
		$sSQL = "SELECT influencers.influencer_id as id,influencers.name as name,influencers.profile_pic_url as pic FROM influencers
		INNER JOIN issues_influencers ON issues_influencers.influencer_id=influencers.influencer_id WHERE issues_influencers.issue_id='".$issue_id."' "; 
		$result = $db->get_results( $sSQL );
		if ( isset( $result ) && !empty( $result ) )
		$res = array( 'message_code' => 1000, 'message_text' => $result );
		else
		$res = array( 'message_code' => 999, 'message_text' => 'Data Not found.' );

		return $response->withJson( $res, 200 );	 


}

function sivic_issue_script( Request $request, Response $response )
{
	$db = database();
	$data = json_decode($request->getBody());
		
	if (isset($data->issue_id))
	$issue_id = $data->issue_id;
	$sSQL = "SELECT * FROM script WHERE issue_id= '$issue_id' AND script_status='Active'";
	$result = $db->get_results( $sSQL );
	if ( isset( $result ) && !empty( $result ) )
	$res = array( 'message_code' => 1000, 'message_text' => $result );
	else
	$res = array( 'message_code' => 999, 'message_text' => 'Script Not found.');

	return $response->withJson( $res, 200 );	
}


function sivic_resivic_for_sivic( Request $request, Response $response ){

	$url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

	$db = new Model();
	
    $res = $db->resivicForSivic($url);

    return $response->withJson($res, 200);

}





