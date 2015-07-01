<?php
require_once('../phpInclude/db_connection.php');
require_once('../classes/AllClasses.php');

$post_id = $_POST['post_id'];
$post = new Posts;

$res = $post->getPostById($post_id);

print_r($res);
?>