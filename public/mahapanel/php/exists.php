<?php

require_once('db.php');
require_once('func.php');
$table = $_POST['table'];
$url_title = $_POST['url_title'];
$db = new DB();
$stmt = $db->_select($table, 'id', array("url_title" => $url_title));
echo count($stmt);
?>
