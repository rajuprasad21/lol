<?php
#save post id,desc,
#save post tags
#save post images and tags

$sample_post = array(
		'title'=>'My first post',
		'description'=>'a sort description',
		'tags'=>'#igag#someone#lost',
		'category_id'=>'1',
		'is_global'=>'1',
		'is_text'=>'0',
		'photos'=>array(
					array(
					'name'=>'name by user',
					'image'=>'long base 64 encoded string',
					'tags'=>array(
							array(
									'pos_x'=>100,
									'pos_y'=>100,
									'brand_name'=>'id or name',
									'productline'=>'1',
									'product_id'=>'1'
								),
							array(
									'pos_x'=>100,
									'pos_y'=>100,
									'brand_name'=>'id or name',
									'productline'=>'1',
									'product_id'=>'1'
								)	

						)
					),
					array(
					'name'=>'name by user',
					'image'=>'long base 64 encoded string',
					'tags'=>array(
							array(
									'pos_x'=>100,
									'pos_y'=>100,
									'brand_name'=>'id or name',
									'productline'=>'1',
									'product_id'=>'1'
								),
							array(
									'pos_x'=>100,
									'pos_y'=>100,
									'brand_name'=>'id or name',
									'productline'=>'1',
									'product_id'=>'1'
								)	

						)
					)		
			)
	);
//echo json_encode($sample_post);die;
require_once('../phpInclude/db_connection.php');
require_once('../classes/AllClasses.php');

$token = $_POST['token'];
$user_id = $_POST['user_id'];
$caption = $_POST['caption'];
$description = $_POST['description'];
$user = new Users;
$post = new Posts;
if(!empty($user_id) && !empty($token)){
	if($user->isTokenValid($user_id,$token)){
		//create post//
		$title = $sample_post['title'];
		$description = $sample_post['description'];
		$category_id = $sample_post['category_id'];
		$global = $sample_post['is_global'] == '1' ? 'y':'n';
		$is_text = $sample_post['is_text'] == '1' ? 'y' : 'n';
		$approved = 'n';
		$tags = $sample_post['tags'];

		$post_id = $post->savePost($user_id,$title,$description,$category_id,$global,$approved,$is_text);

		if($post_id){
			$post_tags=explode('#',$tags);
			$post->savePostTags($post_id,$post_tags);
			if($is_text == 'n'){
				foreach($sample_post['photos'] as $key=>$value){
					$post_image_id = $post->savePostImage($post_id,$value['name'],$value['image']);
					if($post_image_id){
						$post_image_tags = $value['tags'];
						$post->savePostImageTags($post_image_id,$post_image_tags);
					}
				}
			}
		}	
	}else{
		$success='0';$msg='token expired login to continue';
	}
}else{
	$success='0';$msg='Incomplete Parameters';
}
if($post_id){
	//post created return post _data//
}
?>