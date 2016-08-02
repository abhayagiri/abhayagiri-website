<?php

require_once __DIR__ . '/../www-bootstrap.php';

$stmt = $func->submenu($_page);
$subnav = "";
if ($_subpage == "") {
    $row = reset($stmt);
    $_subpage = $row['url_title'];
    $_subpage_title = $row['title'];
}
foreach ($stmt as $key => $row) {
    $title = $row['title'];
    $url_title = $row['url_title'];
    if ($_subpage == $url_title) {
        $class = "class='active'";
        $_subpage_title = $row['title'];
        echo "<script>var _subpage='{$_subpage}';</script>"; //SO CHEAP :(
    } else {
        $class = "";
    }
    $subnav.="<li id='$url_title' $class><a href=\"{$_lang['base']}/$_page/$_subpage\" onclick=\"navSub('$_page','$url_title','$title');return false;\"> $title</a></li>";
}
?>

<!--image-->
<div id="banner">
    <div class="title"><?= "<i class='$_icon'></i> $_page_title" ?></div>
    <img src="/media/images/banner/<?= $_page ?>.jpg">
</div>
<div id="breadcrumb-container">
    <div class="container-fluid">
        <ul class="breadcrumb">
            <li><a href="<?= $_lang['base'] ?>/" onclick="nav('home');
                    return false;"><?= $_lang['home'] ?></a> <span class="divider">/</span></li>
            <li><a href='<?= "{$_lang['base']}/{$_page}" ?>' onclick = "nav('<?= $_page ?>');
                    return false;"><?= $_page_title ?></a><span class="divider">/</span></li>
            <li id="breadcrumb" class="active"><?= $_subpage_title ?></li>
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
            <?
            include('subpage.php');
            ?>
        </div>
    </div>
</div>