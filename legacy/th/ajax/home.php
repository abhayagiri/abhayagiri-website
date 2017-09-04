<!--image-->
<div id="banner">
    <img src="/media/images/banner/faq.jpg">
</div>
<!--/image-->
<div id="content" class="container-fluid">
    <div class="row-fluid">
        <div class="span8">
            <div class='title-black'><i class="icon-bullhorn"></i> ข่าว</div>
            <?php
            $limit = config('settings.home_news_count');
            $data = $db->_query('SELECT * FROM news WHERE status="Open" AND (date < NOW()) ORDER BY date DESC LIMIT ' . $limit);
            foreach ($data as $row) {
                ?>
                <p>
                    <a class="title" href="<?php echo $_lang['base'] ?>/news/<?= $row['url_title'] ?>" onclick="navEntry('news', '<?= $row['url_title'] ?>');
                            return false;"><?= $row['title'] ?></a><br>
                    <?= $func->display_date($row['date']) ?><br>
                </p>
                <p>
                    <?php echo App\Text::abridge($row['body'], 750) ?>
                </p>
            <?php }
            ?>
            <a class="btn viewmore" href="/news" onclick="nav('news');
                        return false;">
                <i class="icon-share-alt"></i>
                ต่อไป
            </a>
            <hr class="border-home">
        </div>
        <div class="span4 item">
            <div class='title-black'><i class="icon-calendar"></i> ปฏิทิน</div>

            <div id="latest-event-list">

            </div>
                   <a class="btn viewmore" href="/calendar" onclick="nav('calendar');
                        return false;">
                    <i class="icon-share-alt"></i>
                    View Full Calendar
                </a>
        </div>
    </div>
    <hr>
    <div class="row-fluid">


        <div class='span8'>
            <div class='title-black'><i class="icon-leaf"></i> แง่ธรรมใหม่ล่าสุด</div>
            <?php
            $data = $db->_query('SELECT * FROM reflections WHERE status="Open" AND (date < NOW()) ORDER BY date DESC LIMIT 1 ');
            foreach ($data as $row) {
                $title = $row['title'];
                $url_title = $row['url_title'];
                $date = $func->display_date($row['date']);
                $body = $row['body'];
                $author = $row['author'];
                $img = $func->getAuthorImagePath($author);
                ?>
                <p>
                    <span class='title'>
                        <a href='/reflections/<?= $url_title ?>' onclick="navEntry('reflections', '<?= $url_title ?>');
                            return false;"><?= $title ?></a>
                    </span>
                    <br><?= $author ?>
                </p>
                <p>
                    <?= $func->abridge($body, 525) ?>
                </p>
            <?php }
            ?>
            <a class="btn" href="/reflections" onclick="nav('reflections');
                        return false;">
                <i class="icon-share-alt"></i>
                ต่อไป
            </a>
            <hr class="border-home">
        </div>
        <div class="span4 item">
            <div class='title-black'><i class="icon-volume-up"></i> เสียงธรรมใหม่ล่าสุด</div>
            <?php
                $talk = \App\Models\Talk::where('status', 'open')
                    ->orderBy('date', 'desc')->first();
            ?>
            <p>
                <a class="title" href="<?= e($talk->getPath('th')) ?>">
                    <?= e($talk->title) ?>
                </a><br>
                <?= e($talk->author->title) ?>
                <?= e($talk->getLocalizedDate()) ?>
            </p>
            <p>
                <?= $talk->getSummaryHtml() ?>
            </p>
            <p>
                <a class='btn' href="/new/talks">
                    <i class="icon-share-alt"></i>
                    ต่อไป
                </a>
            </p>
            <hr class="border-home">
        </div>
    </div>
            <hr>
        <div class="row-fluid">
        <div class='span12'>
            <div class='title-black'><i class="icon-wrench"></i> การก่อสร้างศาลา</div>
            <?php
             $data = $db->_query('SELECT * FROM construction WHERE status="Open" AND (date < NOW()) ORDER BY date DESC LIMIT 1 ');
            foreach ($data as $row) {
                $body = str_replace('&nbsp;', ' ', $row['body']);
                ?>
                <p>
                    <a class="title" href="/news/<?= $row['url_title'] ?>" onclick="navEntry('construction', '<?= $row['url_title'] ?>');
                            return false;"><?= $row['title'] ?></a><br>
                    <?= $func->display_date($row['date']) ?><br>
                </p>
                <div style="margin-bottom:10px">
                    <?= $func->abridge($body, 650) ?>
                </div>
            <?php }
            ?>
            <p>
                <a class="btn" href="/news" onclick="nav('construction');
                        return false;">
                    <i class="icon-share-alt"></i>
                    Read More
                </a>
            </p>
        </div>
    </div>
</div>
