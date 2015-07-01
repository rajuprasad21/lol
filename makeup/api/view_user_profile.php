<?php
require_once('../phpInclude/db_connection.php');
require_once('../classes/AllClasses.php');

$user_id = $_POST['user_id'];
$token   = $_POST['token'];

if(!empty($user_id)){
	$user = new Users;
	$comments = $user->getAllCommentsByUser($user_id);
	$text_posts = $user->getAllTextPostByUser($user_id);
	$image_posts = $user->getImagePostsByUser($user_id);
	$post_likes = $user->getUserPostLikeCount($user_id);
	$success='0';$msg='user data';
}else{
	$success='0';$msg='Incomplete Parameters';
}
echo json_encode(array('success'=>$success,'msg'=>$msg,'comments'=>$comments,'text_posts'=>$text_posts,'image_posts'=>$image_posts,'post_likes'=>$post_likes));
?>