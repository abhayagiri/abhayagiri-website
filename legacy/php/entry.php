<?php

switch ($_page) {

    case 'books':
    case 'news':
    case 'reflections':
        $className = 'App\Models\\' . title_case(str_singular($_page));
        $query = call_user_func([$className, 'select'])
            ->where('id', (int) $_entry);
        if ($_page !== 'news') {
            $query->with('author');
        }
        $model = $query->first();
        if ($model) {
            $stmt = [ $model->toLegacyArray($_language) ];
        } else {
            $stmt = null;
        }
        break;

    default:
        $stmt = null;
        break;
}

if (!$stmt) {
    abort(404);
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