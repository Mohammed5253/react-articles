<?php

		
$CASE_REQUEST_TYPE = "CASE WHEN requestType = 1 THEN 'Buy1' WHEN requestType = 2 THEN 'Sell1' ELSE 'Remittance' END AS requestTypeName";

$CASE_DELEIVERY_MODE = "CASE WHEN requestDeliveryMode = 1 THEN 'Home Delivery' ELSE 'Pick Up' END AS 	requestDeliveryModeName";

$CASE_LEAD_SOURCE = "CASE WHEN requestLeadSourceId = 1 THEN 'Portal' WHEN requestLeadSourceId = 2 THEN 'Justdial'WHEN requestLeadSourceId = 3 THEN 'MMT' WHEN requestLeadSourceId = 4 THEN 'Google'WHEN requestLeadSourceId = 5 THEN 'Whatsapp' WHEN requestLeadSourceId = 6 THEN 'Now Floats' WHEN requestLeadSourceId = 7 THEN 'BNI' WHEN requestLeadSourceId = 8 THEN 'Referral'
	WHEN requestLeadSourceId = 9 THEN 'Les Concierges' WHEN requestLeadSourceId = 10 THEN 'Facebook'WHEN requestLeadSourceId = 11 THEN 'Trip Shelf' ELSE 'Test' END AS requestLeadSourceName";

$CASE_REQUEST_STATUS ="CASE WHEN requestStatusId = 1 THEN 'Open' WHEN requestStatusId = 2 THEN 'Converted'WHEN requestStatusId = 3 THEN 'Expired' WHEN requestStatusId = 4 THEN 'Open for FFMC'WHEN requestStatusId = 5 THEN 'Accepted' WHEN requestStatusId = 6 THEN 'Waiting' 
	WHEN requestStatusId = 7 THEN 'Waiting for Call Center User Respond' WHEN requestStatusId = 8 THEN 'Waiting for Call Center Admin User Respond' WHEN requestStatusId = 9 THEN 'Waiting for Call Center User Respond' WHEN requestStatusId = 10 THEN 'Waiting for Call Center Admin User Respond' WHEN requestStatusId = 11 THEN 'Rejected By Call Center User' WHEN requestStatusId = 12 THEN 'Rejected By Account' ELSE 'Deleted' END AS requestStatusName";	
$CASE_PRODUCT_TYPE = "CASE WHEN requestProductTypeId = 1 THEN 'Card' WHEN requestProductTypeId = 2 THEN 'Cash' WHEN requestProductTypeId = 3 THEN 'Remittance' WHEN requestProductTypeId = 4 THEN 'Card Reload' ELSE 'Card Encashment' END AS requestProductTypeName";



function database()
{
	return new ezSQL_mysqli( USER, PASSWORD, DATABASE, HOST );
}

function blob_to_image($blob){

	define('UPLOAD_DIR', '../../adminImg/issue/');
	$img = $blob;
	$img = str_replace('data:image/jpeg;base64,', '', $img);
	$data = base64_decode($img);
	$file = UPLOAD_DIR . uniqid() . '.png';
	$success = file_put_contents($file, $data);
	$data1[] = substr($file, 6);
	
	return $data1[0]	;

}

function blob_to_image_sivic($blob){

	define('UPLOAD_DIR', '../sivic-medias/');
	$img = $blob;
	$img = str_replace('data:image/jpeg;base64,','', $img);
	$data = base64_decode($img);
	$file = UPLOAD_DIR.uniqid().'.png';
	$success = file_put_contents($file, $data);
	$data1[] = substr($file,3);
	
	 return $data1[0];

}

function blob_to_influencer_img($blob){

	define('UPLOAD_DIR', '../../adminImg/influencer/');
	$img = $blob;
	$img = str_replace('data:image/jpeg;base64,','', $img);
	$data = base64_decode($img);
	$file = UPLOAD_DIR.uniqid().'.png';
	$success = file_put_contents($file, $data);
	$data1[] = substr($file,6);
	
	 return $data1[0];

}
function blob_to_image_dummy(){

	define('UPLOAD_DIR', '../../adminImg/dummy.png');
	
	return substr(UPLOAD_DIR,6);

}

function generate_token( $userId, $userRole, $userDevice )
{
	$db = database();
	$api_key = hash( 'sha256', ( time() . $userId . md5( uniqid( rand(), true ) ) . rand() ) );
	$created_on = date( 'Y-m-d H:i:s' );
	$db->query( 'DELETE FROM tblUserSessions WHERE userId = ' . $userId . ' and userDeviceDetails="' . $userDevice . '"' );

	$sSQL = 'INSERT INTO tblUserSessions (userId, userRole, userToken, userDeviceDetails, createdBy,  	lastModifiedBy) VALUES (' . $userId . ',' . $userRole . ',"' . $api_key . '", "' . $userDevice .'",1,1)';
	
	$db->query( $sSQL );
	
	return $api_key;
}

