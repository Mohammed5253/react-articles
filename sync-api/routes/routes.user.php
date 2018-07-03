<?php
// echo "Hie";exit;
$app->POST( '/user/register', 'sivic_register_user' );
$app->POST( '/login', 'sivic_login' );
$app->POST( '/forget_password', 'sivic_forget_password' );
$app->POST( '/change_password', 'sivic_change_password' );
$app->GET( '/codemaster', 'sivic_codemaster' );
$app->POST( '/userIssues', 'sivic_user_issues' );
$app->POST( '/userInfluencers', 'sivic_user_influencers' );
$app->POST( '/resendVerificationEmail', 'resendVerificationEmail' );
$app->POST( '/emailVerified', 'sivic_user_verify_email' );
$app->GET( '/listSivic', 'sivic_list_wall' );
$app->POST( '/sivic', 'sivic_create' );
$app->POST( '/script', 'sivic_script_according_issue' );
$app->POST( '/searchInfluencer', 'sivic_search_influencer' );
$app->POST( '/socialLogin', 'sivic_social_login' );
$app->POST( '/socialCheck', 'sivic_social_check' );
$app->POST( '/updateflag', 'sivic_update_flag_register' );
$app->POST( '/sivic/issue_influencer_list', 'sivic_issue_influencer_list' );
$app->POST( '/sivic/issue_script', 'sivic_issue_script' );
$app->GET( '/resivicforsivic/{random:[0-9, a-zA-z]+}', 'sivic_resivic_for_sivic' );

















