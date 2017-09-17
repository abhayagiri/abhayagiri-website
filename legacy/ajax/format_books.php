<?php

$id = $row['id'];
$title = e($row['title']);
$url_title = e($row['url_title']);
$date = date('Y', strtotime($row['date']));
$body = $row['body'];
$author = e($row['author']);
$cover = e($row['cover']);
$e_pdf = e($row['pdf']);
$e_epub = e($row['epub']);
$e_mobi = e($row['mobi']);
$weight = e($row['weight']);
$request = $row['request'];

$buttons = "";
if ($request) {
    $buttons.= "<button class='btn btn-group-media btn-request' onclick='addBook($id)'><i class='icon-book'></i> {$_lang['request_print_copy']}</button>";
}
$buttons .= "<div class='btn-group btn-group-media'>";
if ($e_pdf) {
    $buttons .= "<a href='$e_pdf' target='_blank' class='btn btn-pdf'><i class='icon-download-alt'></i> PDF</a>";
}
if ($e_epub) {
    $buttons .= "<a href='$e_epub' class='btn btn-epub'><i class='icon-download-alt'></i> ePUB</a>";
}
if ($e_mobi) {
    $buttons.= "<a href='$e_mobi' class='btn btn-mobi'><i class='icon-download-alt'></i> Mobi</a>";
}
$buttons .= "</div>";
$data = "
                        <div class='media books'>
                            <span class='pull-left'>
                                    <img class='media-object img-books' src='$cover' data-src='$cover/50x50'>
                            </span>
                            <div class='media-body'>
                                <div class='row-fluid'>
                                    <div class='span7'>
                                        <span class='title'>
                                            <a href='{$_lang['base']}/books/$url_title' onclick=\"navEntry('books','$url_title');return false;\">$title</a>
                                        </span>
                                        <br>$author
                                        <br><i>Year Published: $date</i>
                                    </div>
                                    <div class='span5'>
                                        $buttons
                                    </div>
                               </div>
                               <div class = 'row-fluid'>
                                    <div class ='body span12'>
                                        $body
                                    </div>
                               </div>
                          </div>
                      </div>
                      <div class='backtotop phone' onclick='backtotop()'>
        <span class='pull-right'>
            <i class='icon-caret-up'></i>
           {$_lang['back_to_top']}
        </span>
    </div>
    <hr class='border'>
    ";

return $data;
