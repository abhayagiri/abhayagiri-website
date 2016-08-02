<?php

require_once __DIR__ . '/../mahapanel-bootstrap.php';

require_once('db.php');
require_once('func.php');
$table = $_POST['table'];
$url_title = $_POST['url_title'];
$db = Abhayagiri\DB::getDB();
try {
    $stmt = $db->_select($table, 'id', array("url_title" => $url_title));
    echo count($stmt);
} catch (Exception $e) {
    if (strpos($e->getMessage(), 'Column not found') !== false) {
        echo 0;
    } else {
        raise $e;
    }
}
?>
