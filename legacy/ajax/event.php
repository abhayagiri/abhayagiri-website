<!--image-->
<div id="banner">
    <div class="title"><?= "<i class='icon-calendar'></i> Calendar" ?></div>
    <img src="/media/images/banner/calendar.jpg">
</div>

<div id="breadcrumb-container">
    <div class="container-fluid">
        <ul class="breadcrumb">
            <li><a href="<?= $_lang['base'] ?>/"><?= $_lang['home'] ?></a> <span class="divider">/</span></li>
            <li><a href='<?= $_lang['base'] ?>/calendar'><?= $_page_title ?></a><span class="divider">/</span></li>
            <li id="breadcrumb" class="active"></li>
        </ul>
    </div>
</div>
<!--/image-->
<!--body-->
<div id="content" class="container-fluid">
    <div class="content row-fluid">
        <dl class = 'dl-horizontal'>
            <div id="event-details"></div>
        </dl>
        <div class='backtotop' onclick='backtotop()'><span class='pull-right'><i class='icon-caret-up'></i> <?= $_lang['back_to_top'] ?></span></div>
    </div>
</div>
