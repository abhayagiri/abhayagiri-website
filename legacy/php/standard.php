<?php

$path = \Request::path();
$redirect = \App\Models\Redirect::getRedirectFromPath($path);
if ($redirect) {
    throw new \App\Legacy\RedirectException($redirect);
}

list($row, $stmt) = \App\Models\Subpage::getLegacyStatement($_page, $_subpage);
if (!$row) {
    abort(404);
}
$_subpage = $row['url_title'];
$_subpage_title = $row['title'];

$subnav = "";
foreach ($stmt as $r) {
    if ($_subpage === $r['url_title']) {
        $class = "class='active'";
        echo "<script>var _subpage='{$_subpage}';</script>"; //SO CHEAP :(
    } else {
        $class = "";
    }
    $subnav .= '<li id="' . e($r['url_title']) . '" ' . $class . '>' .
        '<a href="' . e($r['path']) . '" onclick="navSub(\'' .
        e($r['page']) . "','" . e($r['url_title']) . "','" . e($r['title']) .
        '\'); return false;"> ' . e($r['title']) . '</a></li>';
}

?>

<!--image-->
<div id="banner">
    <div class="title"><i class="<?= e($_icon) ?>"></i> <?= e($_page_title) ?></div>
    <img src="/media/images/banner/<?= e($_page) ?>.jpg">
</div>
<div id="breadcrumb-container">
    <div class="container-fluid">
        <ul class="breadcrumb">
            <li><a href="<?= e($_lang['base']) ?>/" onclick="nav('home');
                    return false;"><?= e($_lang['home']) ?></a> <span class="divider">/</span></li>
            <li><a href="<?= e($_lang['base']) ?>/<?= e($_page) ?>" onclick="nav('<?= e($_page) ?>');
                    return false;"><?= e($_page_title) ?></a><span class="divider">/</span></li>
            <li id="breadcrumb" class="active"><?= e($_subpage_title) ?></li>
        </ul>
    </div>
</div>

<!--/image-->
<!--body-->
<div id="content" class="container-fluid">
    <div class="row-fluid">
        <div class="span2">
            <div id='subnav' class="well" style="padding: 8px 0;">
                <ul class="nav nav-list ">
                    <?= $subnav ?>
                </ul>
            </div>
        </div>
        <div id="subpage" class="span10">
            <?php
            include('subpage.php');
            ?>
        </div>
    </div>
</div>