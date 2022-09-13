<?php
require_once("connect.php");
$Get_id=$_GET['id'];
QB::table('user')->where('id', '=', $Get_id)->delete();
// @unlink('user_images/');
header("location:index.php");
?>

