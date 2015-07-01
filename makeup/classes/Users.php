<?php
class Users{

	public static function isUserNameExists($username){
		global $conn;
		$sql	= "Select count(*) as count from user WHERE username LIKE :username LIMIT 1";
		$sth	= $conn->prepare($sql);
		$sth->bindParam(':username',$username,PDO::PARAM_STR);
		$sth->execute();
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		$count	= $result[0]['count'];
		return $count;
	}

	public static function createUserByEmail($username,$password,$email,$gender,$yob,$skin_tone,$country,$postal_code){
		global $conn;
		$facebook_id = $twitter_id = NULL;
		$approved	 = 'n';
		$token 		 = '';
		$sql	= "INSERT INTO user VALUES(DEFAULT,:email,:username,:password,:gender,:yob,:postal_code,:country,:skin_tone,:fb_id,:approved,:twitter_id,NOW(),:token)";
		$sth	= $conn->prepare($sql);
		$sth->bindParam(':email',$email);
		$sth->bindParam(':username',$username);
		$sth->bindParam(':password',$password);
		$sth->bindParam(':gender',$gender);
		$sth->bindParam(':yob',$yob);
		$sth->bindParam(':postal_code',$postal_code);
		$sth->bindParam(':country',$country);
		$sth->bindParam(':skin_tone',$skin_tone);
		$sth->bindParam(':fb_id',$facebook_id);
		$sth->bindParam(':approved',$approved);
		$sth->bindParam(':twitter_id',$twitter_id);
		$sth->bindParam('token',$token);
		try{
			$sth->execute();
			$insertid	= $conn->lastInsertId();
			$token = Users::generateToken($insertid);
			Users::setToken($insertid,$token);
		}catch(Exception $e){
			$insertid 	= 0;
		}
		return $insertid;
	}

	public static function createUserByFacebookId($facebook_id,$username,$gender,$yob,$skin_tone,$country,$postal_code){
		global $conn;
		$twitter_id = NULL;
		$email = '';
		$password='';
		$approved='n';
		$token='';
		$user_id=Users::getUserIdByFacebookId($facebook_id);
		if($user_id){
			echo "aa";
			return $user_id;
		}else{
			echo "aar";	
			$sql	= "INSERT INTO user VALUES(DEFAULT,:email,:username,:password,:gender,:yob,:postal_code,:country,:skin_tone,:fb_id,:approved,:twitter_id,NOW(),:token)";
			$sth	= $conn->prepare($sql);
			$sth->bindParam(':email',$email);
			$sth->bindParam(':username',$username);
			$sth->bindParam(':password',$password);
			$sth->bindParam(':gender',$gender);
			$sth->bindParam(':yob',$yob);
			$sth->bindParam(':postal_code',$postal_code);
			$sth->bindParam(':country',$country);
			$sth->bindParam(':skin_tone',$skin_tone);
			$sth->bindParam(':fb_id',$facebook_id);
			$sth->bindParam(':approved',$approved);
			$sth->bindParam(':twitter_id',$twitter_id);
			$sth->bindParam('token',$token);
			try{
				$sth->execute();
				$insertid	= $conn->lastInsertId();
				$token = Users::generateToken($insertid);
				Users::setToken($insertid,$token);
			}catch(Exception $e){
				$insertid 	= 0;
			}
			return $insertid;
		}

	}

	public static function createUserByTwitterId($twitter_id,$username,$gender,$yob,$skin_tone,$country,$postal_code){
		global $conn;
		$facebook_id = NULL;
		$email = '';
		$password='';
		$approved='n';
		$token='';
		$user_id=Users::getUserIdByTwitterId($twitter_id);
		if($user_id){
			echo "aa";
			return $user_id;
		}else{
			echo "aar";	
			$sql	= "INSERT INTO user VALUES(DEFAULT,:email,:username,:password,:gender,:yob,:postal_code,:country,:skin_tone,:fb_id,:approved,:twitter_id,NOW(),:token)";
			$sth	= $conn->prepare($sql);
			$sth->bindParam(':email',$email);
			$sth->bindParam(':username',$username);
			$sth->bindParam(':password',$password);
			$sth->bindParam(':gender',$gender);
			$sth->bindParam(':yob',$yob);
			$sth->bindParam(':postal_code',$postal_code);
			$sth->bindParam(':country',$country);
			$sth->bindParam(':skin_tone',$skin_tone);
			$sth->bindParam(':fb_id',$facebook_id);
			$sth->bindParam(':approved',$approved);
			$sth->bindParam(':twitter_id',$twitter_id);
			$sth->bindParam('token',$token);
			try{
				$sth->execute();
				$insertid	= $conn->lastInsertId();
				$token = Users::generateToken($insertid);
				Users::setToken($insertid,$token);
			}catch(Exception $e){
				$insertid 	= 0;
			}
			return $insertid;
		}

	}

	public static function getUserProfileById($user_id){
		global $conn;
		$sql	= "SELECT id,email,username,gender,yob,postal_code,country,skin_tone,fb_id,approved,twitter_id,token FROM user WHERE id=:user_id";
		$sth 	= $conn->prepare($sql);
		$sth->bindParam(':user_id',$user_id);
		$sth->execute();
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $result[0];
	}

	public static function generateToken($user_id){
		global $conn;
		return md5('makeup_'.$user_id.'_'.date());
	}

	public static function setToken($user_id,$token){
		global $conn;
		$sql = "UPDATE user SET token=:token WHERE id=:user_id";
		$sth = $conn->prepare($sql);
		$sth->bindParam(':token',$token);
		$sth->bindParam(':user_id',$user_id);
		try{
			$sth->execute();
		}catch(Exception $e){}
		return true;
	}

