<?php
require_once('../phpInclude/db_connection.php');
require_once('../classes/AllClasses.php');

$user_id = $_POST['user_id'];
$folder_id = $_POST['folder_id'];
$token	 = $_POST['token'];

$user = new Users;
if(!empty($user_id) && !empty($folder_id)){
	if($user->deleteFolder($user_id,$folder_id)){
		$user_folders = $user->getUserFolders($user_id);
		$success='1';$msg='Deleted';
	}else{
		$success='0';$msg='Delete Failed';
	}

}else{
	$success='0';$msg='Incomplete Parameters';
}

echo json_encode(array('success'=>$success,'msg'=>$msg,'folders'=>$user_folders));