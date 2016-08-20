<?php

/*
 * Script:    DataTables server-side script for PHP and MySQL
 * Copyright: 2012 - John Becker, Beckersoft, Inc.
 * Copyright: 2010 - Allan Jardine
 * License:   GPL v2 or BSD (3-point)
 */

class TableData {

    private $_db;

    public function __construct() {
        $this->_db = Abhayagiri\DB::getPDOConnection();
    }

    public function get($table, $index_column, $columns, $func) {
        // Paging
        $sLimit = "";
        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $sLimit = "LIMIT " . intval($_GET['iDisplayStart']) . ", " . intval($_GET['iDisplayLength']);
        }


        $sWhere = " WHERE (title LIKE :search OR author LIKE :search) AND (status = 'Open') AND (date <= NOW())";
        /* UNION ALL
          SELECT 'news' AS page,title,url_title,date,status,body,null FROM news $sWhere AND (title LIKE :search)
          UNION ALL
          SELECT 'news' AS page,title,url_title,date,status,body,author FROM news $sWhere AND (body LIKE :search)
          UNION ALL
          SELECT 'audio' AS page,title,url_title,date,status,body,author FROM audio $sWhere AND (title LIKE :search)
          UNION ALL
          SELECT 'audio' AS page,title,url_title,date,status,body,author FROM audio $sWhere AND (author LIKE :search OR body LIKE :search)
          UNION ALL
          SELECT 'books' AS page,title,url_title,date,status,body,author FROM books $sWhere AND (title LIKE :search)
          UNION ALL
          SELECT 'books' AS page,title,url_title,date,status,body,author FROM books $sWhere AND (author LIKE :search OR body LIKE :search)
          UNION ALL
          SELECT 'reflections' AS page,title,url_title,date,status,body,author FROM reflections $sWhere AND (title LIKE :search)
          UNION ALL
          SELECT 'reflections' AS page,title,url_title,date,status,body,author FROM reflections $sWhere AND (author LIKE :search OR body LIKE :search)
          UNION ALL
          SELECT 'residents' AS page,title,url_title,date,status,body,null FROM residents $sWhere AND (title LIKE :search)
          UNION ALL
          SELECT 'residents' AS page,title,url_title,date,status,body,author FROM residents $sWhere AND (body LIKE :search) */
        // SQL queries get data to display
        //$title = $func->searchable('title', $_GET['sSearch']);
        //$body = $func->searchable('body', $_GET['sSearch']);
        $sQuery = "SELECT SQL_CALC_FOUND_ROWS `" . str_replace(" , ", " ", implode("`, `", $columns)) . "` FROM `" . $table . "` " . $sWhere . " " . $sOrder . " " . $sLimit;
        $statement = $this->_db->prepare($sQuery);
        //echo $sQuery;
        //$statement->bindValue(':search', $func->searchable($_GET['sSearch']), PDO::PARAM_STR);
        $statement->bindValue(':search', '%' . $_GET['sSearch'] . '%', PDO::PARAM_STR);
        //echo $func->searchable($_GET['sSearch']);
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

        if (isset($_GET['sSearch']) && $_GET['sSearch'] != '') {
            $sSearch = $_GET['sSearch'];
        }

        // Return array of values
        foreach ($rResult as $row) {
            $aRow = array();
            $title = $row['title'];
            $url_title = $row['url_title'];
            $author = $func->highlight($sSearch, $row['author']);
            //$body = $func->searchLength($func->highlight($sSearch, strip_tags($row['body'])));
            $body = $func->abridge(strip_tags($row['body']));
            switch ($row['page']) {
                case "books":
                case "audio":
                case "reflections":
                case "news":
                    $page = $row['page'];
                    $href = "href='/$page/$url_title' onclick='navEntry(\"$page\",\"$url_title\");closeSearch();return false;'";
                    $url = "www.abhayagiri.org/$page/$url_title";
                    break;
                case "pages":
                    $page = lcfirst($row['title']);
                    $href = "href='/$page' onclick='nav(\"$page\");closeSearch();return false;'";
                    $url = "www.abhayagiri.org/$page";
                    break;
                case "residents":
                    $page = 'community';
                    $href = "href='/community/residents' onclick='navSub(\"community\",\"residents\");closeSearch();return false;'";
                    $url = "www.abhayagiri.org/community/residents";
                default:
                    $page = $row['page'];
                    $href = "href='/$page/$url_title' onclick='navSub(\"$page\",\"$url_title\");closeSearch();return false;'";
                    $url = "www.abhayagiri.org/$page/$url_title";
                    break;
            }
            $data = "<a $href class='title' >$title</a>";
            $data .= "<div class='url'>$url</div>";
            if ($author) {
                $data.="<div class='muted'>$author</div>";
            }
            $data .= "<div class='desc'>$body</div>";
            //$data .= "<div class='muted'>$url_title</div>";
            $data .= "<hr class = 'border'>";
            $aRow[] = $data;
            $output['aaData'][] = $aRow;
        }


        $stmt = $func->google_calendar();
        foreach ($stmt->event as $event) {
            $aRow = array();
            $title = stripslashes($event->title);
            $body = $func->searchLength($func->highlight($sSearch, stripslashes($event->description)));
            $date = $func->display_date(stripslashes($event->starttime));
            $data = "<a href='/calendar' onclick='nav(\"calendar\");return false;)' class='title' >$title</a>";
            $data .= "<div class='url'>www.abhayagiri.org/calendar</div>";
            $data.="<div class='muted'>$date</div>";
            $data .= "<div class='desc'>$body</div>";
            $data .= "<hr class = 'border'>";
            if (stristr($data, $sSearch)) {
                $aRow[] = $data;
                $output['aaData'][] = $aRow;
            }
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
$std = array('page', 'title', 'url_title', 'date', 'body', 'author', 'status');
$table_data->get('search', 'id', $std, $func);
?>