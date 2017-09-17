<?php

$func = new \App\Legacy\Func($_language);
$url_title = e($row['url_title']);
$title = e($row['title']);
$body = $row['body'];
$date = $func->display_date($row['date']);
$author = e($row['author']);
$img = e($row['author_image_url']);

if ($key > 0) {
    $body = $func->fixLength($row['body'], 500);
    $body .= "<br>
            <a class = 'btn' href = '{$_lang['base']}/reflections/$url_title' onclick = 'navEntry(\"reflections\", \"$url_title\");return false;'>
                {$_lang['read_more']}
                <i class='icon-double-angle-right'></i>
            </a>";
}

$data = "
	<div class='row-fluid'>
                    <div class='span12' >
                        <div class='media'>
                            <span class='pull-left'>
                                <img class='media-object img-speakers' src='$img' data-src='$img/50x50'>
                            </span>
                            <div class='media-body'>
                                <span class='title'>
                                    <a href='{$_lang['base']}/reflections/$url_title' onclick=\"navEntry('reflections','$url_title');return false;\">$title</a>
                                </span>
                                <br>$author
                                <br><i>$date</i>
                            </div>
                        </div>
                    </div>
                    <div class='row-fluid'>
                        <div class='body span9'>
                            $body
                        </div>
                    </div>
                    <div class='backtotop' onclick='backtotop()'>
                        <span class='pull-right'>
                            <i class='icon-caret-up'></i>
                            {$_lang['back_to_top']}
                        </span>
                    </div>";

return $data;
