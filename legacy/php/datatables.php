<?php

require base_path('legacy/bootstrap.php');
require base_path('legacy/php/main.php');

/*
 * Script:    DataTables server-side script for PHP and MySQL
 * Copyright: 2012 - John Becker, Beckersoft, Inc.
 * Copyright: 2010 - Allan Jardine
 * License:   GPL v2 or BSD (3-point)
 */

class TableData {

    private $_db;
    private $_language;

    public function __construct($_language) {
        $this->_db = Abhayagiri\DB::getPDOConnection();
        $this->_language = $_language;
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
        $sWhere = "WHERE (status = 'Open') AND (date <= NOW())";
        if ($this->_language != 'Thai') {
            $sWhere .= " AND (language != 'Thai')";
        }

        // Individual column filtering
        $sSearch = trim(array_get($_GET, 'sSearch', ''));
        $bindParameters = [];
        if ($sSearch !== '') {
            $searchString = '%' . $sSearch . '%';
            $bindParameters[] = [':search1', $searchString];
            $bindParameters[] = [':search2', $searchString];
            if ($table == "audio" || $table == "books" || $table == "reflections") {
                $sWhere .= " AND (`title` LIKE :search1 OR `author` LIKE :search2 OR `body` LIKE :search3)";
                $bindParameters[] = [':search3', $searchString];
            } else {
                $sWhere .= " AND (`title` LIKE :search1 OR `body` LIKE :search2)";   
            }
        }

        $category = array_get($_GET, 'sSearch_0', 'All');
        if ($category !== 'All') {
            $searchString = '%' . $category . '%';
            if ($table === 'audio') {
                $sWhere .= " AND (`category` LIKE :search4)";
                $bindParameters[] = [':search4', $searchString];
            } else if ($table === 'books') {
                $sWhere .= " AND (pdf LIKE :search4 OR epub LIKE :search5 OR mobi LIKE :search6 OR request LIKE :search7)";
                $bindParameters[] = [':search4', $searchString];
                $bindParameters[] = [':search5', $searchString];
                $bindParameters[] = [':search6', $searchString];
                $bindParameters[] = [':search7', $searchString];
            }
        }

        // SQL queries get data to display
        $sQuery = "SELECT SQL_CALC_FOUND_ROWS `" . str_replace(" , ", " ", implode("`, `", $columns)) . "` FROM `" . $table . "` " . $sWhere . " " . $sOrder . " " . $sLimit;
        $statement = $this->_db->prepare($sQuery);

        // Bind parameters
        foreach ($bindParameters as $p) {
            $statement->bindValue($p[0], $p[1], PDO::PARAM_STR);
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
            $body = $row['body'];
            $author = array_get($row, 'author', '');
            $data2 = "";

            include base_path("legacy/ajax/format_$table.php");
            $data .= "<hr class='border'>";
            $aRow[] = $data;
            $output['aaData'][] = $aRow;
        }
        echo json_encode($output);
    }

}

// Create instance of TableData class
if (!isset($_language)) {
    $_language = 'English';
}
$table_data = new TableData($_language);
$page = $_REQUEST['page'];

switch ($page) {
    case "audio":
        $cols = array('author','mp3', 'category');
        break;
    case "books":
        $cols = array('author', 'subtitle', 'weight', 'cover', 'pdf', 'epub', 'mobi', 'request');
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
