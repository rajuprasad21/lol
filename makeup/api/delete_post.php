<?php
require_once('../phpInclude/db_connection.php');
require_once('../classes/AllClasses.php');

$user_id = $_POST['user_id'];
$token   = $_POST['token'];
$post_id = $_POST['post_id'];

$user = new Users;
$post = new Posts;

if(!empty($user_id) && !empty($post_id)){
	if($user->isTokenValid($user_id,$token)){
		$post->deletePost($post_id);
	}else{

	}
}else{
	$success='0';$msg='Incomplete Parameters';
}
