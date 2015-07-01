<?php
require_once('../phpInclude/db_connection.php');
require_once('../classes/AllClasses.php');
/*
$folders_arr = array(
	array('id'=>0,'position'=>1,'title'=>'t1'),
	array('id'=>0,'position'=>2,'title'=>'t2'),
	array('id'=>0,'position'=>3,'title'=>'t3')
	);
echo json_encode($folders_arr);die;
*/	
$user_id = $_POST['user_id'];
$folders = $_POST['folders'];
$token	 = $_POST['token'];
$user_folders = array();
$user = new Users;
global $conn;
if(!empty($user_id) && !empty($folders)){
	$folders_arr = json_decode($folders,true);
	foreach ($folders_arr as $key => $value) {
		//insert if id else update//
		if($value['id']){
			$sql="UPDATE folders SET position={$value[position]} WHERE id={$value[id]}";
			try{
				$conn->query($sql);
			}catch(Exception $e){}
		}else{
			$sql="INSERT INTO folders VALUES(DEFAULT,:user_id,:title,:position,NOW())";
			$sth = $conn->prepare($sql);
			$sth->bindParam(':user_id',$user_id);
			$sth->bindParam(':title',$value['title']);
			$sth->bindParam(':position',$value['position']);
			try{
				$sth->execute();
			}catch(Exception $e){}
		}
	}
	$user_folders = $user->getUserFolders($user_id);
}else{
	$success='0';$msg='Incomplete Parameters';
}
echo json_encode(array('success'=>$success,'msg'=>$msg,'folders'=>$user_folders));
?>