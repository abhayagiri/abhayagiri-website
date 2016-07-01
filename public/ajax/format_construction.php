<?

if ($key > 0) {
    $body = $func->fixLength($row['body'], 500);
    $body .= "<br>
            <a class = 'btn' href = '/$table/$url_title' onclick = 'navEntry(\"$table\", \"$url_title\");return false;'>
                {$_lang['read_more']}
                <i class='icon-double-angle-right'></i>
            </a>";
}
$data = "
    <span class = 'title'>
        <a href = '{$_lang['base']}/construction/$url_title' onclick = \"navEntry('construction','$url_title'); return false;\">$title</a>
    </span>
    <br><br> 
    <div class='row-fluid'>
        <div class='span9'>
            $body
        </div>
    </div>
    <div class='backtotop' onclick='backtotop()'>
        <span class='pull-right'>
            <i class='icon-caret-up'></i>
            {$_lang['back_to_top']}
        </span>
    </div>";
?>
