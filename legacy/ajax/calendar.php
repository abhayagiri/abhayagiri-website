<!--image-->
<div id="banner">
    <div class="title"><?= "<i class='$_icon'></i> $_page_title" ?></div>
    <img src="/media/images/banner/<?= $_page ?>.jpg">
</div>
<!--/image-->
<!--body-->
<div id="breadcrumb-container">
    <div class="container-fluid">
        <ul class="breadcrumb">
            <li><a href="<?= $_lang['base'] ?>/" onclick="nav('home');
                    return false;"><?= $_lang['home'] ?></a> <span class="divider">/</span></li>
            <li class='active'><?= $_page_title ?></li>
        </ul>
    </div>
</div>
<div id="content" class="container-fluid">
    <div id="calendar"></div>
    <div id="event-list"></div><br>
    <legend>Event Details</legend>
    <div id="event-details"></div>
</div>