<!--image-->
<div id="banner">
    <img src="/media/images/banner/faq.jpg">
</div>
<!--/image-->
<div id="content" class="container-fluid">
    <div class="row-fluid">
        <div class="span8">
            <div class='title-black'><i class="icon-bullhorn"></i> News</div>
            <?
            $data = $func->entry('news',2);
            foreach ($data as $row) {
                $body = str_replace('&nbsp;', ' ', $row['body']);
                $body = preg_replace("/<img([^>]+)\>/i", "", $body); 
                ?>
                <p>
                    <a class="title" href="/news/<?= $row['url_title'] ?>" onclick="navEntry('news', '<?= $row['url_title'] ?>');
                            return false;"><?= $row['title'] ?></a><br>
                    <?= $func->display_date($row['date']) ?><br>
                </p>
                <div style="margin-bottom:10px">
                    <?= $func->abridge($body, 650) ?>
                </div><br>
            <? }
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
            <?
            $data = $func->entry('reflections');
            foreach ($data as $row) {
                $title = $row['title'];
                $url_title = $row['url_title'];
                $date = $func->display_date($row['date']);
                $body = str_replace('&nbsp;', ' ', $row['body']);
                $author = $row['author'];
                $img = '/media/images/speakers/speakers_' . strtolower($func->stripDiacritics(str_replace(' ', '_', $author))) . '.jpg';
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
            <? }
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
            <?
            $data = $func->entry('audio');
            foreach ($data as $row) {
                $title = $row['title'];
                $escaped_title = str_replace("'", "\'", $title);
                $author = $row['author'];
                $date = $func->display_date($row['date']);
                $summary = $func->abridge($row['body'], 200);
                $mp3 = $row['mp3'];
                $img = '/media/images/speakers/speakers_' . strtolower($func->stripDiacritics(str_replace(' ', '_', $author))) . '.jpg';
                ?>
                <p>
                    <a class="title" href="/audio/<?= $row['url_title'] ?>" onclick="navEntry('audio', '<?= $row['url_title'] ?>');
                            return false;"><?= $row['title'] ?></a><br>
                    <?= $author ?><br>
                    <?= $date ?>
                </p>
                <p>
                    <?= $summary ?>
                </p>
                <p>
                <div class="btn-group-home btn-group">
                    <button class='btn' onclick="play(this, <?= "'$escaped_title','$author','$date','$img','$mp3'" ?>);">
                        <i class='icon-play'></i>
                        Play
                    </button>
                    <a href='/media/audio/<?= $mp3 ?>' class='btn'>
                        <i class='icon-cloud-download'></i>
                        Download
                    </a>
                </div>
                <br>
                <a class="btn" href="/audio" onclick="nav('audio');
                            return false;">
                    <i class="icon-share-alt"></i>
                    More Talks
                </a>
                </p>
            <? } ?>
        </div>
    </div>
        <hr>
        <div class="row-fluid">
        <div class='span12'>
            <div class='title-black'><i class="icon-wrench"></i> Reception Hall Construction Updates</div>
            <?
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
            <? }
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
