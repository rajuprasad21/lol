<?php
class Posts{

	public static function getPostById($post_id){
		global $conn;
		//post , post tags ,post images,images tag
		$sql = "SELECT post.*,CONCAT('#',(SELECT GROUP_CONCAT(tag SEPARATOR '#') FROM post_hastag WHERE post_id=post.id)) as tags FROM post  WHERE id=:post_id;";
		$sth = $conn->prepare($sql);
		$sth->bindParam(':post_id',$post_id);
		try{
			$sth->execute();
			$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			$post_data = $result[0];
			$post_data['photos']= Posts::getAllPostPhotos($post_id);
		}catch(Exception $e){}
		return $post_data;
	}

	public static function getAllPostPhotos($post_id){
		global $conn;
		$all_photos = array();
		$sql = "SELECT PI.id,PI.post_id,PI.image_path,PI.created_on,PIT.id as i_tag_id, PIT.text,PIT.ratio_x,PIT.ratio_y,PIT.product_id,PIT.inventory_id FROM post_image PI LEFT JOIN post_image_tag PIT ON PI.id=PIT.post_image_id WHERE PI.post_id=:post_id ORDER BY PI.id,i_tag_id";
		$sth = $conn->prepare($sql);
		$sth->bindParam(':post_id',$post_id);
		try{
			$sth->execute();
			$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			$image_id = 0;
			foreach ($result as $key => $value) {
				if($image_id!=$value['id'] || $image_id==0){
					//create new and add tags//
					$all_photos[]=array('id'=>$value['id'],'post_id'=>$value['post_id'],'image_path'=>$value['image_path'],'create_on'=>$value['created_on'],'tags'=>array(0=>array('i_tag_id'=>$value['i_tag_id'],'ratio_x'=>$value['ratio_x'],'ratio_y'=>$value['ratio_y'],'product_id'=>$value['product_id'],'inventory_id'=>$value['inventory_id'])));
				}else{
					$index = sizeof($all_photos) - 1;
					$all_photos[$index]['tags'][]=array('i_tag_id'=>$value['i_tag_id'],'ratio_x'=>$value['ratio_x'],'ratio_y'=>$value['ratio_y'],'product_id'=>$value['product_id'],'inventory_id'=>$value['inventory_id']);
				}
				$image_id = $value['id'];
			}

		}catch(Exception $e){}
		return $all_photos;
	}

	public static function addCommentPost($user_id,$post_id,$parent_id,$comment){
		global $conn;
		$Insertid=0;
		$sql="Insert INTO post_comments VALUES(DEFAULT,:user_id,:post_id,:parent_id,:text,NOW())";
		$sth = $conn->prepare($sql);
		$sth->bindParam(':user_id',$user_id);
		$sth->bindParam(':post_id',$post_id);
		$sth->bindParam(':parent_id',$parent_id);
		$sth->bindParam(':text',$comment);
		try{
			$sth->execute();
			$Insertid=$conn->lastInsertId();
		}catch(Exception $e){
			echo $e->getMessage();
		}
		return $Insertid;
	}

	public static function getAllCommentsPost($post_id){
		global $conn;
		$all_comments =array();
		$sql="SELECT U.id as uid,U.username,PC.id,PC.post_id,PC.parent_id,PC.text,PC.created_on FROM post_comments PC JOIN user U ON PC.user_id=U.id WHERE PC.post_id=:post_id";
		$sth = $conn->prepare($sql);
		$sth->bindParam(':post_id',$post_id);
		try{
			$sth->execute();
			$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			foreach($result as $key=>$value){
				$all_comments[]=$value;
			}
		}catch(Exception $e){}
		return $all_comments;
	}

	public static function flagComment($user_id,$comment_id,$reason){
		global $conn;
		//if flag comment count >= 4 delete comments and its child//
		$flag_count = Posts::getFlagCountComments($comment_id);
		if($flag_count >= 4){
			Posts::deleteComment($comment_id);
			return true;
		}else{
			$sql = "INSERT IGNORE INTO comment_flag VALUES (DEFAULT,:user_id,:comment_id,:reason,NOW())";
			$sth = $conn->prepare($sql);
			$sth->bindParam(':user_id',$user_id);
			$sth->bindParam(':comment_id',$comment_id);
			$sth->bindParam(':reason',$reason);
			try{
				$sth->execute();
			}catch(Exception $e){
				echo $e->getMessage();
			}
			return true;
		}	
	}

