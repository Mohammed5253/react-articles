<?php

// require dirname( __DIR__ ) . "/config.php";

class Model
{
    private $connect;
    private $error_message;

    public function __construct()
    {

        $this->error_message = '';

        $this->connect = new mysqli(HOST, USER, PASSWORD, DATABASE);

        if ($this->connect->connect_errno)
        {
            $error_message = array("error" => "Failed to connect to MySQL: (" . $this->connect->connect_errno . ") " . $this->connect->connect_error);
        }
    }

    public function disconnect()
    {
        $this->connect->close();
        $this->connect = NULL;
    }

    public function userverifyemail($email)
    {

        if( $this->error_message != '' )
        {
            return $error_message;
        }
        $verify = "Yes";
        $stmt = $this->connect->prepare("UPDATE users SET email_verified = ? WHERE email = ?");

        $stmt->bind_param( 'ss', $verify, $email );

        if( $stmt->execute() )
        {
            return array( 'message_code' => 1000, 'message_text' => 'Success');
        }
        else
        {
            return array( 'message_code' => 999, 'message_text' => 'Failed');
        }
     
    }

    public function userIssues($userId, $issueId)
   {

     foreach ($issueId as  $value) {
        $stmt = $this->connect->prepare('INSERT INTO user_issues (user_id, issue_id) VALUES (?, ?)');
        if( $stmt ) {
           
            $stmt->bind_param('ii', $userId, $value);
            $stmt->execute();
            $stmt->close();

        }

	}
	
	return ['Data' => 'success'];
	
    }

    public function codeMaster()
    {

        $stmt = $this->connect->prepare('SELECT * FROM code_master');
        if($stmt) {
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($code_master_id, $code_type, $code_name, $code_value);

            $codemaster = [];

            while($stmt->fetch()) {
                $tmp = [];
                $tmp['code_master_id'] = $code_master_id;
                $tmp['code_type'] = $code_type;
                $tmp['code_name'] = $code_name;
                $tmp['code_value'] = $code_value;
                $codemaster[] = $tmp;
                $tmp = null;
            }

            return ['Code Master' => $codemaster];
        }
     
    }

