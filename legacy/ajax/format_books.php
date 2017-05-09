<?php

$author = $row['author'];
$date = date("Y", strtotime($date));
$subtitle = $row['subtitle'];
$weight = $row['weight'];
$cover = $row['cover'];
$pdf = $row['pdf'];
$epub = $row['epub'];
$mobi = $row['mobi'];
$request = $row['request'];
$e_pdf = e($pdf);
$e_epub = e($epub);
$e_mobi = e($mobi);
$buttons = "";
if (strcasecmp($request, "no") != 0) {
    $buttons.= "<button class='btn btn-group-media btn-request' onclick='addBook($id)'><i class='icon-book'></i> {$_lang['request_print_copy']}</button>";
}
$buttons .= "<div class='btn-group btn-group-media'>";
if ($pdf != "") {
    $buttons .= "<a href='/media/books/$e_pdf' target='_blank' class='btn btn-pdf'><i class='icon-download-alt'></i> PDF</a>";
}
if ($epub != "") {
    $buttons .= "<a href='/media/books/$e_epub' class='btn btn-epub'><i class='icon-download-alt'></i> ePUB</a>";
}
if ($mobi != "") {
    $buttons.= "<a href='/media/books/$e_mobi' class='btn btn-mobi'><i class='icon-download-alt'></i> Mobi</a>";
}
$buttons .= "</div>";
$data = "
                        <div class='media books'>
                            <span class='pull-left'>
                                    <img class='media-object img-books' src='/media/images/books/$cover' data-src='$cover/50x50'>
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
    </div>";
?>
