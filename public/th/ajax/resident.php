<?php
$stmt = $db->_select('residents', '*', array("url_title" => $_resident));
$stmt = $stmt[0];
?>
<!--image-->
<div id="banner">
    <div class="title"><?= "<i class='icon icon-group'></i> {$_lang['community']}" ?></div>
    <img src="/media/images/banner/community.jpg">
</div>

<div id="breadcrumb-container">
    <div class="container-fluid">
        <ul class="breadcrumb">
            <li><a href="<?= $_lang['base'] ?>/" onclick="nav('home');
                    return false;"><?= $_lang['home'] ?></a> <span class="divider">/</span></li>
            <li><a href='<?= $_lang['base'] ?>/<?= $_lang['community'] ?>' onclick="nav('community');
                    return false;"><?= $_lang['community'] ?></a><span class="divider">/</span></li>
            <li><a href='<?= $_lang['base'] ?>/<?= $_lang['community'] ?>/residents-thai' onclick="navSub('community', 'residents-thai', '<?= $_lang['resident'] ?>');
                    return false;"><?= $_lang['resident'] ?></a><span class="divider">/</span></li>
            <li id="breadcrumb" class="active"><?= $stmt['title'] ?></li>
        </ul>
    </div>
</div>
<!--/image-->
<!--body-->
<div id="content" class="container-fluid">
    <div class="content row-fluid">

        <div class='media residents'>
            <span class='pull-left'>
                <img class='media-object img-residents' src="/media/images/residents/<?= $stmt['photo']; ?>">
            </span>
            <div class='media-body'>
                <span class='bold'>
                    <?= $stmt['title'] ?>
                </span>
                <br><br><?= $stmt['body']; ?>
            </div>
        </div>
        <div class='backtotop' onclick="backtotop()">
            <span class='pull-right'>
                <i class='icon-caret-up'></i>
                <?= $_lang['back_to_top'] ?>
            </span>
        </div>
    </div>
</div>