function verify_token( $userId, $token, $userDevice )
{
	$db = database();
	$sSQL = 'SELECT * FROM tblUserSessions WHERE userId = ' . $userId . ' and userDeviceDetails="' . $userDevice . '"'; 
	//echo $sSQL;
	$user_keys = $db->get_row( $sSQL);

	if( $user_keys->userToken == $token )
	{
		if( 1 ) // token expired check
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}

function random_password( $length = 8 )
{
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $password = substr( str_shuffle( $chars ), 0, $length );
    return $password;
}


// function send_password( $name, $email, $password )
// {
// 	$to = $email;
// 	$subject = "Your password reset link at Nafex.com!";
 	
//  	$message = 'Dear ' . $name . ',<br /><br />';
// 	$message .= 'Your reset password link is ' . $password . '<br /><br />';
// 	$message .= 'The MyTheater Team';

 
// 	$header = "From:My Theater Admin <support@mytheater.com> \r\n";
// 	//$header .= "Cc:afgh@somedomain.com \r\n";
// 	$header .= "MIME-Version: 1.0\r\n";
// 	$header .= "Content-type: text/html\r\n";

// 	$result = mail( $to, $subject, $message, $header );
// }

// function sendConfirmationMail( $name, $email )
// {
// 		$to = $email;
// 		$subject = "Welcome to My Theater!";
         
// 		$message = "Dear " . $name . ",<br />\r\n<br />\r\n";
// 		$message .= "Your registration is now complete. Please login with your email address and password and enjoy the app.<br/>\r\n<br />\r\n Thanks and Regards <br />\r\n My Theater Admin";
         
// 		$header = "From:My Theater Admin <support@mytheater.com> \r\n";
// 		$header .= "MIME-Version: 1.0\r\n";
// 		$header .= "Content-type: text/html\r\n";
// 		//ini_set('smtp_user','mallika@giftjeenie.com');
// 		//ini_set('smtp_pass','Makhijani07');
// 		$result = mail( $to, $subject, $message, $header );
// }


// function send_confirmation_mail( $name, $email )
// {
// 	$to = $email;
// 	$subject = "Welcome to My Theater!";
     
// 	$message = "Dear " . $name . ",<br />\r\n";
// 	$message .= "Your registration is now complete.\r\n";
     
// 	$header = "From:ajitem@joshiinc.com \r\n";
// 	//$header .= "Cc:afgh@somedomain.com \r\n";
// 	$header .= "MIME-Version: 1.0\r\n";
// 	$header .= "Content-type: text/html\r\n";

// 	$result = mail( $to, $subject, $message, $header );
// }

// function send_verification_mail( $url,$name,$email,$user_key )
// {
// 	$to = $email;
// 	$subject = "Welcome to My Theater!";
     
// 	$message = "Dear " . $name . ",<br />\r\n";
// 	$message .= "Your registration is now completed, To verify your email address please click here. \r\n";
// 	$message .= "http://mytheater.theapptest.xyz/user/verify/" . $user_key;
     
// 	$header = "From:My Theater Admin <support@mytheater.com> \r\n";
// 	$header .= "MIME-Version: 1.0\r\n";
// 	$header .= "Content-type: text/html\r\n";

// 	$result = mail( $to, $subject, $message, $header );
// }


function get_current_user_id( $request )
{
	$headers = $request->getHeaders();
	return $headers['PHP_AUTH_USER'][0];
}

function get_current_user_token( $request )
{
	$headers = $request->getHeaders();
	return $headers['PHP_AUTH_PW'][0];
}	


function get_current_user_role( $request )
{
	$headers = $request->getHeaders();
	$db = database();
	$role = $db->get_var('SELECT userRole FROM tblUserSessions WHERE id = ' . $headers['PHP_AUTH_USER'][0] );
	return $role;
}


function cmp($a, $b)
{
    return ( $b->percentage - $a->percentage );
}

function getexpirytime($expiry_date)
{

	$expiry_date = new DateTime($expiry_date);
	$date_today = new DateTime("now");

	$difference = date_diff( $expiry_date, $date_today );

	//return $difference->format('%h hour(s) %i min(s)');
	return $difference->format('%h:%i');
}

function array_utf8_encode($dat)
{
    if (is_string($dat))
        return utf8_encode($dat);
    if (!is_array($dat))
        return $dat;
    $ret = array();
    foreach ($dat as $i => $d)
        $ret[$i] = array_utf8_encode($d);
    return $ret;
}

function time_passed($timestamp)
{
    //type cast, current time, difference in timestamps
    $timestamp      = (int) $timestamp;
    $current_time   = time();
    $diff           = $current_time - $timestamp;
    
    //intervals in seconds
    $intervals      = array (
        'year' => 31556926, 'month' => 2629744, 'week' => 604800, 'day' => 86400, 'hour' => 3600, 'minute'=> 60
    );
    
    //now we just find the difference
    if ($diff == 0)
    {
        return 'just now';
    }    

    if ($diff < 60)
    {
        return $diff == 1 ? $diff . ' second ago' : $diff . ' seconds ago';
    }        

    if ($diff >= 60 && $diff < $intervals['hour'])
    {
        $diff = floor($diff/$intervals['minute']);
        return $diff == 1 ? $diff . ' minute ago' : $diff . ' minutes ago';
    }        

    if ($diff >= $intervals['hour'] && $diff < $intervals['day'])
    {
        $diff = floor($diff/$intervals['hour']);
        return $diff == 1 ? $diff . ' hour ago' : $diff . ' hours ago';
    }    

    if ($diff >= $intervals['day'] && $diff < $intervals['week'])
    {
        $diff = floor($diff/$intervals['day']);
        return $diff == 1 ? $diff . ' day ago' : $diff . ' days ago';
    }    

    if ($diff >= $intervals['week'] && $diff < $intervals['month'])
    {
        $diff = floor($diff/$intervals['week']);
        return $diff == 1 ? $diff . ' week ago' : $diff . ' weeks ago';
    }    

    if ($diff >= $intervals['month'] && $diff < $intervals['year'])
    {
        $diff = floor($diff/$intervals['month']);
        return $diff == 1 ? $diff . ' month ago' : $diff . ' months ago';
    }    

    if ($diff >= $intervals['year'])
    {
        $diff = floor($diff/$intervals['year']);
        return $diff == 1 ? $diff . ' year ago' : $diff . ' years ago';
    }
}


// function send_welcome_password( $name, $email, $password )
// {
// 	$to = $email;
// 	$subject = "Your Registration is successful at Nafex.com!";
 	
//  	$message = 'Dear ' . $name . ',<br /><br />';
// 	$message .= 'Your password is ' . $password . '<br /><br />';
// 	$message .= 'Registration of Nafex User';

 
// 	$header = "From:Nafex Admin <info@nafex.com> \r\n";
// 	//$header .= "Cc:afgh@somedomain.com \r\n";
// 	$header .= "MIME-Version: 1.0\r\n";
// 	$header .= "Content-type: text/html\r\n";

// 	$result = mail( $to, $subject, $message, $header );
// }


// function GetLocationIds($AreaString, $AreaLat, $AreaLong)
// {	
// 	$db = database();
// 	if ( ($AreaString == null) || ($AreaString=="") )
// 		$res = array( 'message_code' => 999, 'message_text' => 'Please provide Area.');
// 	else
// 	{
// 	    $Data = explode(",",$AreaString);
// 	    $len = sizeOf($Data);
	    
// 	    //echo $AreaString . "<br/>";
// 	    if ($len >= 5)
// 	    {
// 		    $Country = strtoupper(trim($Data[$len-1])); //4
// 	    	$State= strtoupper(trim($Data[$len-2])); //3
// 	    	$City= strtoupper(trim($Data[$len-3])); //2
// 	    	$Area = "";
// 	    	for ($i=0;$i<$len-3;$i++) 
// 	        	$Area = $Area . $Data[$i];
// 	    }
// 		else if ($len == 4)
// 	    {
// 		    $Country = strtoupper(trim($Data[3])); //3
// 	    	$State= strtoupper(trim($Data[2])); //2
// 	    	$City= strtoupper(trim($Data[1])); //1
// 	    	$Area = strtoupper($Data[0]);
// 	    }
// 		else if ($len == 3)
// 	    {
// 		    $Country = strtoupper(trim($Data[2])); //2
// 	    	$State= strtoupper(trim($Data[1])); //1
// 	    	$City= strtoupper(trim($Data[0])); //0
// 	    	$Area = strtoupper($Data[0]);
// 	    }
// 		else if ($len == 2)
// 	    {
// 		    $Country = strtoupper(trim($Data[1])); //1
// 	    	$State= strtoupper(trim($Data[0])); //0
// 	    	$City= strtoupper(trim($Data[0])); //0
// 	    	$Area = strtoupper($Data[0]);
// 	    }
// 		else if ($len <=1)
// 	  	    $res = array( 'message_code' => 999, 'message_text' => 'Please provide valid Area.');
	  
//         $CountryId = 0;
// 	    $cnt = $db->get_var("SELECT count(countryId) FROM `tblCountry` WHERE UCASE(countryName)='" . $Country . "'");
// 	    if ($cnt == 0)
// 	    {
// 	        $base_query = "INSERT INTO tblCountry(countryName) VALUES ('" . $Country . "')";
// 	        $result = $db->get_results( $base_query );
// 	        $CountryId = $db->insert_id;
// 	    }
// 	    else
// 	        $CountryId = $db->get_var("SELECT countryId FROM `tblCountry` WHERE UCASE(countryName)='" . $Country . "'");
	        
//         //echo "Country:" . $CountryId . "-" . $Country . "<br/>";

//         $StateId = 0;
//         $cnt = $db->get_var("SELECT count(stateId) FROM `tblState` WHERE UCASE(stateName)='" . $State . "' and countryId=" . $CountryId);
// 	    if ($cnt == 0)
// 	    {
// 	        $base_query = "INSERT INTO tblState(stateName, countryId) VALUES ('" . $State . "'," . $CountryId . ")";
// 	        $result = $db->get_results( $base_query );
// 	        $StateId = $db->insert_id;
// 	    }
// 	    else
// 	        $StateId  = $db->get_var("SELECT stateId FROM `tblState` WHERE UCASE(stateName)='" . $State . "' and countryId=" . $CountryId);
	        
//         //echo "State:" . $StateId . "-" . $State . "<br/>";


//         $CityId = 0;
//         $cnt = $db->get_var("SELECT count(cityId) FROM `tblCity` WHERE UCASE(cityName)='" . $City . "' and countryId=" . $CountryId . " and stateId=" . $StateId);
// 	    if ($cnt == 0)
// 	    {
// 	        $base_query = "INSERT INTO tblCity(cityName, stateid, countryId) VALUES ('" . $City . "'," . $StateId . "," . $CountryId . ")";
// 	        $result = $db->get_results( $base_query );
// 	        $CityId = $db->insert_id;
// 	    }
// 	    else
// 	        $CityId  = $db->get_var("SELECT cityId FROM `tblCity` WHERE UCASE(cityName)='" . $City . "' and countryId=" . $CountryId . " and stateId=" . $StateId);
//         //echo "City:" . $CityId . "-" . $City . "<br/>";

        
//         $AreaId = 0;
//         $cnt = $db->get_var("SELECT count(areaId) FROM `tblArea` WHERE UCASE(areaName)='" . $Area . "' and countryId=" . $CountryId . " and stateId=" . $StateId . " and cityId=" . $CityId);
// 	    if ($cnt == 0)
// 	    {
// 	        $base_query = "INSERT INTO tblArea(areaName, cityId, stateId, countryId, areaLatitute, areaLongitute) VALUES ('" . $Area . "'," . $CityId . "," . $StateId . "," . $CountryId . ",'" . $AreaLat . "','" . $AreaLong . "')";
// 	        $result = $db->get_results( $base_query );
// 	        $AreaId = $db->insert_id;
// 	    }  
// 	    else
// 	        $AreaId  = $db->get_var("SELECT areaId FROM `tblArea` WHERE UCASE(areaName)='" . $Area . "' and countryId=" . $CountryId . " and stateId=" . $StateId . " and cityId=" . $CityId);
// 	    //echo "Area:" . $AreaId . "-" . $Area . "<br/>";

// 		$res = array('message_code' => 1000, 'message_text' => array('countryId' => $CountryId, 'stateId' => $StateId, 'cityId' => $CityId, 'areaId' => $AreaId )  );

// 	}
	
//     return json_encode($res);

// }

function GetLocationIds($AreaString, $AreaLat, $AreaLong)
{	
	$db = database();
	if ( ($AreaString == null) || ($AreaString=="") )
		$res = array( 'message_code' => 999, 'message_text' => 'Please provide Area.');
	else
	{
	    $Data = explode(",",$AreaString);
	    $len = sizeOf($Data);
	    
	    //echo $AreaString . "<br/>";
	    if ($len >= 5)
	    {
		    $Country = trim($Data[$len-1]); //4
	    	$State= trim($Data[$len-2]); //3
	    	$City= trim($Data[$len-3]); //2
	    	$Area = "";
	    	for ($i=0;$i<$len-3;$i++) 
	        	$Area = $Area . $Data[$i];
	    }
		else if ($len == 4)
	    {
		    $Country = trim($Data[3]); //3
	    	$State= trim($Data[2]); //2
	    	$City= trim($Data[1]); //1
	    	$Area = $Data[0];
	    }
		else if ($len == 3)
	    {
		    $Country = trim($Data[2]); //2
	    	$State= trim($Data[1]); //1
	    	$City= trim($Data[0]); //0
	    	$Area = $Data[0];
	    }
		else if ($len == 2)
	    {
		    $Country = trim($Data[1]); //1
	    	$State= trim($Data[0]); //0
	    	$City= trim($Data[0]); //0
	    	$Area = $Data[0];
	    }
		else if ($len <=1)
	  	    $res = array( 'message_code' => 999, 'message_text' => 'Please provide valid Area.');
	  
        $CountryId = 0;
	    $cnt = $db->get_var("SELECT count(countryId) FROM `tblCountry` WHERE UCASE(countryName)=UCASE('" . $Country . "')");
	    if ($cnt == 0)
	    {
	        $base_query = "INSERT INTO tblCountry(countryName) VALUES ('" . $Country . "')";
	        $result = $db->get_results( $base_query );
	        $CountryId = $db->insert_id;
	    }
	    else
	        $CountryId = $db->get_var("SELECT countryId FROM `tblCountry` WHERE UCASE(countryName)=UCASE('" . $Country . "')");
	        
        //echo "Country:" . $CountryId . "-" . $Country . "<br/>";

        $StateId = 0;
        $cnt = $db->get_var("SELECT count(stateId) FROM `tblState` WHERE UCASE(stateName)=UCASE('" . $State . "') and countryId=" . $CountryId);
	    if ($cnt == 0)
	    {
	        $base_query = "INSERT INTO tblState(stateName, countryId) VALUES ('" . $State . "'," . $CountryId . ")";
	        $result = $db->get_results( $base_query );
	        $StateId = $db->insert_id;
	    }
	    else
	        $StateId  = $db->get_var("SELECT stateId FROM `tblState` WHERE UCASE(stateName)=UCASE('" . $State . "') and countryId=" . $CountryId);
	        
        //echo "State:" . $StateId . "-" . $State . "<br/>";


        $CityId = 0;
        $cnt = $db->get_var("SELECT count(cityId) FROM `tblCity` WHERE UCASE(cityName)=UCASE('" . $City . "') and countryId=" . $CountryId . " and stateId=" . $StateId);
	    if ($cnt == 0)
	    {
	        $base_query = "INSERT INTO tblCity(cityName, stateid, countryId) VALUES ('" . $City . "'," . $StateId . "," . $CountryId . ")";
	        $result = $db->get_results( $base_query );
	        $CityId = $db->insert_id;
	    }
	    else
	        $CityId  = $db->get_var("SELECT cityId FROM `tblCity` WHERE UCASE(cityName)=UCASE('" . $City . "') and countryId=" . $CountryId . " and stateId=" . $StateId);
        //echo "City:" . $CityId . "-" . $City . "<br/>";

        
        $AreaId = 0;
        $cnt = $db->get_var("SELECT count(areaId) FROM `tblArea` WHERE UCASE(areaName)=UCASE('" . $Area . "') and countryId=" . $CountryId . " and stateId=" . $StateId . " and cityId=" . $CityId);
	    if ($cnt == 0)
	    {
	        $base_query = "INSERT INTO tblArea(areaName, cityId, stateId, countryId, areaLatitute, areaLongitute) VALUES ('" . $Area . "'," . $CityId . "," . $StateId . "," . $CountryId . ",'" . $AreaLat . "','" . $AreaLong . "')";
	        $result = $db->get_results( $base_query );
	        $AreaId = $db->insert_id;
	    }  
	    else
	        $AreaId  = $db->get_var("SELECT areaId FROM `tblArea` WHERE UCASE(areaName)=UCASE('" . $Area . "') and countryId=" . $CountryId . " and stateId=" . $StateId . " and cityId=" . $CityId);
	    //echo "Area:" . $AreaId . "-" . $Area . "<br/>";

		$res = array('message_code' => 1000, 'message_text' => array('countryId' => $CountryId, 'stateId' => $StateId, 'cityId' => $CityId, 'areaId' => $AreaId )  );

	}
	
    return json_encode($res);

}

function get_name( $type, $id)
{
	$db = database();
	//echo 'SELECT codeName FROM tblCodeMaster WHERE codeValue = "' . $id .'" AND codeType = "'. $type .'"'; die();
	$Name = $db->get_var( 'SELECT codeName FROM tblCodeMaster WHERE codeValue = "' . $id .'" AND codeType = "'. $type .'"' );
	//$res = array('message_code' => 1000, 'message_text' => $result);

	return $Name;
}

function get_name_switch($type, $id)
{
	$Name = "";
	if ($type == "REQUESTTYPE")
	{
		if ($id == 1) $Name = "Buy";
		else if ($id==2) $Name = "Sell";
		else if ($id ==3) $Name = "Money Transfer";
	}
	else if ($type == "DELIVERYTYPES")
	{
		if ($id == 1) $Name = "Home Delivery";
		else if ($id==2) $Name = "Pick up";
		
	}
	else if ($type == "LEADSOURCE")
	{
		if ($id == 1) $Name = "Web Portal Call Center";
		else if ($id==2) $Name = "Justdial";
		else if ($id==3) $Name = "MMT";
		else if ($id==4) $Name = "Google";
		else if ($id==5) $Name = "Whatsapp";
		else if ($id==6) $Name = "Now Floats";
		else if ($id==7) $Name = "BNI";
		else if ($id==8) $Name = "Referral";
		else if ($id==9) $Name = "Les Concierges";
		else if ($id==10) $Name = "Facebook";
		else if ($id==11) $Name = "Trip Shelf";
		else if ($id==12) $Name = "Mobile App";
		else if ($id==13) $Name = "Test";
		else if ($id==14) $Name = "Repeat";
		else if ($id==15) $Name = "Walk-In";
		else if ($id==16) $Name = "Google Adwords";
	}
	else if ($type == "B2BSOURCE")
	{
		if ($id == 1) $Name = "Web Portal Call Center";
		else if ($id==2) $Name = "Web Portal FFMC";
		else if ($id==3) $Name = "Mobile App Android";
		else if ($id==4) $Name = "Mobile App iOS";
		else if ($id==5) $Name = "Test";
	}
	else if ($type == "REQUESTSTATUS")
	{
		if ($id == 1) $Name = "Open";
		else if ($id==2) $Name = "Converted";
		else if ($id==3) $Name = "Expired";
		else if ($id==4) $Name = "Open";
		else if ($id==5) $Name = "Accepted";
		else if ($id==6) $Name = "Waiting";
		else if ($id==7) $Name = "Waiting for Call Center User Respond";
		else if ($id==8) $Name = "Waiting for Call Center Admin User Respond";
		else if ($id==9) $Name = "Waiting for Call Center User Respond";
		else if ($id==10) $Name = "Waiting for Call Center Admin User Respond";
		else if ($id==11) $Name = "Rejected By Call Center User";
		else if ($id==12) $Name = "Rejected By Account";
		else if ($id==13) $Name = "Deleted";
	}
	else if ($type == "DISPUTEREASONSC")
	{
		
		if ($id == 1) $Name = "NBC Already Generated";
		else if ($id==2) $Name = "No Forex Requirement";
		else if ($id==3) $Name = "Transaction Completed Already";
		else if ($id==4) $Name = "Restricted Currency/Coins";
		else if ($id==5) $Name = "Ringing No Response";
		else if ($id==6) $Name = "Disconnecting the Call";
		else if ($id==7) $Name = "Number Switch Off";
		else if ($id==8) $Name = "Future Follow Up";
		else if ($id==9) $Name = "No Money Changer Listed";
		else if ($id==10) $Name = "Demo Lead";
		else if ($id==11) $Name = "Duplicate Enquiry";
		else if ($id==12) $Name = "Wrong Number";
		else if ($id==13) $Name = "Others";
		
	}
	else if ($type == "DISPUTEREASONSN")
	{
		if ($id == 1) $Name = "Unhappy Rate";
		else if ($id==2) $Name = "Future Follow Up";
		else if ($id==3) $Name = "Duplicate Enquiry";
		else if ($id==4) $Name = "Done with Other Money Changer";
		else if ($id==5) $Name = "Ringing No Response";
		else if ($id==6) $Name = "Disconnecting the Call";
		else if ($id==7) $Name = "Number Switch Off";
		else if ($id==8) $Name = "Documents Issue";
		else if ($id==9) $Name = "Wrong Transaction Type";
		else if ($id==10) $Name = "Payment Mode";
		else if ($id==11) $Name = "Delivery Issue";
		else if ($id==12) $Name = "Demo Lead";
		else if ($id==13) $Name = "Restricted Currency/Coins";
		else if ($id==14) $Name = "Wrong Number";
		else if ($id==15) $Name = "Others";
		
	} 	
	else if ($type == "PRODUCTTYPE")
	{
		if ($id == 1) $Name = "Forex Card";
		else if ($id==2) $Name = "Currency Notes";
		else if ($id==3) $Name = "Forex Card Reload";
		else if ($id==4) $Name = "Forex Card Encashment";
		//else if ($id==5) $Name = "Card Encashment";
	}
	else if ($type == "PRODUCTTYPESHORT")
	{
		if ($id == 1) $Name = "Card";
		else if ($id==2) $Name = "Notes";
		else if ($id==3) $Name = "Card Reload";
		else if ($id==4) $Name = "Card Encash";
		//else if ($id==5) $Name = "Card Encashment";
	} 
	else if ($type == "REMITTANCETRANSFER")
	{
		if ($id == 1) $Name = "Wire / Telegraphic Transfer (TT)";
		else if ($id==2) $Name = "Demand Draft";
	}
	return $Name;
}

function get_currency_name( $id ){
	$db = database();
	$Name = $db->get_var( 'SELECT currencyAbbrevation FROM tblCurrencies WHERE id = ' . $id);
	return $Name;
}

function get_area_name( $countryId,$stateId,$cityId,$areaId )
{
	//echo $countryId; die();
	$db = database();
	if($countryId != 0)
		$countryName = $db->get_var( 'SELECT countryName FROM tblCountry WHERE countryId = ' . $countryId);
	if($stateId != 0)
		$stateName = $db->get_var( 'SELECT stateName FROM tblState WHERE stateId = ' . $stateId .'
			 And countryId = '. $countryId);
	if($cityId !=0)
		$cityName = $db->get_var( 'SELECT cityName FROM tblCity WHERE cityId = ' . $cityId .' And stateId = ' . $stateId . ' And  countryId = ' . $countryId);
	if($areaId !=0)
		$areaName = $db->get_var( 'SELECT areaName FROM tblArea WHERE areaId = ' . $areaId .' And countryId = ' . $countryId . ' And stateId = ' . $stateId);

	$res = array('countryName' => $countryName, 'stateName' => $stateName, 'cityName' => $cityName, 'areaName' => $areaName );

	return $res;
}

function get_class_name_switch($requestTypeId, $requestSourceRefId, $requestLeadSourceId, $requestDeliveryMode, $ProductType)
{
	if ($requestTypeId == 1) $requestTypeIdClass = "buy";
	else if ($requestTypeId==2) $requestTypeIdClass = "sell";
	else if ($requestTypeId ==3) $requestTypeIdClass = "money transfer";

	 
	if ($requestDeliveryMode == 1) $requestDeliveryModeClass = "bgBidDeliv";
	else if ($requestDeliveryMode==2) $requestDeliveryModeClass = "bgBidPick";
		
	
	if ($requestSourceRefId == 1 || $requestSourceRefId == 2 || $requestSourceRefId == 4)
	{
		if ($requestLeadSourceId == 1) $requestLeadSourceIdClass = "cTypePortal";
		else if ($requestLeadSourceId==2) $requestLeadSourceIdClass = "cTypeJD";
		else if ($requestLeadSourceId==3) $requestLeadSourceIdClass = "cTypeMMT";
		else if ($requestLeadSourceId==4) $requestLeadSourceIdClass = "cTypeGoogle";
		else if ($requestLeadSourceId==5) $requestLeadSourceIdClass = "cTypeWhatsapp";
		else if ($requestLeadSourceId==6) $requestLeadSourceIdClass = "cTypeNowFloats";
		else if ($requestLeadSourceId==7) $requestLeadSourceIdClass = "cTypeBNI";
		else if ($requestLeadSourceId==8) $requestLeadSourceIdClass = "cTypeRefferal";
		else if ($requestLeadSourceId==9) $requestLeadSourceIdClass = "cTypeLesCon";
		else if ($requestLeadSourceId==10) $requestLeadSourceIdClass = "cTypeFaceBook";
		else if ($requestLeadSourceId==11) $requestLeadSourceIdClass = "cTypeTripShelf";
		else if ($requestLeadSourceId==12) $requestLeadSourceIdClass = "cTypeMobile";
		else if ($requestLeadSourceId==13) $requestLeadSourceIdClass = "cTypeTest";
		// $$ms20180108
		else if ($requestLeadSourceId==14) $requestLeadSourceIdClass = "cTypeRepeat";
		else if ($requestLeadSourceId==15) $requestLeadSourceIdClass = "cTypeWalkIn";
		else if ($requestLeadSourceId==16) $requestLeadSourceIdClass = "cTypeGoogleAdword";
		else $requestLeadSourceIdClass = "";
	}
	else
	{
		if ($requestLeadSourceId == 1) $requestLeadSourceIdClass = "cTypePortal";
		else if ($requestLeadSourceId==2) $requestLeadSourceIdClass = "cTypeFFMC";
		else if ($requestLeadSourceId==3) $requestLeadSourceIdClass = "cTypeAndroid";
		else if ($requestLeadSourceId==4) $requestLeadSourceIdClass = "cTypeIOS";
		else if ($requestLeadSourceId==5) $requestLeadSourceIdClass = "cTypeTest";
		else $requestLeadSourceIdClass = "";
		
	}

	if ($requestSourceRefId == 1) $requestSourceRefIdClass = "customerName";
	else if ($requestSourceRefId==2) $requestSourceRefIdClass = "fmccName";
	else if ($requestSourceRefId==6) $requestSourceRefIdClass = "fmccName";
	else $requestSourceRefIdClass ="";
	
	if (($ProductType == "Currency Notes") && ($requestTypeId !=3)) $ProductTypeClass = "bgBidProCash";
	else if (($ProductType == "Currency Notes") && ($requestTypeId ==3)) $ProductTypeClass = "bgBidProMoney";
	else if ($ProductType == "Forex Card") $ProductTypeClass = "bgBidProCard";
	else if ($ProductType == "Forex Card Reload") $ProductTypeClass = "bgBidProCard";
	else if ($ProductType == "Forex Card Encashment") $ProductTypeClass = "bgBidProCard";
	else if ($ProductType == "Cash+Card") $ProductTypeClass = "bgBidProDbl";
	else $ProductTypeClass = "";

	$res = array('requestTypeIdClass' => $requestTypeIdClass, 'requestDeliveryModeClass' => $requestDeliveryModeClass, 'requestLeadSourceIdClass' => $requestLeadSourceIdClass, 'requestSourceRefIdClass' => $requestSourceRefIdClass, 'ProductTypeClass'  => $ProductTypeClass);

	return json_encode($res);	

}

 function get_country_name( $id )
 {
 	$db = database();
 	$Name = $db->get_var( 'SELECT countryName FROM tblCountry WHERE countryId = ' . $id);
	return $Name;
 }

 function get_moneytransfercountry_name( $id )
 {
 	$db = database();
 	$Name = $db->get_var( 'SELECT countryName FROM tblCountryMoneyTransfer WHERE countryId = ' . $id);
	return $Name;
 }

  function is_valid_mobile_no( $mobileNo )
 {
 	$db = database();
 	$sSQL = "SELECT count(*) FROM tblInvalidMobileNumbers WHERE userMobileNumber='" . $mobileNo . "'";
 	//echo $sSQL . "<br/>";
 	$cnt = $db->get_var($sSQL);
 	
 	//echo $cnt . "<br/>";
	if ((int)$cnt > 0 )
		return false;
 	
	return true;
 }

 function distance($lat1, $lon1, $lat2, $lon2, $unit='KM')
 {
	$theta = $lon1 - $lon2;
	$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
	$dist = acos($dist);
	$dist = rad2deg($dist);
	$miles = $dist * 60 * 1.1515;
	$unit = strtoupper($unit);

	if ($unit == "KM") {
	    return ($miles * 1.609344);
	} else if ($unit == "N") {
	      return ($miles * 0.8684);
	  } else {
	        return $miles;
	    }
}

function gst_calculation($rate = null, $quantity, $remittanceCharges, $otherCharges, $deliveryCharges)
{
	$db = database();
    $slabs = [
        1000000,
        100000
    ];
    $taxInstance =  $db->get_var( 'SELECT percentage FROM tblTaxMaster WHERE taxname = "IGST"');
	$taxValue = 0.18;
    if ($taxInstance !== null) {
        $taxValue = $taxInstance/100;
    }
   
    $quantity = (int)$quantity;
    $roundPrecision = 2;
    if ($rate > 0) {
        //$total_rate = round($rate * $quantity, $roundPrecision);
        $total_rate = $rate;
        $taxRate = 0;
        if ($total_rate > $slabs[0]) {
            $taxRate = (($total_rate - $slabs[0]) * (0.1 / 100)) + 5500;
        } elseif (($total_rate > $slabs[1])) {
            $taxRate = (($total_rate - $slabs[1]) * (0.5 / 100)) + 1000;
        } elseif ($total_rate <= $slabs[1]) {
            $taxRate = max(round($total_rate / 100), 250);
        }
        //echo $taxRate; die();

        // delivery charges calculations start
        $gstDeliveryCharge = 0.00;
        $gstOtherCharges = 0; 
        $gstRemittanceCharge = 0;
	        if($deliveryCharges != 0)
	        {
	        	$gstDeliveryCharge = ceil($deliveryCharges * $taxValue);
	        }else{
	        	$gstDeliveryCharge = 0;
	        }
	       
	    	if($otherCharges != 0)
	        {
	        	$gstOtherCharges = ceil($otherCharges * $taxValue);
	        }else{
	        	$gstOtherCharges = 0;
	        }
	  
	        if($remittanceCharges != 0)
	        {
	        	$gstRemittanceCharge = ceil($remittanceCharges * $taxValue);
	        }else{
	        	$gstRemittanceCharge = 0;
	        }
        // delivery charges calculations end
	        //echo $gstDeliveryCharge; die();
        $forexGst = ceil(($taxRate * $taxValue)* 10000) / 10000;
        $totalGst = ceil(($forexGst + $gstDeliveryCharge + $gstRemittanceCharge + $gstOtherCharges) * 10000) / 10000;
        $totalGst = ceil($totalGst);

        return  $totalGst;
    }
}

function get_ffmc_area_name($id)
{
	$db = database();
 	$Name = $db->get_var( 'SELECT areaName FROM tblArea WHERE areaId = ' . $id);
	return $Name;
}

function get_remaining_seconds($remaining)
{
	$arr = explode(":", $remaining);
	if ($arr[0] ==="-00")
		$remaining = "-" . ( ((int)$arr[1] * 60) + (int)$arr[2]); 
	else if ($arr[0]=== "00") {
		if ((int)$arr[1] <=30 )
			$remaining = "+" . ( ((int)$arr[1] * 60) + (int)$arr[2]); 
		else
			$remaining =  ( ((int)$arr[1] * 60) + (int)$arr[2]); 
	}
	else
		$remaining = (( (int)$arr[0] * 60) * 60) + ((int)$arr[1] * 60) + (int)$arr[2]; 

	return $remaining;
}

function get_lead_source_class($requestSourceRefId, $requestLeadSourceId){
	
	if ($requestSourceRefId == 1 || $requestSourceRefId == 2 || $requestSourceRefId == 4)
	{
		if ($requestLeadSourceId == 1) $requestLeadSourceIdClass = "cTypePortal";
		else if ($requestLeadSourceId==2) $requestLeadSourceIdClass = "cTypeJD";
		else if ($requestLeadSourceId==3) $requestLeadSourceIdClass = "cTypeMMT";
		else if ($requestLeadSourceId==4) $requestLeadSourceIdClass = "cTypeGoogle";
		else if ($requestLeadSourceId==5) $requestLeadSourceIdClass = "cTypeWhatsapp";
		else if ($requestLeadSourceId==6) $requestLeadSourceIdClass = "cTypeNowFloats";
		else if ($requestLeadSourceId==7) $requestLeadSourceIdClass = "cTypeBNI";
		else if ($requestLeadSourceId==8) $requestLeadSourceIdClass = "cTypeRefferal";
		else if ($requestLeadSourceId==9) $requestLeadSourceIdClass = "cTypeLesCon";
		else if ($requestLeadSourceId==10) $requestLeadSourceIdClass = "cTypeFaceBook";
		else if ($requestLeadSourceId==11) $requestLeadSourceIdClass = "cTypeTripShelf";
		else if ($requestLeadSourceId==12) $requestLeadSourceIdClass = "cTypeMobile";
		else if ($requestLeadSourceId==13) $requestLeadSourceIdClass = "cTypeTest";
		// $$ms20180108
		else if ($requestLeadSourceId==14) $requestLeadSourceIdClass = "cTypeRepeat";
		else if ($requestLeadSourceId==15) $requestLeadSourceIdClass = "cTypeWalkIn";
		else if ($requestLeadSourceId==16) $requestLeadSourceIdClass = "cTypeGoogleAdword";
		else $requestLeadSourceIdClass = "";
	}
	else
	{
		if ($requestLeadSourceId == 1) $requestLeadSourceIdClass = "cTypePortal";
		else if ($requestLeadSourceId==2) $requestLeadSourceIdClass = "cTypeFFMC";
		else if ($requestLeadSourceId==3) $requestLeadSourceIdClass = "cTypeAndroid";
		else if ($requestLeadSourceId==4) $requestLeadSourceIdClass = "cTypeIOS";
		else if ($requestLeadSourceId==5) $requestLeadSourceIdClass = "cTypeTest";
		else $requestLeadSourceIdClass = "";
		
	}

	return $requestLeadSourceIdClass;
}


function LocateFFMCs($requestId, $strProduct1, $strProduct2, $requestLat, $requestLong,$productMessage, $currencyId, $requestTypeId, $NBCnumber, $requestSourceRefId)
{
	//echo "hello"; die();
	$db = database();

	if($strProduct1 != 3 && $strProduct1 !=4)
	{
	$sSQL = 'INSERT INTO tblVirtualFFMC(requestId, FFMCBranchId, FFMCCompanyId, FFMCName, FFMCBranchLevel, FFMCSMSNotifications, SMSTOMobile, FFMCEMAILNotifications, EMAILTO, BIDPermission, FFMCCommisionRateId, FFMCDeliveryDistance, distanceInKM, FFMCAreaId, FFMCBranchCityId, FFMCBranchStateId, FFMCBranchLatitute, FFMCBranchLongitute, FFMCContactMobile, FFMCContactEmail, RemittanceCharges, FFMCOtherCharges, FFMCDeliveryCharges1, FFMCDeliveryCharges2, FFMCDeliveryCharges3, FFMCReqShortCode, watermarkRate) SELECT ' . $requestId. ', p.FFMCBranchId, p.FFMCCompanyId, o.FFMCBranchName, FFMCBranchLevel, FFMCSMSNotifications, SMSTOMobile, FFMCEMAILNotifications, EMAILTO, BIDPermission, FFMCCommisionRateId, FFMCDeliveryDistance,0, FFMCAreaId, FFMCBranchCityId, FFMCBranchStateId, FFMCBranchLatitute, FFMCBranchLongitute, c.FFMCContactMobile, FFMCContactEmail, RemittanceCharges, FFMCOtherCharges, FFMCDeliveryCharges1, FFMCDeliveryCharges2, FFMCDeliveryCharges3, "",0.00 FROM tblFFMCProducts as p, tblFFMCOffice as o, tblFFMCAddressData as a,  tblFFMCContact as c, tblFFMCDeliveryCharges as d WHERE d.FFMCBranchId = p.FFMCBranchId and d.FFMCCompanyId= p.FFMCCompanyId and c.FFMCOperationalId = p.FFMCBranchId and c.FFMCCompanyId= p.FFMCCompanyId and a.FFMCOperationalId = p.FFMCBranchId and a.FFMCCompanyId= p.FFMCCompanyId  and o.FFMCBranchId = p.FFMCBranchId and o.FFMCCompanyId= p.FFMCCompanyId and o.isActive=1 ';


		if ($requestTypeId == "3")
			$sSQL = $sSQL . ' and p.ProductId = 5 '; // 5 is the Money Transfer product ID from FFMC Profile
		else if (($requestSourceRefId == 3) || ($requestSourceRefId == 5) || ($requestSourceRefId == 6))
			$sSQL = $sSQL . ' and p.ProductId = 6 '; // 6 is the B2B Prodcut ID from FFMC
		else
			$sSQL = $sSQL . ' and p.ProductId = ' . $strProduct1;

 		if ($strProduct2 !="") //two products
			$sSQL = $sSQL . ' and p.FFMCBranchId in (SELECT distinct FFMCBranchId FROM tblFFMCProducts WHERE ProductId = ' . $strProduct2 . ')';
	//echo $sSQL;die();	


	$db->query($sSQL);	

	$sSQL = "DELETE FROM tblVirtualFFMC WHERE FFMCBranchId in (SELECT FFMCBranchId from tblFFMCOffice where FFMCCompanyId in (SELECT FFMCCompanyId from tblFFMCOffice where FFMCBranchId in (SELECT bu.FFMCBranchId FROM `tblUser` as u, tblBackendUsers as bu, tblRequest as r where bu.userId = u.userReferenceId and u.userId = r.requestUserId and r.requestId=" . $requestId . "))";

	$db->query($sSQL);		

	$sSQL = "SELECT FFMCBranchId, FFMCBranchLatitute, FFMCBranchLongitute, FFMCDeliveryDistance from tblVirtualFFMC WHERE requestId= " . $requestId;
 	$result = $db->get_results( $sSQL );

 	$strBranchIds ="";
 	foreach ($result as $var) {

 		if (distance($var->FFMCBranchLatitute, $var->FFMCBranchLongitute, $requestLat, $requestLong ) > $var->FFMCDeliveryDistance)
		{
			if ($strBranchIds !="") $strBranchIds = $strBranchIds .",";
			$strBranchIds = $strBranchIds . $var->FFMCBranchId;
		}
	}

	$sSQL = "DELETE from tblVirtualFFMC WHERE requestId= " . $requestId . " and FFMCBranchId in (" . $strBranchIds . ")";
	$db->query($sSQL);	

	$sSQL = "UPDATE tblVirtualFFMC as f, tblFFMCCompany as c SET f.FFMCName = c.FFMCCompany WHERE c.companyId=  f.FFMCCompanyId and (f.FFMCName='' or f.FFMCName = null)";
	$db->query($sSQL);	


	$sSQLs = "SELECT v.FFMCBranchId, FFMCBranchLatitute, FFMCBranchLongitute, v.FFMCDeliveryDistance, FFMCContactMobile, FFMCEmail, FFMCSms from tblVirtualFFMC as v , tblFFMCOffice as o WHERE v.FFMCBranchId = o.FFMCBranchId and requestId= " . $requestId;
 	$result = $db->get_results( $sSQLs );

 	foreach ($result as $var) {
 		$FFMCReqShortCode = random_password();

 		$distance =  distance($var->FFMCBranchLatitute, $var->FFMCBranchLongitute, $requestLat, $requestLong);

 	// wartermark logik	
 		$sSQL= 'SELECT rb.averageRate as Rate from tblRequest as r, tblRequestBids as rb WHERE rb.bidRequestId = r.requestId and rb.bidFFMCId = ' . $var->FFMCBranchId . ' and DateDiff(rb.createdOn, CURDATE()) = 0 ';

		if (($requestTypeId == "1") || ($requestTypeId == "3"))
			$sSQL= $sSQL . ' and requestTargetCurrencyId=' . $currencyId;
		else if ($requestTypeId == "2")
			$sSQL= $sSQL . ' and requestSourceCurrencyId=' . $currencyId; 
			
		$sSQL= $sSQL . ' and r.requestType=' . $requestTypeId . ' order by rb.createdOn desc limit 1';
		//echo $sSQL; die();

//SELECT * FROM `tblVirtualFFMC` where requestId in (Select requestId from tblRequest where requestNBC = 11984

		$watermarkRate = 0;
		$result = $db->get_results( $sSQL );
		if ( isset( $result ) && !empty( $result ) )
			$watermarkRate = $result[0]->Rate;

		if ($watermarkRate == null || $watermarkRate == "" || intval($watermarkRate) == 0 || $watermarkRate == 0)
		{

			// if ($requestTypeId == "1")
			// 	$sSQL = "SELECT buyRate ";
			// else if ($requestTypeId == "2")
			// 	$sSQL = "SELECT sellRate ";
			// else if ($requestTypeId == "3")
			// 	$sSQL = "SELECT buyRate ";
			//As per customer's feedback showing the RBI Rate
			$sSQL = "SELECT rbiRate ";
			
			$sSQL = $sSQL . " from tblCurrencies WHERE id = " . $currencyId;
				//echo $sSQL; die();
			$watermarkRate = $db->get_var($sSQL);
		}

		$sSQLs = 'Update `tblVirtualFFMC` set distanceInKM = '. $distance .', FFMCReqShortCode ="'. $FFMCReqShortCode .'", watermarkRate=' . $watermarkRate . ' WHERE FFMCBranchId = '. $var->FFMCBranchId .' And requestId = ' . $requestId;
		
		$db->query($sSQLs);	

		if ($var->FFMCSms == 1)
		{
			//For Forex Card Encashment and Reload there is no notifications to FFMC
			if (($strProduct1 != 3) && ($strProduct1 != 4))
			{
		        if (($FFMCReqShortCode != null) && ($FFMCReqShortCode!=""))
			    { 
			        $shortURLData = array('0' => $NBCnumber, '1' => $productMessage, '2' => $FFMCReqShortCode);

			        if (($requestSourceRefId == 3) || ($requestSourceRefId == 5) || ($requestSourceRefId == 6))
			        	$Message = PrepareMessage("REQUESTBIDB2B",$shortURLData);
			        else
			        	$Message = PrepareMessage("REQUESTBIDB2C",$shortURLData);  
			      
			         if (TESTorACTUAL == "TEST")
		             {
		             	//SendSMStoAPI("8793392836",$Message);
				        //SendSMStoAPI("9766079591",$Message);
				    	//SendSMStoAPI("9066002013",$Message);
				    
		            	if ($var->FFMCContactMobile == "9766079591")	
		            		SendSMStoAPI("9766079591",$Message);
		             } 
		            else
		          		SendSMStoAPI($var->FFMCContactMobile,$Message);
				}
			}
		}
	}
}	
	return "";
}


function getThreshold($requestType,$sourceCurrencyId,$targetCurrencyId)
{
	
	$db = database();
	if($requestType ==1 || $requestType ==3){
		$sSQL = "SELECT id,rbiRate,buyRate,sellRate FROM tblCurrencies WHERE currencyAbbrevation = '". $targetCurrencyId."'";
		$threshold = $db->get_results( $sSQL );
		if ( isset( $threshold ) && !empty( $threshold ) )
        {
			$res = array( 'minRate' => $threshold[0]->rbiRate, 'maxRate'=>$threshold[0]->buyRate);
		}else{
			$res = array( 'minRate' => '', 'maxRate'=>'');
		}
	}else{
		$sSQL = "SELECT id,rbiRate,buyRate,sellRate FROM tblCurrencies WHERE currencyAbbrevation = '". $sourceCurrencyId."'";
		$threshold = $db->get_results( $sSQL );
		if ( isset( $threshold ) && !empty( $threshold ) )
        {
		$res = array( 'minRate' => $threshold[0]->sellRate, 'maxRate'=>$threshold[0]->buyRate);
		}else{
			$res = array( 'minRate' => '', 'maxRate'=>'');
		}
	}

	return $res;
	
}

 function getFFMCBalance($ffmcBranchId)
 {
 	$db = database();
 	
 	$newBalance = $db->get_var("SELECT NewBalance FROM tblFFMCTransactions WHERE FFMCBranchId=". $ffmcBranchId . " ORDER BY id DESC LIMIT 0,1");

 	if (floatval($newBalance) <= 0.00)
 	{
 		 $data = array($newBalance);
 		 $Message = PrepareEmail("NEGATIVEBALANCE",$data);
		 $subject = "Nafex.com - Partners Support Team";

		 $FFMCContactEmail = $db->get_var("SELECT FFMCContactEmail FROM tblFFMCContact WHERE FFMCOperationalId=". $ffmcBranchId);

		 //$$SL20171215
		 $FFMCBranchName = $db->get_var("SELECT FFMCBranchName FROM tblFFMCOffice WHERE FFMCBranchId=". $ffmcBranchId);

		 if (TESTorACTUAL == "TEST")       
		 	$to = array('0' => 'nafex2test@gmail.com', '1' => "Nafex Test");
		 else
		    $to = array('0' => $FFMCContactEmail, '1' => $FFMCBranchName);

		 //SendEmailwithHeader($to, $subject, $Message,"NEGATIVEBALANCE");
 	}

 	return $newBalance;
 }

 function checkISTOfficeHrs()
 {
	$timezone = new DateTimeZone("Asia/Kolkata" );
	$date = new DateTime();
	$date->setTimezone($timezone);
	$epochNow = strtotime($date->format("Y-m-d h:i:s A"));

	$StartDateTime = $date->format("Y-m-d");
	$EndDateTime = $date->format("Y-m-d");
	

	//$epochStart = strtotime($StartDateTime . " 11:49:00 AM");
	//$epochEnd = strtotime($EndDateTime . " 00:01:00 PM");

	$epochStart = strtotime($StartDateTime . " 12:00:00 AM");
	$epochEnd = strtotime($EndDateTime . " 07:30:00 PM");

 	if (($epochStart<=$epochNow) && ($epochNow<=$epochEnd))
		return true;
	else
		return false;


	// $gmtDate = gmdate("Y-m-d");
	// $gmtStartTime= $gmtDate . " 02:30:00 AM"; // IST 8 AM;
	// $gmtEndTime= $gmtDate . " 02:30:00 PM"; // IST 8 PM;

	// //$gmtEndTime= $gmtDate . " 09:30:00 AM"; // IST Test Time 3 PM;

	// $epochStart = strtotime($gmtStartTime);
	// $epochEnd = strtotime($gmtEndTime);
	// $datagmt = array('gmtStartTime' => $gmtStartTime, 'gmtEndTime' => $gmtEndTime );
	// $dataist = array('start' => gmdate('r', $epochStart), 'now' => gmdate('r',$epochNow), 'end' => gmdate('r', $epochEnd));

	// $res = array( 'GMT' => $datagmt, 'IST' => $dataist);

	// return $res;
 }

 function getRequestB2CB2B($requestSourceRefId)
{
	$Prefix = "NBC";

	if ($requestSourceRefId ==1 || $requestSourceRefId ==2 || $requestSourceRefId ==4)
		$Prefix = "NBC";		 
	else if ($requestSourceRefId ==3 || $requestSourceRefId ==5 || $requestSourceRefId ==6)
		$Prefix = "NBB";

	return $Prefix;

}
/*27/02/2018 iqbal*/
function poultry_get_chicken_boiler_info()
{
	$res = array( 'message_code' => 999, 'message_text' => 'Functional part is commented.' );
	$db = database();
		
		 $sSQL = "SELECT SUM(receiveBirds) as receivedBirdTotal,SUM(birdsWeight) as receivedBirdWtTotal FROM tblReceiveChickenBoiler where DATE(receiveDate)=CURDATE()";
	    $result = $db->get_results( $sSQL );
	   	if ( isset( $result ) && !empty( $result ) )
			$res = $result ;
		else
			$res =  'No Data found.' ;	
		//return $response->withJson( $res, 200 );
		return $res;
}
/*end 27/02/2018 iqbal*/

/*27/02/2018 iqbal*/
function poultry_get_chicken_issue_boiler_info()
{
	$res = array( 'message_code' => 999, 'message_text' => 'Functional part is commented.' );
	$db = database();
		$res=$db->get_results("SELECT issueBirds,birdsWeight,issueDate FROM tblIssueChickenBoiler");
		$selectedChicks = array();
		$issue_birds='';
		$birds_wt='';

		foreach ($res as  $value) {
		        	 	$timestamp=$value->issueDate;
		        	 	//print_r($timestamp);
						$TT =  gmdate("Y-m-d", $timestamp);
					//	print_r($TT);echo "<br>";
			        	$today = date("Y-m-d");
			        // print_r($today);echo "<br>";
			        	if($today == $TT){
			        		$value->Birds = $value->issueBirds;
			        		$value->Weight = $value->birdsWeight;
			        		//print_r($value);
			        	array_push($selectedChicks, $value);
			       }
			      // exit();
		}
		 //print_r($selectedChicks);
			       foreach ($selectedChicks as $key => $value) 
			       {
			       		$issue_birds += $value->issueBirds;
			       		$birds_wt += 	$value->birdsWeight;
			       		//print_r($dd);
			       		//echo "<br>";
			       }
			       // print_r($birds_wt);
			       // print_r($issue_birds);
			       $boiler_info=array(
			       	'issue_birds'=>$issue_birds,
			       	'birds_wt'=>$birds_wt
			       );
		if ( isset( $boiler_info ) && !empty( $boiler_info ) )
			$res = $boiler_info;
		else
			$res =  'No Data found.' ;	       
		//return $response->withJson( $res, 200 );
		return $res;
   
}
/*end 27/02/2018 iqbal*/
/*28/02/2018 iqbal*/
function poultry_get_chicken_gavran_info()
{
	$res = array( 'message_code' => 999, 'message_text' => 'Functional part is commented.' );
	$db = database();
		
		 $sSQL = "SELECT SUM(receiveBirds) as receivedBirdTotal,SUM(birdsWeight) as receivedBirdWtTotal FROM tblReceiveChickenGavran where DATE(receiveDate)=CURDATE()";
	    $result = $db->get_results( $sSQL );
	   	if ( isset( $result ) && !empty( $result ) )
			$res = $result ;
		else
			$res =  'No Data found.' ;	
		//return $response->withJson( $res, 200 );
		return $res;
}
/*end 28/02/2018 iqbal*/

/*28/02/2018 iqbal*/
function poultry_get_chicken_issue_gavran_info()
{
	$res = array( 'message_code' => 999, 'message_text' => 'Functional part is commented.' );
	$db = database();
		$res=$db->get_results("SELECT issueBirds,birdsWeight,issueDate FROM tblIssueChickenGavran");
		$selectedChicks = array();
		$issue_birds='';
		$birds_wt='';

		foreach ($res as  $value) {
		        	 	$timestamp=$value->issueDate;
		        	 	//print_r($timestamp);
						$TT =  gmdate("Y-m-d", $timestamp);
					//	print_r($TT);echo "<br>";
			        	$today = date("Y-m-d");
			        // print_r($today);echo "<br>";
			        	if($today == $TT){
			        		$value->Birds = $value->issueBirds;
			        		$value->Weight = $value->birdsWeight;
			        		//print_r($value);
			        	array_push($selectedChicks, $value);
			       }
			      // exit();
		}
		 //print_r($selectedChicks);
			       foreach ($selectedChicks as $key => $value) 
			       {
			       		$issue_birds += $value->issueBirds;
			       		$birds_wt += 	$value->birdsWeight;
			       		//print_r($dd);
			       		//echo "<br>";
			       }
			       // print_r($birds_wt);
			       // print_r($issue_birds);
			       $gavran_info=array(
			       	'issue_birds'=>$issue_birds,
			       	'birds_wt'=>$birds_wt
			       );
		if ( isset( $gavran_info ) && !empty( $gavran_info ) )
			$res = $gavran_info;
		else
			$res =  'No Data found.' ;	       
		//return $response->withJson( $res, 200 );
		return $res;
   
}
/*end 28/02/2018 iqbal*/


/*29/02/2018 iqbal*/
function poultry_get_chicken_eggs_info()
{
	$res = array( 'message_code' => 999, 'message_text' => 'Functional part is commented.' );
	$db = database();
		
		 $sSQL = "SELECT SUM(receivedTray) as receivedTrayTotal,SUM(receiveQty) as receivedQtyTotal FROM tblReceiveChickenEggs where DATE(receiveDate)=CURDATE()";
	    $result = $db->get_results( $sSQL );
	   	if ( isset( $result ) && !empty( $result ) )
			$res = $result ;
		else
			$res =  'No Data found.' ;	
		//return $response->withJson( $res, 200 );
		return $res;
}
/*end 29/02/2018 iqbal*/


/*01/03/2018 iqbal*/
function poultry_get_chicken_issue_eggs_info()
{
	$res = array( 'message_code' => 999, 'message_text' => 'Functional part is commented.' );
	$db = database();
		$res=$db->get_results("SELECT issueQty,issueTray,issueDate FROM tblIssueEggs");
		$selectedChicks = array();
		$issue_qty='';
		$issue_tray='';

		foreach ($res as  $value) {
		        	 	$timestamp=$value->issueDate;
		        	 	//print_r($timestamp);
						$TT =  gmdate("Y-m-d", $timestamp);
					//	print_r($TT);echo "<br>";
			        	$today = date("Y-m-d");
			        // print_r($today);echo "<br>";
			        	if($today == $TT){
			        		$value->Birds = $value->issueQty;
			        		$value->Weight = $value->issueTray;
			        		//print_r($value);
			        	array_push($selectedChicks, $value);
			       }
			      // exit();
		}
		 //print_r($selectedChicks);
			       foreach ($selectedChicks as $key => $value) 
			       {
			       		$issue_qty += $value->issueQty;
			       		$issue_tray += 	$value->issueTray;
			       		//print_r($dd);
			       		//echo "<br>";
			       }
			       // print_r($issue_tray);
			       // print_r($issue_qty);
			       $eggs_info=array(
			       	'issue_qty'=>$issue_qty,
			       	'issue_tray'=>$issue_tray
			       );
		if ( isset( $eggs_info ) && !empty( $eggs_info ) )
			$res = $eggs_info;
		else
			$res =  'No Data found.' ;	       
		//return $response->withJson( $res, 200 );
		return $res;
   
}
