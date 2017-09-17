<?php

require base_path('legacy/bootstrap.php');
require base_path('legacy/php/main.php');

use App\Legacy;

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
        $this->_db = App\Legacy\DB::getPDOConnection();
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
            $sWhere .= " AND (`title` LIKE :search1 OR `body` LIKE :search2)";
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

class NewTableData
{
    public function __construct($_language, $_lang) {
        $this->_language = $_language;
        $this->_lang = $_lang;
    }

    public function printDatatables($class)
    {
        $table = str_plural(strtolower(array_slice(explode('\\', $class), -1)[0]));
        $_language = $this->_language;
        $_lang = $this->_lang;
        list($models, $output) = call_user_func([$class, 'getLegacyDatatables'], $_GET);
        foreach ($models as $key => $model) {
            $row = $model->toLegacyArray($_language);
            $data = include base_path("legacy/ajax/format_$table.php");
            $output['aaData'][] = [ $data ];
        }
        echo json_encode($output);
     }
}

// Create instance of TableData class
if (!isset($_language)) {
    $_language = 'English';
}
$table_data = new TableData($_language);
$newTableData = new NewTableData($_language, $_lang);
$page = $_REQUEST['page'];

switch ($page) {
    case 'books':
        $newTableData->printDatatables(\App\Models\Book::class);
        return;
    case 'news':
        $newTableData->printDatatables(\App\Models\News::class);
        return;
    case 'reflections':
        $newTableData->printDatatables(\App\Models\Reflection::class);
        return;
    case 'construction':
        // Legacy style...for construction?
        $cols = array();
        break;
    default:
        return;
}

$std = array('id', 'title', 'url_title', 'date', 'body');
$table_data->get($page, 'id', array_merge($std, $cols), $func, $_lang);
