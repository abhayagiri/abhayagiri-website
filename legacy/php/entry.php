<?php

$path = substr($_SERVER['REQUEST_URI'], 1);
$redirect = \App\Models\Redirect::getRedirectFromPath($path);
if ($redirect) {
    \App\Util::redirect($redirect);
    return;
}

$stmt = null;

if ($_page == 'books') {
    $book = \App\Models\Book::where('id', (int) $_entry)
        ->with('author')->first();
    if ($book) {
        $stmt = [ $book->toLegacyArray() ];
    }
} else if ($_page == 'news') {
    $news = \App\Models\News::where('id', (int) $_entry)
        ->first();
    if ($news) {
        $stmt = [ $news->toLegacyArray() ];
    }
} else {
    $stmt = $db->_select($_page, '*', array('url_title' => $_entry));
    if (!$stmt) {
        // Try urlencoding $_entry
        $_entry = urlencode($_entry);
        $stmt = $db->_select($_page, '*', array('url_title' => $_entry));
    }
}
if (!$stmt) {
    include("$_base/ajax/404.php");
    return;
}
$row = $stmt[0];
$id = $row['id'];
$title = $row['title'];
$url_title = $row['url_title'];
$date = $func->display_date($row['date']);
$body = $row['body'];
$entry = true;
?>
<!--image-->
<div id="banner">
    <div class="title"><?= "<i class='$_icon'></i> $_page_title" ?></div>
    <img src="/media/images/banner/<?= $_page ?>.jpg">
</div>

<div id="breadcrumb-container">
    <div class="container-fluid">
        <ul class="breadcrumb">
            <li><a href="<?= $_lang['base'] ?>/" onclick="nav('home');
                    return false;"><?= $_lang['home'] ?></a> <span class="divider">/</span></li>
            <li><a href='<?= "{$_lang['base']}/{$_page}" ?>' onclick="nav('<?= $_page ?>');
                    return false;"><?= $_page_title ?></a><span class="divider">/</span></li>
            <li id="breadcrumb" class="active"><?= $title ?></li>
        </ul>
    </div>
</div>
<!--/image-->
<!--body-->
<div id="content" class="container-fluid">
    <div class="content row-fluid">
        <?php
        include("$_base/ajax/format_$_page.php");
        echo $data;
        ?>
    </div>
</div>