<?php
require_once('../phpInclude/db_connection.php');
require_once('../classes/AllClasses.php');

$product_id = $_POST['product_id'];
$user_id = $_POST['user_id'];
$token = $_POST['token'];

$user = new Users;
$product = new products;
$inventory=array();
if(!empty($user_id) && !empty($product_id)){
	if($user->isTokenValid($user_id,$token)){
		$product->addToInventory($user_id,$product_id);
		$inventory=$user->getInventory($user_id);
		$success='1';$msg='success';
	}else{
		$success='0';$msg='Token expired Login Again';
	}

}else{
	$success='0';$msg='Incomplete Parameters';
}
echo json_encode(array('success'=>$success,'msg'=>$msg,'inventory'=>$inventory));
?>