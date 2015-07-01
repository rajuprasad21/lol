<?php
require_once('../phpInclude/db_connection.php');
require_once('../classes/AllClasses.php');

$product = new Products;
echo json_encode($product->getAllProducts());
?>