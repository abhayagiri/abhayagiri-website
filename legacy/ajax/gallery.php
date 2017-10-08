<script>//window.location.replace("https://picasaweb.google.com/110976577577357155764");</script>
<!--image-->
<div id="banner">
    <div class="title">
        <?= "<i class='$_icon'></i> $_page_title" ?>
    </div>
    <img src="/media/images/banner/gallery.jpg">
</div>
<div id="breadcrumb-container">
    <div class="container-fluid">
        <ul class="breadcrumb">
            <li><a href="<?= $_lang['base'] ?>/"><?= $_lang['home'] ?></a> <span class="divider">/</span></li>
            <li class='active'><?= $_page_title ?></li>
        </ul>
    </div>
</div>
<!--/image-->
<!--body-->
<div id="content" class="container-fluid">

    <div class="content row-fluid">
        <div id='gallery'>
            <?php
            $_album = $func->galleryAlbums();
            // Disable pending approval
            // $_album = array_slice($_album,0,30);
            foreach ($_album as $row) {
                $id = $row['id'];
                $url = $row['url'];
                $title = $row['title'];
                $description = $row['description'];
                ?>
                <div class='gallery brick thumbnail'>
                    <a href='<?= $_lang['base'] ?>/gallery/<?= $id ?>'><img src="<?= $url ?>"/></a>
                    <div class='caption'>
                        <h4><a href='<?= $_lang['base'] ?>/gallery/<?= $id ?>'><?= $title ?></a></h4>
                        <p><?= $description ?></p>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>
