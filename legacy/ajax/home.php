<!--image-->
<div id="banner">
    <img src="/media/images/banner/faq.jpg">
</div>
<!--/image-->
<div id="content" class="container-fluid">
    <div class="row-fluid">
        <div class="span8">
            <div class='title-black'><i class="icon-bullhorn"></i> News</div>
            <?php
            $data = $func->entry('news', config('settings.home_news_count'));
            foreach ($data as $row) {
                ?>
                <p>
                    <a class="title" href="/news/<?= $row['url_title'] ?>" onclick="navEntry('news', '<?= $row['url_title'] ?>');
                            return false;"><?= $row['title'] ?></a><br>

                </p>
                <div style="margin-bottom:10px">
                    <?php echo App\Text::abridge($row['body'], 750) ?><br/>
                    <i>Posted on <?= $func->display_date($row['date']) ?></i>
                </div><br>
            <?php }
            ?>
            <p>
                <a class="btn" href="/news" onclick="nav('news');
                        return false;">
                    <i class="icon-share-alt"></i>
                    More News
                </a>
            </p>
            <hr class="border-home">
        </div>
        <div class="span4 item">
            <div class='title-black'><i class="icon-calendar"></i> Calendar</div>
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
            <div class='title-black'><i class="icon-leaf"></i> Latest Reflection</div>
            <?php
            $data = $func->entry('reflections');
            foreach ($data as $row) {
                $title = $row['title'];
                $url_title = $row['url_title'];
                $date = $func->display_date($row['date']);
                $body = str_replace('&nbsp;', ' ', $row['body']);
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
                <div style="margin-bottom:10px">
                    <?= $func->abridge($body, 600) ?>
                </div>
            <?php }
            ?><p>
                <a class="btn" href="/reflections" onclick="nav('reflections');
                        return false;">
                    <i class="icon-share-alt"></i>
                    More Reflections
                </a>
            </p>
            <hr class="border-home">
        </div>
        <div class="span4 item">
            <div class='title-black'><i class="icon-volume-up"></i> Latest Talk</div>
            <?php
                $talk = \App\Models\Talk::public()
                    ->latestVisible()->latest()->first();
            ?>
            <p>
                <a class="title" href="<?= e($talk->getPath()) ?>">
                    <?= e($talk->title_en) ?>
                </a><br>
                <?= e($talk->author->title) ?>
                <?= e($talk->recorded_on->format('F j, Y')) ?>
            </p>
            <p>
                <?= $talk->getSummaryHtml() ?>
            </p>
            <p>
                <a class='btn' href="/new/talks">
                    <i class="icon-share-alt"></i>
                    More Talks
                </a>
            </p>
        </div>
    </div>
        <hr>
        <div class="row-fluid">
        <div class='span12'>
            <div class='title-black'><i class="icon-wrench"></i> Reception Hall Construction Updates</div>
            <?php
            $data = $func->entry('construction');
            foreach ($data as $row) {
                $body = str_replace('&nbsp;', ' ', $row['body']);
                ?>
                <p>
                    <a class="title" href="/construction" onclick="nav('construction');
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
                    More Updates
                </a>
            </p>
        </div>
    </div>
</div>
