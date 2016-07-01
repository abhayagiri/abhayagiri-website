<?php

$author = $row['author'];
$mp3 = $row['mp3'];
$img = '/media/images/speakers/speakers_' . strtolower($func->stripDiacritics(str_replace(' ', '_', $author))) . '.jpg';
$escaped_title = str_replace("'","\'",$title);
$data = "
<div class='row-fluid'>
    <div class='span7'>
        <div class='media'>
            <span class='pull-left'>
                <img class='img-speakers media-object' src='$img' data-src='$img/50x50'>
            </span>
            <div class='media-body'>
                <span class='title'>
                    <a href='{$_lang['base']}/audio/$url_title' onclick=\"navEntry('audio','$url_title');return false;\">$title</a>
                </span>
                <br>{$row['author']}
                <br><i>$date</i>
            </div>
        </div>
    </div>
    <div class='span5'>
        <span class='btn-group btn-group-media'>";

if($row['category'] != "Collection (.zip file)"){
            $data.= "<button class='btn' onclick=\"play(this,'$escaped_title','$author','$date','$img','$mp3');\">
                <i class='icon-play'></i>
                {$_lang['play']}
            </button>";
}

$data .= "<a href='/media/audio/$mp3' class='btn'>
                <i class='icon-cloud-download'></i>
                {$_lang['download']}
            </a>
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
    </div>";
?>
