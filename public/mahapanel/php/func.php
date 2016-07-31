<?php

class Func {

    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function addColumn($table, $column, $type) {
        $type = $this->getType($type);
        $stmt = $this->db->_alter('add', $table, $column, $type);
    }

    public function deleteColumn($table, $column) {
        $stmt = $this->db->_alter('drop', $table, $column);
    }

    public function updateColumn($table, $column, $type, $column2) {
        $type = $this->getType($type);
        $stmt = $this->db->_alter('change', $table, $column, $type, $column2);
    }

    public function addPage($page, $user) {
        $stmt = $this->db->_create($page);
        $id = $this->getTableId($page);
        $this->addColumn($page, 'title', 'text');
        $this->addColumn($page, 'url_title', 'text');
        $this->addColumn($page, 'user', 'user');
        $this->addColumn($page, 'date', 'date');
        $this->addColumn($page, 'status', 'dropdown');
        $stmt = $this->db->_insert('columns', array("title" => 'title', 'display_title' => 'Title', 'date' => date("Y-m-d H:i:s"), 'column_type' => 'title', 'user' => $user,
            'display' => 'yes', 'parent' => $id));
        $stmt = $this->db->_insert('columns', array("title" => 'url_title', 'display_title' => 'URL Title', 'date' => date("Y-m-d H:i:s"), 'column_type' => 'text', 'user' => $user,
            'display' => 'yes', 'parent' => $id));
        $stmt = $this->db->_insert('columns', array("title" => 'user', 'display_title' => 'Created By', 'date' => date("Y-m-d H:i:s"), 'column_type' => 'user', 'user' => $user,
            'display' => 'yes', 'parent' => $id));
        $stmt = $this->db->_insert('columns', array("title" => 'date', 'display_title' => 'Date', 'date' => date("Y-m-d H:i:s"), 'column_type' => 'date', 'user' => $user,
            'display' => 'yes', 'parent' => $id));
        $stmt = $this->db->_insert('columns', array("title" => 'status', 'display_title' => 'Status', 'date' => date("Y-m-d H:i:s"), 'column_type' => 'dropdown', 'user' => $user,
            'display' => 'yes', 'parent' => $id));
    }

    public function deletePage($id, $page) {
        $stmt = $this->db->_drop($page);
        $stmt = $this->db->_delete('columns', array('parent' => $id));
    }

    public function updatePage($old_title, $new_title) {
        $stmt = $this->db->_rename($old_title, $new_title);
    }

    public function getType($type) {
        switch ($type) {
            case "textarea":
                $type = "mediumtext";
                break;
            case "date":
                $type = "datetime";
                break;
            case "user":
            case "id":
                $type = "int(5)";
                break;
            default:
                $type = "varchar(300)";
                break;
        }
        return $type;
    }

    public function getTableName($id) {
        $stmt = $this->db->_select("pages", "url_title", array("id" => $id));
        return $stmt[0]['url_title'];
    }

    public function getColumnName($id) {
        $stmt = $this->db->_select("columns", "title", array("id" => $id));
        return $stmt[0]['title'];
    }

    public function getTableId($url_title) {
        $stmt = $this->db->_select("pages", "id", array("url_title" => $url_title));
        return $stmt[0]['id'];
    }

}
?>

