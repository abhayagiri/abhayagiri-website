<?php
    $resident = DB::table('residents')
        ->get()
        ->where('url_title', '=', $_resident)
        ->first();
?>
<!--image-->
<div id="banner">
    <div class="title"><i class='icon icon-group'></i><?= e($_lang['community']) ?></div>
    <img src="/media/images/banner/community.jpg">
</div>

<div id="breadcrumb-container">
    <div class="container-fluid">
        <ul class="breadcrumb">
            <li><a href="<?= e($_lang['base']) ?>/" onclick="nav('home');
                    return false;"><?= e($_lang['home']) ?></a> <span class="divider">/</span></li>
            <li><a href='<?= e($_lang['base']) ?>/community' onclick="nav('community');
                    return false;"><?= e($_lang['community']) ?></a><span class="divider">/</span></li>
            <li><a href='<?= e($_lang['base']) ?>/community/residents' onclick="navSub('community', 'residents<?= $_language == 'Thai' ? '-thai' : '' ?>', '<?= e($_lang['resident']) ?>');
                    return false;"><?= e($_lang['resident']) ?></a><span class="divider">/</span></li>
            <li id="breadcrumb" class="active"><?= e($resident->title) ?></li>
        </ul>
    </div>
</div>
<!--/image-->
<!--body-->
<div id="content" class="container-fluid">
    <div class="content row-fluid">
        <?php include("$_base/ajax/resident_row.php") ?>
    </div>
</div>
