<?php

$ajax = true;
include('main.php');
/* ------------------------------------------------------------------------------
  Page and Subpage
  ------------------------------------------------------------------------------ */
if ($_POST['_page']) {
    $_page = $_POST['_page'];
    $stmt = $func->page($_page);
    $_page_title = $stmt["thai_title"];
    $_type = $stmt["display_type"];
    $_icon = $stmt["icon"];
}
if ($_POST['_subpage']) {
    $_subpage = $_POST['_subpage'];
    $_subpage_title = $func->title_case($_subpage);
    $_type = "Subpage";
}
if ($_POST['_entry']) {
    $_entry = $_POST['_entry'];
    $_type = "Entry";
} else if ($_POST['_album']) {
    $_album = $_POST['_album'];
    $_type = "Album";
} else if ($_POST['_event']) {
    $_event = $_POST['_event'];
    $_type = "Event";
} else if ($_POST['_resident']) {
    $_resident = $_POST['_resident'];
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
        include("$_base/th/ajax/404.php");
}
?>
