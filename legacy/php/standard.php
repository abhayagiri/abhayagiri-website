<?php

$subpage = \App\Models\Subpage::getLegacySubpage($_page, $_subpage, $_subsubpage);
if (!$subpage) {
    abort(404);
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
            <li>
                <a href="<?= e($_lang['base']) ?>/">
                    <?= e($_lang['home']) ?>
                </a>
                <span class="divider">/</span>
            </li>
            <li>
                <a href="<?= e($_lang['base']) ?>/<?= e($_page) ?>">
                    <?= e($_page_title) ?>
                </a>
                <span class="divider">/</span>
            </li>
            <?php foreach ($subpage->breadcrumbs as $breadcrumb) { ?>
                <li class="<?= $breadcrumb->last ? 'active' : '' ?>">
                    <?php if ($breadcrumb->last) { ?>
                        <?= e(tp($breadcrumb, 'title')) ?>
                    <?php } else { ?>
                        <a href="<?= e($breadcrumb->path) ?>">
                            <?= e(tp($breadcrumb, 'title')) ?>
                        </a>
                        <span class="divider">/</span>
                    <?php } ?>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>

<!--/image-->
<!--body-->
<div id="content" class="container-fluid">
    <div class="row-fluid">
        <div class="span2">
            <div id='subnav' class="well" style="padding: 8px 0;">
                <ul class="nav nav-list">
                    <?php foreach ($subpage->subnav as $subnav) { ?>
                        <li class="<?= $subnav->active ? 'active' : '' ?>">
                            <a href="<?= e($subnav->path) ?>">
                                <?= e(tp($subnav, 'title')) ?>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
        <div id="subpage" class="span10">
            <?php require(base_path('legacy/php/subpage.php')); ?>
        </div>
    </div>
</div>
