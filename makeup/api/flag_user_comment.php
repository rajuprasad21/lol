<?php
require_once('../phpInclude/db_connection.php');
require_once('../classes/AllClasses.php');

$user_id = $_POST['user_id'];
$token   = $_POST['token'];
$comment_id = $_POST['comment_id'];
$reason = $_POST['reason']?$_POST['reason']:'';

if(!empty($user_id) && !empty($comment_id)){
	$user = new Users;
    $post = new Posts;
    if($user->isTokenValid($user_id,$token)){
    	$post->flagComment($user_id,$comment_id,$reason);
    	$success='1';$msg='thanks this post will be removed';
	}else{
		$success='0';$msg='Token Expired';
	}
}else{
    $success='0';$msg='Incomplete Parameters';
}
echo json_encode(array('success'=>$success,'msg'=>$msg));
?>