<?php
class Products{

	public static function getProductDetailById($product_id){
		global $conn;
		$response='';
		$sql = "SELECT P.id as pid,P.name as pname,P.store_link,PL.name as plname,
		B.name as bname,(SELECT count(*) FROM product_rating PR WHERE PR.product_id=P.id) as rating_count,(SELECT avg(rating) FROM product_rating PR WHERE PR.product_id=P.id) as avg_rating FROM product P 
		JOIN product_line PL ON P.product_line_id=PL.id
		JOIN brand_name B ON PL.brand_id=B.id
		WHERE P.id=:product_id";
		$sth = $conn->prepare($sql);
		$sth->bindParam(':product_id',$product_id);
		try{
			$sth->execute();
			$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			$response = $result[0];
		}catch(Exception $e){}
		return $response;	
	}

	public static function getProductReviews($product_id,$offset,$limit){
		global $conn;
		$response=array();
		$sql= "SELECT U.username,PR.rating,PR.text,PR.created_on FROM product_rating PR JOIN user U on PR.user_id=U.id WHERE PR.product_id=:id";
		$sth = $conn->prepare($sql);
		$sth->bindParam(':id',$product_id);
		try{
			$sth->execute();
			$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			foreach($result as $key=>$value){
				$response[]=$value;
			}
		}catch(Exception $e){}
		return $response;
	}

	public static function addProductReview($user_id,$product_id,$review,$rating){
		global $conn;
		$sql= "INSERT INTO product_rating values (DEFAULT,:product_id,:rating,:user_id,:review,NOW()) ON DUPLICATE KEY UPDATE
rating=:new_rating, `text`=:new_review, created_on=NOW()";
		$sth = $conn->prepare($sql);
		$sth->bindParam(':product_id',$product_id);
		$sth->bindParam(':rating',$rating);
		$sth->bindParam(':user_id',$user_id);
		$sth->bindParam(':review',$review);
		$sth->bindParam(':new_rating',$rating);
		$sth->bindParam(':new_review',$review);
		try{
			$sth->execute();
		}catch(Exception $e){
			echo $e->getMessage();
		}
		return true;
	}

	public static function getAllProducts(){
		global $conn;
		$temp = array();
		$sql = "SELECT P.id as pid,P.name as pname,P.store_link,PL.id as plid,PL.name as plname,B.id as bid,B.name as bname FROM product P JOIN  product_line PL ON P.product_line_id=PL.id JOIN brand_name B ON PL.brand_id=B.id WHERE 1 ORDER BY bid,plid,pid";
		$sth = $conn->prepare($sql);
		$sth->execute();
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		foreach($result as $key=>$value){
			$temp[]=$value;
		}
		$final_data = array(
			'brands'=>array(
					'id'=>'b_name',
					'name'=>'b_name'
				)
			);
		$brand_id=0;$pl_id=0;$pid=0;$all_brands=array();
		foreach($temp as $key=>$value){
			if(!in_array($value['bid'], $all_brands)){
				$brands[]=array('id'=>$value['bid'],'b_name'=>$value['bname'],'product_line'=>array());
				$all_brands[]=$value['bid'];
			}
		}

		$all_product_lines=array();
		foreach($temp as $key=>$value){
			foreach($brands as $rr=>$ss){
				if($ss['id']==$value['bid']){
					if(!in_array($value['plid'], $all_product_lines)){
					$brands[$rr]['product_line'][]=array('pl_id'=>$value['plid'],'name'=>$value['plname'],'products'=>array());
					$all_product_lines[]=$value['plid'];
					}
				}
			}
		}

		foreach($brands as $key=>$value){
			foreach($value['product_line'] as $rr=>$ss){
				foreach($temp as $jj=>$kk){
					if($kk['bid']== $value['id'] && $ss['pl_id']==$kk['plid']){
						$brands[$key]['product_line'][$rr]['products'][]=array('pid'=>$kk['pid'],'pname'=>$kk['pname']);
					}
				}
			}
		}
		return $brands;
	}

	public static function addToInventory($user_id,$product_id){
		global $conn;
		$sql = "INSERT IGNORE INTO inventory VALUES(DEFAULT,:user_id,:product_id,NOW())";
		$sth = $conn->prepare($sql);
		$sth->bindParam(':user_id',$user_id);
		$sth->bindParam(':product_id',$product_id);
		try{
			$sth->execute();
		}catch(Exception $e){}
		return true;
	}
}
?>