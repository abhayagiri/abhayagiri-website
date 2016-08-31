<?php

$stmt = $func->subpage($_subpage);
$source = $stmt[0]['source'];
$body = $stmt[0]['body'];
$title = $stmt[0]['title'];
?>
<legend><?= $title ?></legend>

<?php if ($source == 0) {
    echo $body;
} else {
    $stmt = $db->_select('pages', 'url_title', array('id' => $source));
    $source = $stmt[0]['url_title'];
    include("$_base/ajax/$source.php");
}
?>
<div class='backtotop' onclick="backtotop()"><span class='pull-right'><i class='icon-caret-up'> <?= $_lang['back_to_top'] ?></i></span></div>
