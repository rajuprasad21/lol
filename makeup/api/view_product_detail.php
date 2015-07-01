<?php
require_once('../phpInclude/db_connection.php');
require_once('../classes/AllClasses.php');

$product_id = $_POST['product_id'];
$product_detail='';
if(!empty($product_id)){
	$product = new Products;
	$product_detail=$product->getProductDetailById($product_id);
	$success='1';$msg='product data';
}else{
	$success='0';$msg='Incomplete Parameters';
}
echo json_encode(array('success'=>$success,'msg'=>$msg,'product'=>$product_detail));
?>