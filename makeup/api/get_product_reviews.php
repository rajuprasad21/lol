<?php
require_once('../phpInclude/db_connection.php');
require_once('../classes/AllClasses.php');

$product_id = $_POST['product_id'];
$offset		= $_POST['offset']?$_POST['offset']:0;
$limit		= $_POST['limit']?$_POST['limit']:10;
$reviews    = array();
if(!empty($product_id)){
	$product = new Products;
	$reviews = $product->getProductReviews($product_id,$offset,$limit);
	$success='1';$msg='all reviews';
}else{
	$success='0';$msg='Incomplete Parameters';
}
echo json_encode(array('success'=>$success,'msg'=>$msg,'reviews'=>$reviews));
?>