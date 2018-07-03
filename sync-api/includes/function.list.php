<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


function gender_list( Request $request, Response $response )
{
	$res = array( 'message_code' => 999, 'message_text' => 'Functional part is commented.' );
	$db = database();
	$base_query = "SELECT code_master_id,code_name,code_value  
                    FROM code_master WHERE code_type='GenderType'";
	

	$result = $db->get_results( $base_query );
	if ( isset( $result ) && !empty( $result ) )
		$res = array( 'message_code' => 1000, 'message_text' => $result );
	else if ( $result == null )
		$res = array( 'message_code' => 999, 'message_text' => 'No currencies for the region!. Please contact Nafex support team.' );
	else 
		$res = array( 'message_code' => 999, 'message_text' => 'Unable to load currencies. Please contact Nafex support team.' );
		
	return $response->withJson( $res, 200 );
}

function sivic_issue_list( Request $request, Response $response )
{
	$res = array( 'message_code' => 999, 'message_text' => 'Functional part is commented.' );
	$db = database();
	
	$sSQL = "SELECT * FROM issue_master WHERE status='Active' ORDER By issue_id DESC";

	    $result = $db->get_results( $sSQL );

	   	if ( isset( $result ) && !empty( $result ) )
			$res = array( 'message_code' => 1000, 'message_text' => $result );
		else
			$res = array( 'message_code' => 999, 'message_text' => 'Data Not found.' );
		
		return $response->withJson( $res, 200 );
}



function sivic_influencer_list( Request $request, Response $response )
{
	$res = array( 'message_code' => 999, 'message_text' => 'Functional part is commented.' );
	$db = database();
	
	$sSQL="SELECT influencers.influencer_id as influencer_id,influencers.name as name,influencers.post as post,influencers.email as email,influencers.profile_pic_url as profile_pic_url,influencers.zipcode as zipcode,influencers.city as city,influencers.state as state,influencers.dob as dob,influencers.phone as phone,influencers.lattitude as lattitude,influencers.longitude as longitude,influencers.status as status,issue_master.issue_name as issue_name,issue_master.issue_id as issue_id,issues_influencers.issue_influencer_id as issue_influencer_id FROM `influencers` 
	INNER JOIN issues_influencers ON influencers.influencer_id=issues_influencers.influencer_id 
	INNER JOIN issue_master ON issue_master.issue_id=issues_influencers.issue_id WHERE influencers.influencer_status='Active' ORDER BY influencers.influencer_id DESC
	 ";

	    $result = $db->get_results( $sSQL );
	   	if ( isset( $result ) && !empty( $result ) )
			$res = array( 'message_code' => 1000, 'message_text' => $result );
		else
			$res = array( 'message_code' => 999, 'message_text' => 'Data Not found.' );
		
		return $response->withJson( $res, 200 );	 
}

function sivic_political_level_list( Request $request, Response $response )
{
	$res = array( 'message_code' => 999, 'message_text' => 'Functional part is commented.' );
	$db = database();
	
		$sSQL = "SELECT *  FROM code_master WHERE code_type='PoliticalLevel' ";
	    $result = $db->get_results( $sSQL );
	   	if ( isset( $result ) && !empty( $result ) )
			$res = array( 'message_code' => 1000, 'message_text' => $result );
		else
			$res = array( 'message_code' => 999, 'message_text' => 'Data Not found.' );
		
		return $response->withJson( $res, 200 );	 
}

function sivic_users_list( Request $request, Response $response )
{
	$res = array( 'message_code' => 999, 'message_text' => 'Functional part is commented.' );
	$db = database();
	
	$sSQL = "SELECT *  FROM users WHERE status='Active'";
	    $result = $db->get_results( $sSQL );
	   	if ( isset( $result ) && !empty( $result ) )
			$res = array( 'message_code' => 1000, 'message_text' => $result );
		else
			$res = array( 'message_code' => 999, 'message_text' => 'Users Not found.' );
		
		return $response->withJson( $res, 200 );	 
}

function sivic_script_list( Request $request, Response $response )
{
	$res = array( 'message_code' => 999, 'message_text' => 'Functional part is commented.' );
	$db = database();
	
	$sSQL = "SELECT script.script_id as script_id, script.script as script,issue_master.issue_name as issueName,issue_master.issue_id as issue_id FROM script
	INNER JOIN issue_master ON issue_master.issue_id=script.issue_id 
	WHERE script.script_status='Active'  ORDER BY script.script_id DESC";
	    $result = $db->get_results( $sSQL );
	   	if ( isset( $result ) && !empty( $result ) )
			$res = array( 'message_code' => 1000, 'message_text' => $result );
		else
			$res = array( 'message_code' => 999, 'message_text' => 'Data Not found.' );
		
		return $response->withJson( $res, 200 );	 
}


function sivic_list( Request $request, Response $response )
{
	$res = array( 'message_code' => 999, 'message_text' => 'Functional part is commented.' );
	$db = database();
	
	$sSQL = "SELECT sivic.headline as headline,sivic.shortcode as shortcode,code_master.code_name as code_name,issue_master.issue_name as issue_name FROM sivic
	INNER JOIN code_master ON sivic.reply_status_id=code_master.code_master_id
	INNER JOIN issue_master ON sivic.issue_id=issue_master.issue_id ";
	    $result = $db->get_results( $sSQL );
	   	if ( isset( $result ) && !empty( $result ) )
			$res = array( 'message_code' => 1000, 'message_text' => $result );
		else
			$res = array( 'message_code' => 999, 'message_text' => 'Data Not found.' );
		
		return $response->withJson( $res, 200 );	 
}

