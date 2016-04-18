<?
$stmt = $db->_select($_page, '*', array('url_title' => $_entry));
$row = $stmt[0];
$title = $row['title'];
$url_title = $row['url_title'];
$date = $func->display_date($row['date']);
$body = $row['body'];
?>
<!--image-->
<div id="banner">
    <div class="title"><?= "<i class='$_icon'></i> $_page_title" ?></div>
    <img src="/media/images/banner/<?= $_page ?>.jpg">
</div>

<div id="breadcrumb-container">
    <div class="container-fluid">
        <ul class="breadcrumb">
            <li><a href="/" onclick="nav('home');
                    return false;">Home</a> <span class="divider">/</span></li>
            <li><a href='/<?= $_page ?>' onclick="nav('<?= $_page ?>');
                    return false;"><?= $_page_title ?></a><span class="divider">/</span></li>
            <li id="breadcrumb" class="active"><?= $row['title'] ?></li>
        </ul>
    </div>
</div>
<!--/image-->
<!--body-->
<div id="content" class="container-fluid">
    <div class="content row-fluid">
        <?
        include("$_base/ajax/format_$_page.php");
        echo $data;
        ?>
    </div>
</div>