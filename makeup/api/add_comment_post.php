<?php
require_once('../phpInclude/db_connection.php');
require_once('../classes/AllClasses.php');

$user_id = $_POST['user_id'];
$post_id = $_POST['post_id'];
$parent_id = $_POST['parent_id']?$_POST['parent_id']:0;
$comment = $_POST['comment'];
$token	= $_POST['token'];

$user = new Users;
$post = new Posts;

if(!empty($user_id) && !empty($post_id) && !empty($comment)){
	if($user->isTokenValid($user_id,$token)){
		if($post->addCommentPost($user_id,$post_id,$parent_id,$comment)){
			$success='1';$msg='added success';
		}else{
			$success='0';$msg='failed';
		}
	}else{
		$success='0';$msg='Token expired login again';
	}
}else{
	$success='0';$msg='Incomplete Parameters';
}
echo json_encode(array('success'=>$success,'msg'=>$msg));