	public static function getFlagCountComments($comment_id){
		global $conn;
		$count = 0;
		$sql = "SELECT count(*) as count FROM comment_flag WHERE comment_id=:comment_id";
		$sth = $conn->prepare($sql);
		$sth->bindParam(':comment_id',$comment_id);
		try{
			$sth->execute();
			$result=$sth->fetchAll(PDO::FETCH_ASSOC);
			$count = $result[0]['count'];	
		}catch(Exception $e){}
		return $count;
	}

	public static function deleteComment($comment_id){
		global $conn;
		$sql = "DELETE FROM post_comments WHERE id=:comment_id OR parent_id=:comment_id1";
		$sth = $conn->prepare($sql);
		$sth->bindParam(':comment_id',$comment_id);
		$sth->bindParam(':comment_id1',$comment_id);
		try{
			$sth->execute();
		}catch(Exception $e){
			echo $e->getMessage();
		}
	}

	public static function likePost($user_id,$post_id){
		global $conn;
		$sql = "INSERT IGNORE INTO post_likes VALUES(DEFAULT,:post_id,:user_id,NOW())";
		$sth = $conn->prepare($sql);
		$sth->bindParam(':user_id',$user_id);
		$sth->bindParam(':post_id',$post_id);
		try{
			$sth->execute();
		}catch(Exception $e){}
	}

	public static function savePost($user_id,$title,$description,$category_id,$global,$approved,$is_text){
		global $conn;
		$Insertid=0;
		$sql = "INSERT INTO post VALUES(DEFAULT,:user_id,:title,:description,:category_id,:global,:approved,NOW(),:is_text)";
		$sth = $conn->prepare($sql);
		$sth->bindParam(':user_id',$user_id);
		$sth->bindParam(':title',$title);
		$sth->bindParam(':description',$description);
		$sth->bindParam(':category_id',$category_id);
		$sth->bindParam(':global',$global);
		$sth->bindParam(':approved',$approved);
		$sth->bindParam(':is_text',$is_text);
		try{
			$sth->execute();
			$Insertid = $conn->lastInsertId();
		}catch(Exception $e){
			echo $e->getMessage();
		}
		return $Insertid;
	}

	public static function savePostTags($post_id,$post_tags){
		global $conn;
		$sql = "INSERT INTO post_hastag VALUES (DEFAULT,:tag,:post_id)";
		$sth = $conn->prepare($sql);
		foreach($post_tags as $key=>$value){
			if(!empty($value)){
				$sth->bindValue(':tag',$value);
				$sth->bindValue(':post_id',$post_id);
				try{
					$sth->execute();
				}catch(Exception $e){}
			}
		}
		return true;
	}

	public static function savePostImage($post_id,$name,$image){
		global $conn;
		$success='1';
		$Insertid=0;
		$random_name = 'IMG_'.$post_id.'_'.md5(time()).'_'.rand(0,1000);
		$file = fopen("../images/".$random_name, "wb");
		if(fwrite($file, base64_decode($image))){}else $success="0";
		fclose($file);
		if($success){
			//save to db//
			$sql= "INSERT INTO post_image VALUES(DEFAULT,:post_id,:image_path,NOW())";
			$sth=$conn->prepare($sql);
			$sth->bindParam(':post_id',$post_id);
			$sth->bindParam(':image_path',$random_name);
			try{
				$sth->execute();
				$Insertid = $conn->lastInsertId();
			}catch(Exception $e){}
		}
		return $Insertid;
	}

	public static function savePostImageTags($post_image_id,$post_image_tags){
		global $conn;
		$sql = "INSERT INTO post_image_tag VALUES(DEFAULT,:post_image_id,:text,:ratio_x,:ratio_y,:product_id,:inventory_id)";
		$sth = $conn->prepare($sql);
		foreach($post_image_tags as $key=>$value){
			//print_r($value);die;
			$sth->bindValue(':post_image_id',$post_image_id);
			$sth->bindValue(':text',$value['brand_name']);
			$sth->bindValue(':ratio_x',$value['pos_x']);
			$sth->bindValue(':ratio_y',$value['pos_y']);
			$sth->bindValue(':product_id',$value['product_id']);
			$sth->bindValue(':inventory_id',$value['productline']);
			try{
				$sth->execute();
			}catch(Exception $e){
				echo $e->getMessage();
			}
		}
		return true;
	}

	public static function deletePost($post_id){
		global $conn;
		//post , post_comments , post_hashtag,post_image,post_image_tag,post_flag,
		$sql = "DELETE FROM post_like WHERE post_id=:post_id";
		$sth = $conn->prepare($sql);
		$sth->bindParam(':post_id',$post_id);
		try{
			$sth->execute();
		}catch(Exception $e){}
		return true;
	}
}

?>