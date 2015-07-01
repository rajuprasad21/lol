<?php
require_once('../phpInclude/db_connection.php');
require_once('../classes/AllClasses.php');

$username = $_POST['username']; //unique user name//
$password = $_POST['password'];
$email	  = $_POST['email'];
$yob	  = $_POST['yob'];
$skin_tone = $_POST['skin_tone'];
$postal_code = $_POST['postal_code'];
$country	 = $_POST['country'];
$gender		 = $_POST['gender'];
$profile = '';
$facebook_id = $_POST['facebook_id'];
$twitter_id	 = $_POST['twitter_id'];
$user = new Users;

if($facebook_id){
	$user_id = $user->createUserByFacebookId($facebook_id,$username,$gender,$yob,$skin_tone,$country,$postal_code);
	$profile	= $user->getUserProfileById($user_id);
}elseif($twitter_id){
	$user_id = $user->createUserByTwitterId($twitter_id,$username,$gender,$yob,$skin_tone,$country,$postal_code);
	$profile	= $user->getUserProfileById($user_id);
}elseif(!empty($username) && !empty($password) && !empty($email) && !empty($gender) && !empty($yob) && !empty($skin_tone) && !empty($country) && !empty($postal_code)){
	if(!$user->isUserNameExists($username)){
		$password = md5($password);
		if($user_id=$user->createUserByEmail($username,$password,$email,$gender,$yob,$skin_tone,$country,$postal_code)){
			//successfully created user return user data//
			$success='1';$msg='Registration Successfull';
			$profile	= $user->getUserProfileById($user_id);

		}else{
			$success='0';$msg='Registration Failed';
		}
	}else{
		$success='0';$msg='Username not available';
	}
}else{
	$success='0';$msg='Incomplete Parameters';
}
echo $success;
echo $msg;
print_r($profile);
?>