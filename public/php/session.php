<?php

require_once __DIR__ . '/../www-bootstrap.php';

$db = Abhayagiri\DB::getDB();
$action = $_POST['action'];
$book = $_POST['book'];
$quantity = $_POST['quantity'];

switch ($action) {

    case "add":
        echo "adding $book";
        //$db->_insert("request", array("post" => $book, "email" => "session"));
        if (!isset($_SESSION['books'])) {
        	echo "establishing array";
            //initialize
            $_SESSION['books'] = array();
            $_SESSION['books'][0]['id'] = $book;
            $_SESSION['books'][0]['quantity'] = 1;
        } else {
            //increment
            $books = $_SESSION['books'];
            $length = count($books);
            $key = multi_array_search($book, $books);
            if ($key > -1) {
            	echo "incrementing quantity";
                $_SESSION['books'][$key]['quantity']++;
            } else {
            	echo "case first add";
                $_SESSION['books'][$length]['id'] = $book;
                $_SESSION['books'][$length]['quantity'] = 1;
            }
        }
        break;
    case "remove":
        echo "removing $book";
        $key = multi_array_search($book, $_SESSION['books']);
        echo $key;
        unset($_SESSION['books'][$key]);
        $_SESSION['books'] = array_values($_SESSION['books']);
        break;
    case "reset":
        $_SESSION['books'] = array();
        break;
    case "update":
        echo "updating quantity of $book to $quantity";
        $key = multi_array_search($book, $_SESSION['books']);
        $_SESSION['books'][$key]['quantity'] = $quantity;
        break;
}

function multi_array_search($needle, $array) {
    foreach ($array as $key => $row) {
        if ($needle == $row['id']) {
            return $key;
        }
    }
    return -1;
}

?>