	public static function getUserIdByFacebookId($facebook_id){
		global $conn;
		$sql = "SELECT id FROM user WHERE fb_id=:facebook_id";
		$sth = $conn->prepare($sql);
		$sth->bindParam(':facebook_id',$facebook_id);
		$sth->execute();
		$result=$sth->fetchAll(PDO::FETCH_ASSOC);
		return $result[0][id] ? $result[0][id] : 0;
	}

	public static function getUserIdByTwitterId($twitter_id){
		global $conn;
		$sql = "SELECT id FROM user WHERE twitter_id=:twitter_id";
		$sth = $conn->prepare($sql);
		$sth->bindParam(':twitter_id',$twitter_id);
		$sth->execute();
		$result=$sth->fetchAll(PDO::FETCH_ASSOC);
		return $result[0][id] ? $result[0][id] : 0;
	}

	public static function isTokenValid($user_id,$token){
		global $conn;
		$sql = "SELECT count(*) as count FROM user WHERE id=:id AND token=:token";
		$sth = $conn->prepare($sql);
		$sth->bindParam(':id',$user_id);
		$sth->bindParam(':token',$token);
		$sth->execute();
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $result[0]['count'];
	}

	public static function getUserFolders($user_id){
		global $conn;
		$folders =array();
		$sql = "SELECT id,title,position,created_on FROM folders WHERE user_id=:user_id ORDER BY position";
		$sth = $conn->prepare($sql);
		$sth->bindParam(':user_id',$user_id);
		try{
			$sth->execute();
			$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			$folders = $result;
		}catch(Exception $e){}
		return $folders;
	}

	public static function deleteFolder($user_id,$folder_id){
		global $conn;
		$final_folders=array();
		$user_folders = Users::getUserFolders($user_id);
		foreach ($user_folders as $key => $value) {
			if($folder_id == $value[id]){
				$delete = true;
				continue;
			}
			if($delete){
				$final_folders[]=array('id'=>$value['id'],'position'=>$value['position'] - 1);
			}
		}
		//run delete//
		$sql = "DELETE FROM folders WHERE  id=:folder_id";
		$sth = $conn->prepare($sql);
		$sth->bindParam(':folder_id',$folder_id);
		try{
			$sth->execute();
		}catch(Exception $e){
			echo $e->getMessage();
		}

		//run update
		if(!empty($final_folders)){
			foreach($final_folders as $key=>$value){
				echo $sql = "UPDATE folders SET position={$value[position]} WHERE id={$value[id]}";
				try{
					$conn->query($sql);
				}catch(Exception $e){
					echo $e->getMessage();
				}
			}
		}
		return true;
	}

	public static function getInventory($user_id){
		global $conn;
		$inventory = array();
		$sql = "SELECT I.id,P.id,P.name FROM inventory I JOIN product P ON I.product_id=P.id WHERE I.user_id=:user_id";
		$sth = $conn->prepare($sql);
		$sth->bindParam(':user_id',$user_id);
		try{
			$sth->execute();
			$inventory = $sth->fetchAll(PDO::FETCH_ASSOC);
		}catch(Exception $e){}
		return $inventory;
	}

	public static function getAllCommentsByUser($user_id){
		global $conn;
		$comments = array();
		$sql = "SELECT P.id as pid,P.title,PC.id as pcid,PC.text,PC.created_on FROM post_comments PC JOIN post P ON PC.post_id=P.id WHERE PC.user_id=:user_id";
		$sth = $conn->prepare($sql);
		$sth->bindParam(':user_id',$user_id);
		try{
			$sth->execute();
			$result=$sth->fetchAll(PDO::FETCH_ASSOC);
			foreach ($result as $key => $value) {
				$comments[]=$value;
			}
		}catch(Exception $e){
			echo $e->getMessage();
		}
		return $comments;
	}

	public static function getAllTextPostByUser($user_id){
		global $conn;
		$all_posts =array();
		$sql = "SELECT id,title,description,created_on FROM post WHERE user_id=:user_id AND is_text='y' ORDER BY created_on DESC";
		$sth = $conn->prepare($sql);
		$sth->bindParam(':user_id',$user_id);
		try{
			$sth->execute();
			$result=$sth->fetchAll(PDO::FETCH_ASSOC);
			foreach($result as $key=>$value){
				$all_posts[]=$value;
			}
		}catch(Exception $e){}
		return $all_posts;
	}

	public static function getUserPostLikeCount($user_id){
		global $conn;
		$count = 0;
		$sql = "SELECT count(*) as count FROM post_likes WHERE post_id IN (SELECT post.id FROM post WHERE user_id=:user_id)";
		$sth = $conn->prepare($sql);
		$sth->bindParam(':user_id',$user_id);
		try{
			$sth->execute();
			$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			$count = $result[0]['count'];
		}catch(Exception $e){}
		return $count;
	}

	public static function getImagePostsByUser($user_id){
		global $conn;
		$all_posts =array();
		$sql = "SELECT P.*,PI.image_path FROM post P JOIN post_image PI ON PI.post_id=P.id  WHERE P.user_id=:user_id GROUP BY P.id ORDER BY P.created_on DESC";
		$sth = $conn->prepare($sql);
		$sth->bindParam(':user_id',$user_id);
		try{
			$sth->execute();
			$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			foreach ($result as $key => $value) {
				$all_posts[]=$value;
			}
		}catch(Exception $e){}
		return $all_posts;
	}
}
?>