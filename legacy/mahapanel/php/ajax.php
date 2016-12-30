<?php

require base_path('legacy/bootstrap.php');
require base_path('legacy/mahapanel/php/session.php');

$db = App\Legacy\DB::getDB();
$func = new App\Legacy\Func();

foreach ($_POST as $key => $value) {
    $$key = $value;
}

if (isset($columns['parent'])) {
    $table_name = $func->getTableName($columns['parent']);
}

switch ($action) {
    case "select":
        $result = $db->_select($table, $columns, $where, $order);
        $db->normalizeResultFromSelect($result);
        echo json_encode($result);
        break;
    case "insert":
        $db->normalizeColumnsForUpdate($columns, $currentUser);
        $db->_insert($table, $columns);
        echo "Attempting to log...";
        $db->_log($action, $table, array_get($columns, 'title', ''), $currentUser->id);
        if ($table == "columns") {
            $func->addColumn($table_name, array_get($columns, 'title', ''), $columns['column_type']);
        } else if ($table == "pages") {
            $func->addPage($columns['url_title'], $currentUser->id);
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
        $db->normalizeColumnsForUpdate($columns, $currentUser);
        $db->_update($table, $columns, $where);
        $db->_log($action, $table, array_get($columns, 'title', ''), $currentUser->id);
        break;
    case "delete":
        $db->_delete($table, array("id" => $columns['id']));
        $db->_log($action, $table, array_get($columns, 'title', ''), $currentUser->id);
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
