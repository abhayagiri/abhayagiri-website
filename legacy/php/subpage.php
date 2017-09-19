<?php

if ($subpage instanceof \App\Models\Resident) {
    $subpageTitle = $_lang['resident'];
} else {
    $subpageTitle = tp($subpage, 'title');
}

?>

<legend><?= e($subpageTitle) ?></legend>

<?= tp($subpage, 'body_html') ?>

<div class='backtotop' onclick="backtotop()"><span class='pull-right'><i class='icon-caret-up'> <?= $_lang['back_to_top'] ?></i></span></div>
