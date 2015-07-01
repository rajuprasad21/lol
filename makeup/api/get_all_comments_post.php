<?php
require_once('../phpInclude/db_connection.php');
require_once('../classes/AllClasses.php');

$post_id = $_POST['post_id'];
$user_id = $_POST['user_id'];
$token   = $_POST['token'];
$comments = array();
if(!empty($user_id) && !empty($post_id)){
    $user = new Users;
    $post = new Posts;
    $comments = $post->getAllCommentsPost($post_id);
    $comments = getTree($comments);
    foreach($comments as $key=>$value) {
        $sort_data[$key] = $value['created_on'];
    }
    array_multisort($sort_data, SORT_DESC, $comments);
    $success='1';$msg='here comments';
}else{
    $success='0';$msg='Incomplete Parameters';
}
echo json_encode(array('success'=>$success,'msg'=>$msg,'comments'=>$comments));

function getTree(&$res1) {
    $map = array(
        0 => array('child' => array())
    );
    foreach ($res1 as &$category) {
        $category['child'] = array();
        $map[$category['id']] = &$category;
    }
    foreach ($res1 as &$category) {
        $map[$category['parent_id']]['child'][] = &$category;
    }
    return $map[0]['child'];
}

?>