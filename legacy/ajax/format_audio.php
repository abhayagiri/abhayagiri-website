<?php

$author = $row['author'];
$mp3 = $row['mp3'];
$mp3_url = '/media/audio/' . $mp3;
$img = $func->getAuthorImagePath($author);
$youtube_id = $row['youtube_id'];
$youtube_url = 'https://youtu.be/' . $youtube_id;
$extension = strtolower(substr($row['mp3'], -3));

// Ugh...
$e_author = e($author);
$e_date = e($date);
$e_img = e($img);
$e_mp3 = e($mp3);
$e_mp3_url = e($mp3_url);
$e_title = e($title);
$e_url_title = e($url_title);
$e_youtube_url = e($youtube_url);

$data = <<<END
<div class='row-fluid'>
    <div class='span7'>
        <div class='media'>
            <span class='pull-left'>
                <img class='img-speakers media-object' src='$e_img' data-src='$e_img/50x50'>
            </span>
            <div class='media-body'>
                <span class='title'>
                    <a href='{$_lang['base']}/audio/$e_url_title' onclick="navEntry('audio','$e_url_title');return false;">$e_title</a>
                </span>
                <br>$e_author
                <br><i>$e_date</i>
            </div>
        </div>
    </div>
    <div class='span5'>
        <span class='btn-group btn-group-media'>";
END;

if ($youtube_id) {
    $data .= <<<END
        <a href="$e_youtube_url" target="_blank" class="btn">
            <i class="icon-film"></i>
            Watch
        </a>
END;
}

if ($extension == 'mp3') {
    $data .= <<<END
        <button href class="btn" onclick="play(this,'$e_title','$e_author','$e_date','$e_img','$e_mp3');">
            <i class="icon-play"></i>
            {$_lang['play']}
        </button>
END;
}

if ($extension) {
    $data .= <<<END
        <a href="$e_mp3_url" class="btn">
            <i class="icon-cloud-download"></i>
            {$_lang['download']}
        </a>
END;
}

$data .= <<<END
        </span>
    </div>
</div>
<div class='row-fluid'>
    <div class='body span12'>
        $body
    </div>
</div>
 <div class='backtotop phone' onclick='backtotop()'>
        <span class='pull-right'>
            <i class='icon-caret-up'></i>
           {$_lang['back_to_top']}
        </span>
    </div>
END;
