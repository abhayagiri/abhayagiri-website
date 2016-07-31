<?php

include('session.php');
$table = $_REQUEST['table'];
$columns = explode(",", $_REQUEST['columns']);
/* ------------------------------------------------------------------------------
  get_author
  ------------------------------------------------------------------------------ */

function get_author($db, $id) {
    return _select($db, 'authors', 'name', array("id" => $id));
}

/*
 * Script:    DataTables server-side script for PHP and MySQL
 * Copyright: 2012 - John Becker, Beckersoft, Inc.
 * Copyright: 2010 - Allan Jardine
 * License:   GPL v2 or BSD (3-point)
 */

class TableData {

    private $_db;

    public function __construct() {

        try {
            $this->_db = new PDO('', '', '', array(PDO::ATTR_PERSISTENT => true));
        } catch (PDOException $e) {
            error_log("Failed to connect to database: " . $e->getMessage());
        }
    }

    public function fixLength($val) {
        return (strlen($val) > 300) ? mb_substr($val, 0, 300, 'UTF-8') . '...' : $val;
    }

    public function toImg($val, $table) {
        return "<img class='img' src='https://www.abhayagiri.org/media/images/$table/$val'>";
    }

    public function profileImg($img) {
        return "<img class='propic' src='/img/mahaguild/$img'>";
    }

    public function formatDate($val) {
        return date("n/j/y", strtotime($val));
    }

    public function checkField($val) {
        return (strcasecmp($val, "no") == 0 || $val == "") ? "" : "<i class='icon-ok'></i>";
    }

    public function labelAccess($val) {
        $val = explode(',', $val);
        foreach ($val as $key => $row) {
            $val[$key] = "<span class='badge'>$row</span>";
        }
        return implode("<br>", $val);
    }

    public function styleStatus($val) {
        if ($val == "Open") {
            return '<span class="badge badge-success">Open</span>';
        } else {
            return '<span class="badge badge-important">Closed</span>';
        }
    }

    public function get($table, $index_column, $columns) {

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
        $sWhere = "";
        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
            $sWhere = "WHERE (";
            for ($i = 0; $i < count($columns); $i++) {
                if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true") {
                    $sWhere .= "`" . $columns[$i] . "` LIKE :search OR ";
                }
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }

        // Individual column filtering
        for ($i = 0; $i < count($columns); $i++) {
            if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
                if ($sWhere == "") {
                    $sWhere = "WHERE ";
                } else {
                    $sWhere .= " AND ";
                }
                $sWhere .= "`" . $columns[$i] . "` LIKE :search" . $i . " ";
            }
        }

        //MOd
        if ($table != "mahaguild") {
            $columns1 = str_replace(" , ", " ", implode(",", $columns));
            $columns2 = array();
            foreach ($columns as $key => $row) {
                $columns2[$key] = "$table.$row";
            }
            $columns2 = str_replace(" , ", " ", implode(",", $columns2));
            // SQL queries get data to display
            $sQuery = "
        SELECT SQL_CALC_FOUND_ROWS id,$columns1,name FROM
        (SELECT $table.id,$columns2,mahaguild.title AS name
        FROM $table LEFT JOIN mahaguild ON mahaguild.id = $table.user
        ) sel $sWhere $sOrder $sLimit";
            $statement = $this->_db->prepare($sQuery);
        } else {
            $sQuery = "SELECT SQL_CALC_FOUND_ROWS `" . str_replace(" , ", " ", implode("`, `", $columns)) . "`,`id` FROM `" . $table . "` " . $sWhere . " " . $sOrder . " " . $sLimit;
            $statement = $this->_db->prepare($sQuery);
        }
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
        foreach ($rResult as $aRow) {
            $row = array();
            for ($i = 0; $i < count($columns); $i++) {
                $col = $columns[$i];
                $val = $aRow[$columns[$i]];
                switch ($col) {
                    case "id":
                        break;
                    case "img" :
                    case "cover":
                    case "photo":
                        $row[] = $this->toImg($val, $table);
                        break;
                    case "avatar" :
                        $row[] = $this->profileImg($val);
                        break;
                    case "date":
                        $row[] = $this->formatDate($val);
                        break;
                    case "epub":
                    case "mobi":
                    case "mp3":
                    case "request":
                    case "pdf":
                        $row[] = $this->checkField($val);
                        break;
                    case "access":
                        $row[] = $this->labelAccess($val);
                        break;
                    case "icon":
                        $row[] = $this->toIcon($val);
                        break;
                    case "user":
                        $row[] = $aRow['name'];
                        break;
                    case "status":
                        $row[] = $this->styleStatus($val);
                        break;
                    default:
                        $row[] = $this->fixLength($val);
                        break;
                }
            }
            $id = $aRow['id'];
            $edit = "<button class='btn btn-small btn-primary' onclick='navEdit($id)'><i class='icon-pencil'></i> Edit</button>";

            $row[] = $edit;
            $output['aaData'][] = $row;
        }

        echo json_encode($output);
    }

}

header('Pragma: no-cache');
header('Cache-Control: no-store, no-cache, must-revalidate');

// Create instance of TableData class
$table_data = new TableData();

// Get the data
$table_data->get($table, 'id', $columns);

