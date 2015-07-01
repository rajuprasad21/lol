<?php
require_once('../phpInclude/db_connection.php');
if($conn){
	echo "connection success";
}else{
	echo "no database connection";
}
?>