<?php $i18n = $_lang['i18next']['home']; ?>
<!--image-->
<div id="banner">
    <img src="/media/images/banner/faq.jpg">
</div>
<!--/image-->
<div id="content" class="container-fluid">
    <div class="row-fluid">
        <div class="span8">
            <div class='title-black'><i class="icon-bullhorn"></i> <?= $i18n['news'] ?></div>
            <?php foreach (\App\Models\News::getLegacyHomeNews($_language) as $row) { ?>
                <p>
                    <a class="title" href="<?= $_lang['base'] ?>/news/<?= e($row['url_title']) ?>" onclick="navEntry('news', '<?= e($row['url_title']) ?>');
                            return false;">
                        <?= e($row['title']) ?>
                    </a>
                </p>
                <div>
                    <?= \App\Text::abridge($row['body'], 750) ?><br/>
                    <i><?= $_lang['i18next']['common']['posted'] ?> <?= $func->display_date($row['date']) ?></i>
                </div><br>
            <?php } ?>
            <p>
                <a class="btn" href="<?= $_lang['base'] ?>/news" onclick="nav('news');
                        return false;">
                    <i class="icon-share-alt"></i>
                    <?= $i18n['more news'] ?>
                </a>
            </p>
            <hr class="border-home">
        </div>
        <div class="span4 item">
            <div class='title-black'><i class="icon-calendar"></i> <?= $i18n['calendar'] ?></div>
            <div id="latest-event-list">

            </div>
                   <a class="btn viewmore" href="<?= $_lang['base'] ?>/calendar" onclick="nav('calendar');
                        return false;">
                    <i class="icon-share-alt"></i>
                    <?= $i18n['view calendar'] ?>
                </a>
        </div>
    </div>
    <hr>
    <div class="row-fluid">
        <div class='span8'>
            <div class='title-black'><i class="icon-leaf"></i> <?= $i18n['latest reflection'] ?></div>
            <?php $row = \App\Models\Reflection::getLegacyHomeReflection($_language); ?>
            <p>
                <a class="title" href="<?= $_lang['base'] ?>/reflections/<?= e($row['url_title']) ?>" onclick="navEntry('reflections', '<?= e($row['url_title']) ?>');
                        return false;">
                    <?= e($row['title']) ?>
                </a><br>
                <?= e($row['author']) ?><br>
                <?= $func->display_date($row['date']) ?>
            </p>
            <div style="margin-bottom:10px">
                <?= $func->abridge($row['body'], 600) ?>
            </div>
            <p>
                <a class="btn" href="<?= $_lang['base'] ?>/reflections" onclick="nav('reflections');
                        return false;">
                    <i class="icon-share-alt"></i>
                    <?= $i18n['more reflections'] ?>
                </a>
            </p>
            <hr class="border-home">
        </div>
        <div class="span4 item">
            <div class='title-black'><i class="icon-volume-up"></i> <?= $i18n['latest talk'] ?></div>
            <?php $row = \App\Models\Talk::getLegacyHomeTalk($_language); ?>
            <p>
                <a class="title" href="/new<?= $_lang['base'] ?>/talks/<?= e($row['url_title']) ?>">
                    <?= e($row['title']) ?>
                </a><br>
                <?= e($row['author']) ?><br>
                <?= $func->display_date($row['date']) ?>
            </p>
            <p>
                <?= $func->abridge($row['body'], 600) ?>
            </p>
            <p>
                <a class='btn' href="/new<?= $_lang['base'] ?>/talks">
                    <i class="icon-share-alt"></i>
                    <?= $i18n['more talks'] ?>
                </a>
            </p>
        </div>
    </div>
</div>
