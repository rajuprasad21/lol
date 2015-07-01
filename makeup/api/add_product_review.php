<?php
require_once('../phpInclude/db_connection.php');
require_once('../classes/AllClasses.php');

$product_id = $_POST['product_id'];
$user_id	= $_POST['user_id'];
$rating		= $_POST['rating'];
$review  	= $_POST['review'];
$token 		= $_POST['token'];

$user = new Users;
$product = new Products;

if(!empty($product_id) && !empty($user_id) && !empty($review) && !empty($rating)){
	if($user->isTokenValid($user_id,$token)){
		$product->addProductReview($user_id,$product_id,$review,$rating);
		$success='1';$msg='reated successfully';
	}else{
		$success='0';$msg='token expired';
	}
	$reviews = $product->getProductReviews($product_id,$offset,$limit);
	$success='1';$msg='all reviews';
}else{
	$success='0';$msg='Incomplete Parameters';
}
echo json_encode(array('success'=>$success,'msg'=>$msg));
?>