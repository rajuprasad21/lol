<?php
class Feeds{

	public static function getPublicFeeds($offset,$limit){
		#return
		global $conn;
		$sql = "SELECT P.id,P.user_id,P.title,P.description,P.category_id,P.global,P.approved,P.created_on,P.is_text,
		CONCAT('#',(SELECT GROUP_CONCAT(PH.tag SEPARATOR '#') FROM post_hastag PH WHERE PH.post_id=P.id )) as tags,CASE P.is_text WHEN 'y' THEN ''
		ELSE (SELECT FROM post_image PI ON )
		END CASE FROM post P  ORDER BY P.created_on DESC LIMIT {$offset},{$limit}";


	}
}
?>