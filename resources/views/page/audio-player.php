<div id="audioplayer" class="navbar navbar-inverse navbar-fixed-bottom closed">
    <?php
    $talk = \App\Models\Talk::public()
        ->latestVisible()->latest()->first();
    // $data = $func->entry('talks');
    $data = [[
        'title' => $talk->title,
        'author' => $talk->author->title,
        'date' => $talk->date,
        'mp3' => $talk->mp3,
    ]];
    foreach ($data as $row) {
        $title = $row['title'];
        $author = $row['author'];
        $date = $func->display_date($row['date']);
        $mp3 = $row['mp3'];
        $img = $func->getAuthorImagePath($author);
        ?>
        <div class="container-fluid">
            <i class="tab tab-audioplayer icon-volume-up pull-right"></i>
        </div>
        <div class='audioplayer-inner'>
            <div class="container-fluid">
                <div class='row-fluid'>
                    <div id='info-container' class='span4'>
                        <div class='media'>
                            <span class='pull-left'>
                                <img id='speaker' class='media-object' src='<?= $img ?>' data-src='$img/50x50'>
                            </span>
                            <div id='text' class='media-body'>
                                <span class='title'><?= $author ?></span>
                                <div class='author'><?= $title ?></div>
                                <div class='date'><i><?= $date ?></i></div>
                            </div>
                        </div>
                    </div>
                    <div id='time-container' class='span4'>
                        <div  class='row-fluid'>
                            <div class='media'>
                                <span id='buttons' class='pull-left'>
                                    <button class='btn play' onclick="play(this, <?= "'$title','$author','$date','$img','$mp3'" ?>);">
                                        <i class='icon-play'></i>
                                    </button>
                                </span>
                                <div class='media-body'>
                                    <div id='time' class="progress">
                                        <div id='duration' class="bar" style="position:absolute;z-index:100;width:0%;"></div>
                                        <div id='buffer' class="bar bar-warning" style="position:absolute;width:0%;"></div>
                                    </div>
                                    <span id='elapsed'>00:00:00 / 00:00:00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id='volume-container' class='span4'>
                        <div class='media hidden-phone hidden-tablet'>
                            <div class='media-body'>
                                <span class='pull-right'>
                                    <i class='icon-volume-up'></i>
                                </span>
                                <div id='volume' class="progress">
                                    <div class="bar" style="width:100%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php }
    ?>
</div>
