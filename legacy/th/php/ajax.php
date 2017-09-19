<?php

require base_path('legacy/bootstrap.php');
require base_path('legacy/th/php/main.php');

$ajax = true;

/* ------------------------------------------------------------------------------
  Page and Subpage
  ------------------------------------------------------------------------------ */
if ($_GET['_page']) {
    $_page = $_GET['_page'];
    $stmt = $func->page($_page);
    $_page_title = $stmt["thai_title"];
    $_type = $stmt["display_type"];
    $_icon = $stmt["icon"];
}
if ($_GET['_subpage']) {
    $_subpage = $_GET['_subpage'];
    $_subpage_title = $func->title_case($_subpage);
    $_type = "Standard";
}
if ($_GET['_entry']) {
    $_entry = $_GET['_entry'];
    $_type = "Entry";
} else if ($_GET['_album']) {
    $_album = $_GET['_album'];
    $_type = "Album";
} else if ($_GET['_event']) {
    $_event = $_GET['_event'];
    $_type = "Event";
}
switch ($_type) {
    case "Table":
        include("$_base/php/table.php");
        break;
    case "Standard":
        include("$_base/php/standard.php");
        break;
    case "Custom":
        if ($_page == "home" || $_page == "contact") {
            include("$_base/th/ajax/$_page.php");
        } else {
            include("$_base/ajax/$_page.php");
        }
        break;
    case "Entry":
        if ($_entry == "request") {
            include("$_base/th/ajax/request.php");
        } else {
            include("$_base/php/entry.php");
        }
        break;
    case "Album":
        include("$_base/ajax/album.php");
        break;
    case "Event":
        include("$_base/ajax/event.php");
        break;
    default:
        include("$_base/th/ajax/404.php");
}
