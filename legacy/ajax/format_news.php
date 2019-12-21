?php

$func = new \App\Legacy\Func($_language);
$url_title = e($row['url_title']);
$title = e($row['title']);
$body = $row['body'];
$date = $func->display_date($row['date']);

if (FALSE && $key > 0) {
    $body = $func->fixLength($body, 500);
    $body .= "
       <br/>
            <a class='btn' href='{$_lang['base']}/news/$url_title'>
                {$_lang['read_more']}
                <i class='icon-double-angle-right'></i>
            </a>";
}

//if(isset($entry) && $entry==true){
$body = preg_replace("/<img([^>]+)\>/i", "<div style='text-align:center'><img style='max-width:500px; max-height:500px' $1 /></div>", $body);
//} else {
//    $body = preg_replace("/<img([^>]+)\>/i", "", $body);
//}

$data = "
    <span class = 'title'>
        <a href = '{$_lang['base']}/news/$url_title'>$title</a>
    </span>
    <br>

    <br><br>
    <div class='row-fluid'>
        <div class='span9'>
            $body
        </div>
        <div class='span3'>
         <i>{$_lang['i18next']['common']['posted']} $date</i>
        </div>
    </div>
    <div class='backtotop' onclick='backtotop()'>
        <span class='pull-right'>
            <i class='icon-caret-up'></i>
            {$_lang['back_to_top']}
        </span>
    </div>
    <hr>
    ";

return $data;
