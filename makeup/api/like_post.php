<?php
require_once('../phpInclude/db_connection.php');
require_once('../classes/AllClasses.php');

$user_id = $_POST['user_id'];
$post_id = $_POST['post_id'];
$token   = $_POST['token'];
$user = new Users;
$post = new Posts;

if(!empty($user_id) && !empty($post_id)){
	if($user->isTokenValid($user_id,$token)){
		$post->likePost($user_id,$post_id);
		$success='1';$msg='Like success';
	}else{
		$success='0';$msg='Token Expires login again';
	}
}else{
	$success='0';$msg='Incomplete Parameters';
}
echo json_encode(array('success'=>$success,'msg'=>$msg));