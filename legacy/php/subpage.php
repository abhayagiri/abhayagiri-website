<?php

if (empty($row)) {
    $row = \App\Models\Subpage::getLegacyAjax($_page, $_subpage);
    if (!$row) {
        abort(404);
    }
}

?>

<legend><?= e($row['title']) ?></legend>

<?= $row['body'] ?>

<?php

$idSourceMap = [
    3 => 'residents',
    13 => 'danalist',
    15 => 'schedule',
    32 => 'faq',
];

$source = array_get($idSourceMap, $row['id']);

if ($source) {
    include("$_base/ajax/$source.php");
}

?>

<div class='backtotop' onclick="backtotop()"><span class='pull-right'><i class='icon-caret-up'> <?= $_lang['back_to_top'] ?></i></span></div>
