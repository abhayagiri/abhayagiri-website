<?php

require_once __DIR__ . '/../mahapanel-bootstrap.php';

$user = $_SESSION['user'];
require_once('db.php');
require_once('func.php');
foreach ($_POST as $key => $value) {
    $$key = $value;
}
$db = Abhayagiri\DB::getDB();
$func = new Abhayagiri\Func();

/*
  $action = $_POST['action'];
  $activity = $_POST['activity'];
  $table = $_POST['table'];
  $page = $_POST['page'];
  $columns = $_POST['columns'];
  $where = $_POST['where'];
  $order = $_POST['order'];
  $user = $_POST['user'];
 */
if (isset($columns['parent'])) {
    $table_name = $func->getTableName($columns['parent']);
}

switch ($action) {
    case "select":
        $result = $db->_select($table, $columns, $where, $order);
        echo json_encode($result);
        break;
    case "insert":
        $db->_insert($table, $columns);
        echo "Attempting to log...";
        $db->_log($action, $table, array_get($columns, 'title', ''), $_SESSION['user']);
        if ($table == "columns") {
            $func->addColumn($table_name, array_get($columns, 'title', ''), $columns['column_type']);
        } else if ($table == "pages") {
            $func->addPage($columns['url_title'], $user);
        }
        break;
    case "update":
        if ($table == "columns") {
            $old_name = $func->getColumnName($where['id']);
            echo $where['id'];
            echo $old_name;
            $func->updateColumn($table_name, $old_name, $columns['column_type'], $columns['title']);
        } else if ($table == "pages") {
            $old_name = $func->getTableName($where['id']);
            $func->updatePage($old_name, $columns['url_title']);
        }
        $db->_update($table, $columns, $where);
        $db->_log($action, $table, array_get($columns, 'title', ''), $_SESSION['user']);
        break;
    case "delete":
        $db->_delete($table, array("id" => $columns['id']));
        $db->_log($action, $table, array_get($columns, 'title', ''), $_SESSION['user']);
        if ($table == "columns") {
            echo $table_name;

            $func->deleteColumn($table_name, array_get($columns, 'title', ''));
        } else if ($table == "pages") {
            $func->deletePage($columns['id'], $columns['url_title']);
        }
        break;
    default:
        break;
}
?>
