<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

 
function sivic_admin_login( Request $request, Response $response )
{
	$res = array( 'message_code' => 999, 'message_text' => 'Functional part is commented.' );

	$db = database();
	$body = json_decode($request->getBody());
	// $body =  json_decode($request->getParsedBody());
	
	
	if (isset($body->AdminEmail))
		$AdminEmail = $body->AdminEmail;

	$AdminPassword = $body->AdminPassword;

	if ( ( ($AdminEmail == null) || ($AdminEmail=="") ) )
		$res = array( 'message_code' => 999, 'message_text' => 'Please provide your Email for login.');
	else if ( ($AdminPassword == null) || ($AdminPassword=="") )
		$res = array( 'message_code' => 999, 'message_text' => 'Please provide your Password for login.');
	else
	{

		$Where  = "";
		$Where  = " email ='" . $AdminEmail . "'";
		$cnt = $db->get_var("SELECT count(admin_id) FROM `admin` WHERE " . $Where);
	
	   if ($cnt == 0)
		    $res = array( 'message_code' => 999, 'message_text' => 'This email is not registered with Sivic. Please contact Sivic support team.');
        else
        {
        	
        	$cnt = $db->get_var("SELECT count(admin_id) FROM `admin` WHERE " . $Where . " and password='" . $AdminPassword . "'");
	        if ($cnt == 0)
		     $res = array( 'message_code' => 999, 'message_text' => 'Password does not match. Please contact Sivic support team.');
            else
            {
				
                $base_query = "SELECT * FROM `admin` WHERE " . $Where . " and password ='" . $AdminPassword . "'";
               // print_r($base_query);exit();
                $result = $db->get_results( $base_query );
	            if ( isset( $result ) && !empty( $result ) )
	            {
	            	
	            	$res = array( 'message_code' => 1000, 'message_text' => $result );
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

function sivic_issue(Request $request, Response $response)
{
			$res = array( 'message_code' => 999, 'message_text' => 'Functional part is commented.' );
			$db = database();
			$body = json_decode($request->getBody());

			$issueName = $body->issueName;
			$image = $body->imagePreviewUrl;
			$description = $body->description;
			$created_by= $body->adminId;
			$modified_by= $body->adminId;

			//$issueOrder = $body->issueOrder;

			 if ( ($issueName == null) || ($issueName == ""))
		    {
		    	$res = array( 'message_code' => 999, 'message_text' => 'Please provide Issue Name');
		    }
		
			else
			{
				if ($image == NULL OR $image == '') {
					$img = blob_to_image_dummy();
				}
				else {
					$img = blob_to_image($image);
				}
                
				$base_query = "INSERT INTO issue_master (issue_name,image,description,created_by,modified_by) 
				VALUES ('" . $issueName . "', '" . $img . "', '" . $description . "', '" . $created_by . "', '" . $modified_by . "')";
			    //print_r($base_query);exit();
				if($db->query($base_query))
		        {
		          $res = array('message_code' => 1000, 'message_text' => 'Record Inserted Succesfully.');
		        }
		        else
		        {
				  $res = array( 'message_code' => 999, 'message_text' => 'Database error! Request insertion failed.' );
				}	
			}
			 
		return json_encode($res,200);
} 


function sivic_influencers(Request $request, Response $response)
{
		$res = array( 'message_code' => 999, 'message_text' => 'Functional part is commented.' );
		$db = database();
		$body = json_decode($request->getBody());

		$issue_name=$body->issue_name;
		$name = $body->name;
		$post = $body->post;
		// $political_level = $body->political_level; Not required
		$email = $body->email;
		$image = $body->imagePreviewUrl;
		$modalCity = $body->modalCity;
		$modalState = $body->modalState;
		$dob = $body->dob;
		$phone = $body->phone;
		$zipcodeModal=$body->zipcodeModal;
		$lat = $body->lat;
		$long = $body->long;

		$created_by= $body->adminId;
		$modified_by= $body->adminId;

		if ( ($name == null) || ($name == ""))
	    {
	    	$res = array( 'message_code' => 999, 'message_text' => 'Please provide influencers Name');
	    }
	
	     if ( ($post == null) || ($post == ""))
	    {
	    	$res = array( 'message_code' => 999, 'message_text' => 'Please provide Post Name');
	     }
	    else if ( ($email == null) || ($email == ""))
	    {
	    	$res = array( 'message_code' => 999, 'message_text' => 'Please provide Email-id');
	    }
	     else if ( ($modalCity == null) || ($modalCity == ""))
	    {
	    	$res = array( 'message_code' => 999, 'message_text' => 'Please provide City Name');
	    }
	     else if ( ($modalState == null) || ($modalState == ""))
	    {
	    	$res = array( 'message_code' => 999, 'message_text' => 'Please provide State Name');
	    }
	     else if ( ($phone == null) || ($phone == ""))
	    {
	    	$res = array( 'message_code' => 999, 'message_text' => 'Please provide Post Name');
	    }
		else
		{
			if ($image == NULL OR $image == '') {
				$img = blob_to_image_dummy();
			}
			else {
				$img = blob_to_influencer_img($image);
			}
			
			$base_query = "INSERT INTO influencers (name,post,email,profile_pic_url,zipcode,city,state,dob,phone,lattitude,longitude,created_by,modified_by) 
			VALUES ('" . $name . "', '" . $post . "','" . $email . "', '" . $img . "','" . $zipcodeModal . "', '" . $modalCity . "', '" . $modalState . "', '" . $dob . "', '" . $phone . "', '" . $lat . "', '" . $long . "', '" . $created_by . "','" . $modified_by . "')";
		    // print_r($base_query);exit();
			if($db->query($base_query))
	        {
	        	$lastInsertId = $db->insert_id;

	        	$base_query = "INSERT INTO issues_influencers (issue_id,influencer_id) VALUES ('" . $issue_name . "', '" . $lastInsertId . "')";
		    	//print_r($base_query);exit();
		    	 $result = $db->query( $base_query );
	            if ( isset( $result ) && !empty( $result ) )
	            {
	            	
	            	$res = array( 'message_code' => 1000, 'message_text' => 'Record Inserted Succesfully.' );
	            }

	            else
		        {
		        	 $res = array( 'message_code' => 999, 'message_text' => 'Database error! Request insertion failed.' );
		        }
	        }
	        else
	        {
			  $res = array( 'message_code' => 999, 'message_text' => $base_query );
			}	
		}
		 
	return json_encode($res,200);
} 


/*13April*/
function sivic_AutoScript(Request $request, Response $response)
{
		$res = array( 'message_code' => 999, 'message_text' => 'Functional part is commented.' );
		$db = database();
		$body = json_decode($request->getBody());

		$issue_name = $body->issue_name;
		$description = $body->content;
		
		 if ( ($issue_name == null) || ($issue_name == ""))
	    {
	    	$res = array( 'message_code' => 999, 'message_text' => 'Please provide Issue Name');
	    }
	
	    else if ( ($description == null) || ($description == ""))
	    {
	    	$res = array( 'message_code' => 999, 'message_text' => 'Please provide Description');
	    }
		else
		{

			$base_query = "INSERT INTO script (issue_id,script) VALUES ('" . $issue_name . "', '" . $description . "')";
		    //print_r($base_query);exit();
			if($db->query($base_query))
	        {
	          $res = array('message_code' => 1000, 'message_text' => 'Record Inserted Succesfully.');
	        }
	        else
	        {
			  $res = array( 'message_code' => 999, 'message_text' => 'Database error! Request insertion failed.' );
			}	
		}
	       
		 
	return json_encode($res,200);
} 

