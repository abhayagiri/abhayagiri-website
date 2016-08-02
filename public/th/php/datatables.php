<?php

require_once __DIR__ . '/../../www-bootstrap.php';

/*
 * Script:    DataTables server-side script for PHP and MySQL
 * Copyright: 2012 - John Becker, Beckersoft, Inc.
 * Copyright: 2010 - Allan Jardine
 * License:   GPL v2 or BSD (3-point)
 */

class TableData {

    private $_db;

    public function __construct() {
        $config = Abhayagiri\Config::get();

        try {
            $this->_db = new PDO($config['db']['dsn'], $config['db']['username'], $config['db']['password'], $config['db']['options']);
        } catch (PDOException $e) {
            error_log("Failed to connect to database: " . $e->getMessage());
        }
    }

    public function get($table, $index_column, $columns, $func, $_lang) {
        // Paging
        $sLimit = "";
        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $sLimit = "LIMIT " . intval($_GET['iDisplayStart']) . ", " . intval($_GET['iDisplayLength']);
        }

        // Ordering
        $sOrder = "ORDER BY date DESC";
        if (isset($_GET['iSortCol_0'])) {
            $sOrder = "ORDER BY  ";
            for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
                if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
                    $sortDir = (strcasecmp($_GET['sSortDir_' . $i], 'ASC') == 0) ? 'ASC' : 'DESC';
                    $sOrder .= "`" . $columns[intval($_GET['iSortCol_' . $i])] . "` " . $sortDir . ", ";
                }
            }

            $sOrder = substr_replace($sOrder, "", -2);
            if ($sOrder == "ORDER BY") {
                $sOrder = "ORDER BY date DESC";
            }
        }

        /*
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */
        if ($table == "audio" || $table == "books") {
            $sWhere = "WHERE (status = 'Open') AND (date <= NOW())";
        } else {
            $sWhere = "WHERE (status = 'Open') AND (date <= NOW())";
        }
        switch ($_GET['sSearch_0']) {
            case "ธรรมเทศนา":
                $_GET['sSearch_0'] = "Dhamma Talk";
                break;
            case "เสียงสวดมนต์":
                $_GET['sSearch_0'] = "Chanting";
                break;
            case "เทศน์ในกรรมฐาน":
                $_GET['sSearch_0'] = "Retreat";
                break;
            case "หนังสือให้เปล่า":
                $_GET['sSearch_0'] = "Print Copy";
                break;
        }
        switch ($_GET['sSearch_1']) {
            case "ภาษาไทย":
                $_GET['sSearch_1'] = "Thai";
                break;
            case "ภาษาอังกฤษ":
                $_GET['sSearch_1'] = "English";
                break;
        }
        // Individual column filtering
         if ($_GET['sSearch'] != '') {
            if($table == "audio" || $table=="books" || $table=="reflections"){
                 $sWhere .= " AND (`title` LIKE :search OR `author` LIKE :search OR `body` LIKE :search)";   
            }else{
                 $sWhere .= " AND (`title` LIKE :search OR `body` LIKE :search)";   
            }
        }
        if ($_GET['sSearch_0'] != '' && $_GET['sSearch_0'] != 'ทั้งหมด') {
            if ($table == "audio") {
                $sWhere .= " AND (`category` LIKE :search0)";
            } else if ($table == "books") {
                $sWhere .= " AND (pdf LIKE :search0 OR epub LIKE :search0 OR mobi LIKE :search0 OR request LIKE :search0)";
            }
        }
        if ($_GET['sSearch_1'] != '') {
            if ($_GET['sSearch_1'] == "English") {
                $sWhere .= " AND (language='English')";
            } else {
                $sWhere .= " AND (language LIKE '%Thai%')";
            }
        } else if ($table != "reflections" && $table != "news") {
            $sWhere .= " AND (language!='English')";
        }


        // SQL queries get data to display
        $sQuery = "SELECT SQL_CALC_FOUND_ROWS `" . str_replace(" , ", " ", implode("`, `", $columns)) . "` FROM `" . $table . "` " . $sWhere . " " . $sOrder . " " . $sLimit;
        $statement = $this->_db->prepare($sQuery);

        // Bind parameters
        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
            $statement->bindValue(':search', '%' . $_GET['sSearch'] . '%', PDO::PARAM_STR);
        }
        for ($i = 0; $i < count($columns); $i++) {
            if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
                $statement->bindValue(':search' . $i, '%' . $_GET['sSearch_' . $i] . '%', PDO::PARAM_STR);
            }
        }

        $statement->execute();
        $rResult = $statement->fetchAll();

        $iFilteredTotal = current($this->_db->query('SELECT FOUND_ROWS()')->fetch());

        // Get total number of rows in table
        $sQuery = "SELECT COUNT(`" . $index_column . "`) FROM `" . $table . "`";

        $iTotal = current($this->_db->query($sQuery)->fetch());

        // Output
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );

        // Return array of values
        foreach ($rResult as $key => $row) {
            $aRow = array();
            $id = $row['id'];
            $title = $row['title'];
            $url_title = $row['url_title'];
            $date = $func->display_date($row['date']);
            if ($key > 0 && $table != "books" && $table != "audio") {
                $body = $func->fixLength($row['body'], 600);
                $body .= "<br><br>
            <a class = 'btn' href = '/$table/$url_title' onclick = 'navEntry(\"$table\", \"$url_title\");return false;'>
                {$_lang['read_more']}
                <i class='icon-double-angle-right'></i>
            </a>";
            } else {
                $body = $row['body'];
            }
            $author = $row['author'];
            $data2 = "";
            include($GLOBALS['_base'] . "/ajax/format_$table.php");
            $data .= "<hr class='border'>";
            $aRow[] = $data;
            $aRow[] = "test";
            $output['aaData'][] = $aRow;
        }
        echo json_encode($output);
    }

}

header('Pragma: no-cache');
header('Cache-Control: no-store, no-cache, must-revalidate');

// Create instance of TableData class
$table_data = new TableData();
$page = $_REQUEST['page'];
include('main.php');

switch ($page) {
    case "audio":
        $cols = array('author','mp3', 'category');
        break;
    case "books":
        $cols = array('author','subtitle', 'weight', 'cover', 'pdf', 'epub', 'mobi', 'request');
        break;
    case "reflections":
        $cols = array('author');
        break;
    default:
        $cols = array();
        break;
}
$std = array('id', 'title', 'url_title', 'date', 'body');
$table_data->get($page, 'id', array_merge($std, $cols), $func, $_lang);
?>