    public function random_password( $length = 8 )
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $password = substr( str_shuffle( $chars ), 0, $length );
        while( ! (1 === preg_match('~[0-9]~', $password) ) )
        {
            $password = substr( str_shuffle( $chars ), 0, $length );
        }
        return $password;
    }

    public function changePassword($user_id, $old_password, $new_password, $confirm_new_password)
    {
        if ($new_password != $confirm_new_password )
        {
            $res = array( 'message_code' => 999, 'message_text' => 'Password and Confirm Password does not match' );
        }
        // elseif ($user_id == NULL AND $old_password == NULL AND $email != NULL) {
        //     echo "Hello";exit;
        // }
        else 
        {           
        $stmt = $this->connect->prepare('SELECT email FROM users WHERE user_id = ? and password = ?');

        if($stmt) 
            {
            $stmt->bind_param('is', $user_id, $old_password);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($email);

            if( $stmt->num_rows > 0 )
            {   
                $password = md5( $new_password );
                $stmt = $this->connect->prepare("UPDATE users SET password = ? WHERE user_id = ?");

                $stmt->bind_param( 'si', $password, $user_id );
        
                if( $stmt->execute() )
                {
                    $res = array( 'message_code' => 1000, 'message_text' => 'Success');
                }
                else
                {
                    $res = array( 'message_code' => 999, 'message_text' => 'Failed');
                }
            }
            else {
                $res = array( 'message_code' => 999, 'message_text' => 'Incorrect Old Password.');
            }

         
           }
        }
        return ['Response' => $res];
     
    }

    public function forgetPassword($email)
    {
     
        $stmt = $this->connect->prepare('SELECT user_id FROM users WHERE email = ?');

        if($stmt) 
            {
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($user_id);
            $stmt->fetch();

            if( $stmt->num_rows > 0 )
            {   
			$newpass = random_password(6);
            // $password = sha1( $email . ':' . $newpass );
 			// $password = password_hash($password, PASSWORD_BCRYPT);
            $password = md5( $newpass );

            $stmt = $this->connect->prepare("UPDATE users SET password = ? WHERE user_id = ?");
            $stmt->bind_param( 'si', $password, $user_id );
        
                if( $stmt->execute() )
                {
                    $email_encrypt_link="ss";
                    $emailData = array('0' => $email, "1"=>$email_encrypt_link, "2"=>$password);

                    $Message = PrepareEmail("FORGETPASSWORD",$emailData);
                    $subject = "FORGET PASSWORD MAIL - Sivic Team";
                    
                
                    $to = array('0' => $email, '1' => "Appwelt Test");
                    
                    
                    SendEmailwithHeader($to['0'], $to['1'], $subject, $Message, "FORGETPASSWORD","");

                    $res = array( 'message_code' => 1000, 'message_text' => 'Success');
                }
                else
                {
                    $res = array( 'message_code' => 999, 'message_text' => 'Failed');
                }
            }
            else {
                $res = array( 'message_code' => 999, 'message_text' => 'This email is not registered with Sivic.');
            }

         
           }
        
       return ['Response' => $res];
     
    }

    public function resetPassword($email, $new_password, $confirm_new_password)
    {

        if ($new_password != $confirm_new_password )
        {
            $res = array( 'message_code' => 999, 'message_text' => 'Password and Confirm Password does not match' );
        }
        else 
        {           
                $password = md5( $new_password);
                $email = base64_decode($email);
                $stmt = $this->connect->prepare("UPDATE users SET password = ? WHERE email = ?");

                $stmt->bind_param( 'ss', $password, $email );
        
                if( $stmt->execute() )
                {
                    $res = array( 'message_code' => 1000, 'message_text' => 'Success');
                }
                else
                {
                    $res = array( 'message_code' => 999, 'message_text' => 'Failed');
                }
            
         
           }
        
        return ['Response' => $res];
     
    }


    
     public function listSivicOnWall()
    { 

        $stmt = $this->connect->prepare('SELECT s.sivic_id,s.issue_id, s.headline, s.created_on, s.modified_on, u.first_name, u.last_name, u.profile
                                         FROM sivic as s 
                                         join users as u on s.user_id = u.user_id');

        if($stmt) 
            {
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($sivic_id,$issue_id, $headline, $created_on, $modified_on, $user_first_name, $user_last_name, $user_profile);
            $dataSivic = [];
            $datawhole = array();

            while($stmt->fetch()) {
                $dataSivic=null;
                $tmp = [];
                $tmp['sivic_id'] = $sivic_id;
                $tmp['issue_id'] = $issue_id;
                $tmp['headline'] = $headline;
                $tmp['created_on'] = $created_on;
                $tmp['modified_on'] = $modified_on;
                $tmp['user_first_name'] = $user_first_name;
                $tmp['user_last_name'] = $user_last_name;
                $tmp['user_profile'] = $user_profile;

                //Issue
                $query = $this->connect->prepare('SELECT issue_id,issue_name FROM issue_master where issue_id = ?');

                    $query->bind_param('i', $issue_id);
                    $query->execute();
                    $query->store_result();
                    $query->bind_result($id, $issue_name);

                    while($query->fetch()){
                        $tmp1 = [];
                        $tmp1['id'] = $id;
                        $tmp1['issue_name'] = $issue_name;
                        $tmp['issue'] = $tmp1;
                        $tmp1 = null;
                    };
                    // Issue

                     //Influencer Connected with Sivic
                     $query = $this->connect->prepare('SELECT influencer_id FROM sivic_influencers where sivic_id = ?');

                     $query->bind_param('i', $sivic_id);
                     $query->execute();
                     $query->store_result();
                     $query->bind_result($influencers_id);
                     $dataInfluencer = [];
                   
                     while($query->fetch()) {
     
                         $tmp2 = [];
                         $tmp2['influencer_id'] = $influencers_id;
                         $dataInfluencer[] = $tmp2;
                         $tmp2 = null;
                     }
                         $dataInfluencers = null;
                         foreach ($dataInfluencer as $id) {
                         $iid = $id['influencer_id'];
 
                         $query = $this->connect->prepare('SELECT influencer_id, name, post, profile_pic_url  FROM influencers where influencer_id = ?');
                 
                                 $query->bind_param('i', $iid);
                                 $query->execute();
                                 $query->store_result();
                                 $query->bind_result($influencer_id, $name, $post, $profile_pic);
 
                                 while($query->fetch()) {
                                 $data = [];
                                 $data['influencer_id'] = $influencer_id;
                                 $data['name'] = $name;
                                 $data['post'] = $post;
                                 $data['profile_pic'] = $profile_pic;
                                 $dataInfluencers[] = $data;
                                 $data = null;
                                
                                 
                                 }
                                 
                         }
                         
                    $tmp['influencer'] = $dataInfluencers;     
                    //Influencer Ended

                    //Media Connected to Sivic
                    $query = $this->connect->prepare('SELECT url FROM sivic_medias where sivic_id = ?');

                    $query->bind_param('i', $sivic_id);
                    $query->execute();
                    $query->store_result();
                    $query->bind_result($url);
                    $dataMedia = [];
                  
                    while($query->fetch()) {
    
                        $data = [];
                                $data['url'] = $url;
                              
                                $dataMedia[] = $data;
                                $data = null;
                                }
                    $tmp['media'] = $dataMedia;           
                    //Media Ended  
 
                    $currentdate = strtotime (date ("Y-m-d"));
                    $sivictimestamp =abs( $currentdate - $modified_on);
                    $daysDifference = ceil($sivictimestamp/86400); 
    
                    $tmp['dateDiff'] = $daysDifference; 
    
                     
                        
                $dataSivic[] = $tmp;
                $tmp = null; 
                
                
                array_push( $datawhole, $dataSivic );        

                }
           
        }
        return ['Response' => $datawhole];
    }

    public function createSivic($data, $influencer_id, $media)
    {
    
        $headline = $data->headline;
        $shortcode = SIVICURL.random_password(11);
        $script = $data->script;
        $issue_id = $data->issue_id;
        $script_id = $data->script_id;
        $user_id = $data->user_id;
        $sivic_status_id = $data->sivic_status_id;
        $source_type_id = $data->source_type_id;
        $created_by = $data->user_id;
        $modified_by = $data->user_id;


         $stmt = $this->connect->prepare('INSERT INTO sivic (headline, shortcode, script, issue_id, script_id, user_id, sivic_status_id, source_type_id, created_by, modified_by) 
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
         if( $stmt ) {
            
             $stmt->bind_param('sssiiiiiii', $headline, $shortcode, $script, $issue_id, $script_id, $user_id, $sivic_status_id, $source_type_id, $created_by, $modified_by);

             if( !$stmt->execute() )
             {

                 return array( 'message_code' => 999, 'message_text' => 'Unable to post Sivic' );
             }

             $stmt->close();
             $sivic_id = $this->connect->insert_id;
             //Sivic Inserted

             //Influencer Sivic Insertion
            foreach ($influencer_id as  $value) {
                $stmt = $this->connect->prepare('INSERT INTO sivic_influencers (sivic_id, influencer_id) VALUES (?, ?)');
                if( $stmt ) {
            
                $stmt->bind_param('ii', $sivic_id, $value);
                if( !$stmt->execute() )
                {
                   return array('message_code' => 999, 'message_text' => 'Unable to add Influencers');
                }
                $stmt->close();
                }
            }
            //Influencer Sivic Insertion Completed

            //Media Sivic
            foreach ($media as  $url) {
                $imgurl = blob_to_image_sivic($url);
                $stmt = $this->connect->prepare('INSERT INTO sivic_medias (sivic_id, url) VALUES (?, ?)');
                if( $stmt ) {
            
                $stmt->bind_param('is', $sivic_id, $imgurl);
                if( !$stmt->execute() )
                {
                   return array('message_code' => 999, 'message_text' => 'Unable to add Medias');
                }
                $stmt->close();
                }
            }
            //Media Sivic Completed
        }
            return array('message_code' => 1000, 'sivic_id' => $sivic_id, 'shortcode' => $shortcode);
     
     }

     public function issueScript($issue_id)
    {

        $status = "Active";
        $stmt = $this->connect->prepare('SELECT script_id, script FROM script where issue_id = ? AND script_status = ?');
        if($stmt) {
            $stmt->bind_param('is', $issue_id, $status);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($script_id, $script);

            $scriptData = [];

            while($stmt->fetch()) {
                $tmp = [];
                $tmp['script_id'] = $script_id;
                $tmp['script'] = $script;
                $scriptData[] = $tmp;
                $tmp = null;
            }

            return ['Data' => $scriptData];
        }
     
    }


    public function searchInfluencer($searchname)
    {
        $stmt = $this->connect->prepare('SELECT influencer_id, name, profile_pic_url FROM influencers where UCASE(name) LIKE ?');
        if($stmt) {

            $name = '%'. strtoupper($searchname).'%';
            $name=preg_replace('/\s+/', '', $name);
            
            $stmt->bind_param('s', $name);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($influencer_id, $name, $profile_pic_url);

             $influencerSearch = [];

            while($stmt->fetch()) {
                $tmp = [];
                $tmp['influencer_id'] = $influencer_id;
                $tmp['name'] = $name;
                $tmp['profile_url'] = $profile_pic_url;
                $influencerSearch[] = $tmp;
                $tmp = null;
            }
        }

            return ['Data' => $influencerSearch];
     
    }



    public function socialLogin($data)
    { 

        $first_name = $data->fname;
        $last_name = $data->lname;
        $email = $data->email;
        $social_platform_id = $data->social_platform_id;
        $social_id = $data->provider_id;
        $profile = $data->provider_pic;
        $address = $data->address;
        $zipcode = $data->zipcode;
        $gender = $data->gender;
        $phone = $data->phone;
        $city = $data->city;
        $state = $data->state;
        $latitude = $data->lat;
        $longitude = $data->long;
        $token = $data->token;
        $token_type_id = $data->token_type_id;
        $email_verified = "Yes";

       
                $stmt = $this->connect->prepare('INSERT INTO users (first_name, last_name, email, gender_type_id, phone, social_platform_id, social_id, email_verified, profile, address, zipcode, city, state, latitude, longitude) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
                if( $stmt ) {

                    $stmt->bind_param('sssisiisssissii', $first_name, $last_name, $email, $gender, $phone, $social_platform_id, $social_id, $email_verified, $profile, $address, $zipcode, $city, $state, $latitude, $longitude);
       
                    if( !$stmt->execute() )
                    {
       
                        $res =  array( 'message_code' => 999, 'message_text' => 'Unable to register user' );
                        return $res;
                    }
       
                    $stmt->close();
                    $user_id = $this->connect->insert_id;
                }

                $query = $this->connect->prepare('INSERT INTO user_token (user_id, token, token_type_id) 
                VALUES (?, ?, ?)');
                if( $query ) {

                    $query->bind_param('isi', $user_id, $token, $token_type_id);
       
                    if( !$query->execute() )
                    {

                        $res =  'User registered but unable to add token' ;
                    }

                    $query->close();
                    $res =   'User registered and token added' ;
                }



                $res = array('message_code' => 1000,'message_text' => $user_id, 'message' => $res);

                return $res;
        }




  public function checkSocialLoginRegister($email)
    {

        if( $this->error_message != '' )
        {
            return $error_message;
        }

        $stmt = $this->connect->prepare('SELECT user_id, contacts_imported, interest_selected FROM users where email = ?');
            
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($user_id, $contacts_imported, $interest_selected);

            if( $stmt->num_rows > 0 ) {
                $flag = [];
                while($stmt->fetch()) {
                    $tmp = [];
                    $tmp['user_id'] = $user_id;
                    $tmp['contacts_imported'] = $contacts_imported;
                    $tmp['interest_selected'] = $interest_selected;
                    $tmp['email'] = $email;
                    $flag = $tmp;
                    $tmp = null;
                }
                $res = array('message_code' => 999, 'message_text' => $flag);

            }
            else
            {
                $res = array('message_code' => 1000, 'message_text' => 'Redirect to address form'); 
            }
            
            return $res;
     
    }

    


    public function updateFlagwhileRegister($data)
    {

        if( $this->error_message != '' )
        {
            return $error_message;
        }
   
        $email = $data->email;  
        
        

        $stmt = $this->connect->prepare('SELECT user_id FROM users where email = ?');
            
        $stmt->bind_param('s', $email);
       
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($user_id);
        $stmt->fetch();

        if ($data->email_verified) {

            $email_verified = $data->email_verified;
            $email = base64_decode($email);
            $stmt = $this->connect->prepare("UPDATE users SET email_verified = ? WHERE email = ?");

                $stmt->bind_param( 'ss', $email_verified, $email);
        
                if( $stmt->execute() )
                {
                    return array( 'message_code' => 1000, 'message_text' => 'Success');
                }
                else
                {
                    return array( 'message_code' => 999, 'message_text' => 'Failed');
                }
        }elseif ($data->contacts_imported) {

            $contacts_imported = $data->contacts_imported;
            $stmt = $this->connect->prepare("UPDATE users SET contacts_imported = ? WHERE email = ?");

                $stmt->bind_param( 'ss', $contacts_imported, $email);
        
                if( $stmt->execute() )
                {
                    return array( 'message_code' => 1000, 'message_text' => 'Success');
                }
                else
                {
                    return array( 'message_code' => 999, 'message_text' => 'Failed');
                }
        }elseif ($data->interest_selected) {
  
            $interest_selected = $data->interest_selected;
            
            $stmt = $this->connect->prepare("UPDATE users SET interest_selected = ? WHERE email = ?");

            $stmt->bind_param( 'ss', $interest_selected, $email);

            if( $stmt->execute() )
            {
                return array( 'message_code' => 1000, 'message_text' => 'Success');
            }
            else
            {
                return array( 'message_code' => 999, 'message_text' => 'Failed');
            }
        }else {
            return array('message_code' => 999, 'message_text' => 'Error'); 
        }
            
    }

    public function resivicForSivic($url)
    {

          $randomString = array_slice(explode('/', rtrim($url, '/')), -1)[0];
       
          $stmt = $this->connect->prepare('SELECT s.sivic_id,s.issue_id, s.headline, s.created_on, s.modified_on, s.script, u.first_name, u.last_name, u.profile
                                         FROM sivic as s 
                                         join users as u on s.user_id = u.user_id where s.shortcode = ?');

        if($stmt) 
            {
            $stmt->bind_param( 's', $url);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($sivic_id, $issue_id, $headline, $created_on, $modified_on, $script, $user_first_name, $user_last_name, $user_profile);
            $dataSivic = [];
            $datawhole = array();

            while($stmt->fetch()) {
                $dataSivic=null;
                $tmp = [];
                $tmp['sivic_id'] = $sivic_id;
                $tmp['issue_id'] = $issue_id;
                $tmp['headline'] = $headline;
                $tmp['created_on'] = $created_on;
                $tmp['modified_on'] = $modified_on;
                $tmp['script'] = $script;
                $tmp['user_first_name'] = $user_first_name;
                $tmp['user_last_name'] = $user_last_name;
                $tmp['user_profile'] = $user_profile;
                $tmp['random_url'] = $randomString;
                

                //Issue
                $query = $this->connect->prepare('SELECT issue_id,issue_name FROM issue_master where issue_id = ?');

                    $query->bind_param('i', $issue_id);
                    $query->execute();
                    $query->store_result();
                    $query->bind_result($id, $issue_name);

                    while($query->fetch()){
                        $tmp1 = [];
                        $tmp1['id'] = $id;
                        $tmp1['issue_name'] = $issue_name;
                        $tmp['issue'] = $tmp1;
                        $tmp1 = null;
                    };
                    // Issue

                     //Influencer Connected with Sivic
                     $query = $this->connect->prepare('SELECT influencer_id FROM sivic_influencers where sivic_id = ?');

                     $query->bind_param('i', $sivic_id);
                     $query->execute();
                     $query->store_result();
                     $query->bind_result($influencers_id);
                     $dataInfluencer = [];
                   
                     while($query->fetch()) {
     
                         $tmp2 = [];
                         $tmp2['influencer_id'] = $influencers_id;
                         $dataInfluencer[] = $tmp2;
                         $tmp2 = null;
                     }
                         $dataInfluencers = null;
                         foreach ($dataInfluencer as $id) {
                         $iid = $id['influencer_id'];
 
                         $query = $this->connect->prepare('SELECT influencer_id, name, post, profile_pic_url  FROM influencers where influencer_id = ?');
                 
                                 $query->bind_param('i', $iid);
                                 $query->execute();
                                 $query->store_result();
                                 $query->bind_result($influencer_id, $name, $post, $profile_pic);
 
                                 while($query->fetch()) {
                                 $data = [];
                                 $data['influencer_id'] = $influencer_id;
                                 $data['name'] = $name;
                                 $data['post'] = $post;
                                 $data['profile_pic'] = $profile_pic;
                                 $dataInfluencers[] = $data;
                                 $data = null;
                                
                                 
                                 }
                                 
                         }
                         
                    $tmp['influencer'] = $dataInfluencers;     
                    //Influencer Ended

                    //Media Connected to Sivic
                    $query = $this->connect->prepare('SELECT url FROM sivic_medias where sivic_id = ?');

                    $query->bind_param('i', $sivic_id);
                    $query->execute();
                    $query->store_result();
                    $query->bind_result($url);
                    $dataMedia = [];
                  
                    while($query->fetch()) {
    
                        $data = [];
                                $data['url'] = $url;
                              
                                $dataMedia[] = $data;
                                $data = null;
                                }
                    $tmp['media'] = $dataMedia;           
                    //Media Ended  
 
                    $currentdate = strtotime (date ("Y-m-d"));
                    $sivictimestamp =abs( $currentdate - $modified_on);
                    $daysDifference = ceil($sivictimestamp/86400); 
    
                    $tmp['dateDiff'] = $daysDifference; 
    
                     
                        
                $dataSivic[] = $tmp;
                $tmp = null; 
                
                
                array_push( $datawhole, $dataSivic );        

                }
           
        }
        return ['Response' => $datawhole];
     
    }


    


    
   



}
