<?php

$ajax = true;

require_once __DIR__.'/../public/php/main.php';

/* ------------------------------------------------------------------------------
  Page and Subpage
  ------------------------------------------------------------------------------ */
if ($_GET['_page']) {
    $_page = $_GET['_page'];
    $_page_title = ucfirst($_page);
    $stmt = $func->page($_page);
    $_type = $stmt["display_type"];
    $_icon = $stmt["icon"];
}
if ($_GET['_subpage']) {
    $_subpage = $_GET['_subpage'];
    $_subpage_title = $func->title_case($_subpage);
    $_type = "Subpage";
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
} else if ($_GET['_resident']) {
    $_resident = $_GET['_resident'];
    $_type = "Resident";
}

switch ($_type) {
    case "Table":
        include("$_base/php/table.php");
        break;
    case "Standard":
        include("$_base/php/standard.php");
        break;
    case "Custom":
        include("$_base/ajax/$_page.php");
        break;
    case "Entry":
        if ($_entry == "request") {
            include("$_base/ajax/request.php");
        } else {
            include("$_base/php/entry.php");
        }
        break;
    case "Subpage":
        include("$_base/php/subpage.php");
        break;
    case "Album":
        include("$_base/ajax/album.php");
        break;
    case "Event":
        include("$_base/ajax/event.php");
        break;
    case "Resident":
        include("$_base/ajax/resident.php");
        break;
    default:
        include("$_base/ajax/404.php");
}
?>
