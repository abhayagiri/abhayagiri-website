<?php

    if (Lang::locale() === 'th') {
        $base = '/th';
        $otherBasePath = '/';
        $otherFlag = 'flag-us';
        $otherLang = 'English';
    } else {
        $base = '';
        $otherBasePath = '/th';
        $otherFlag = 'flag-th';
        $otherLang = 'ภาษาไทย';
    }

?>
<footer id="footer">
    <div class="container-fluid">
        <i class="tab tab-audioplayer icon-volume-up pull-right"></i>
        <div class="row-fluid">
            <div class="span4">
                <div class="btn-group">
                    <a href="<?= $base ?>/contact" class="btn link">
                        <i class="icon icon-envelope"></i>
                        <?php echo trans('page.footer.contact') ?>
                    </a>
                    <a href="<?= $base ?>/visiting/directions" class="btn link">
                        <i class="icon icon-map-marker"></i>
                        <?php echo trans('page.footer.directions') ?>
                    </a>
                    <a id="call" href="tel:+7074851630"
                       data-toggle="popover" title="" data-content="(707)4851630" data-original-title="Abhayagiri Phone" data-placement="top"
                       class="btn link">
                        <i class="icon icon-phone"></i>
                        <?php echo trans('page.footer.call') ?>
                    </a>
                </div>
            </div>
            <div class="span6 pull-right">
                <div class="btn-group pull-right">
                    <a href="https://www.youtube.com/channel/UCFAuQ5fmYYVv5_Dim0EQpVA?sub_confirmation=1" class="btn link">
                        <i class="icon icon-youtube"></i>
                        <?php echo trans('page.footer.subscribe') ?>
                    </a>
                    <a href="https://itunes.apple.com/us/podcast/abhayagiri-dhamma-talks/id600138148?mt=2" class="btn link">
                        <i class="icon icon-apple"></i>
                        <?php echo trans('page.footer.subscribe') ?>
                    </a>
                    <a href="<?= $base ?>/contact/subscribe-to-our-newsletter" class="btn link">
                        <i class="icon icon-file-text-alt"></i>
                        <?php echo trans('page.footer.newsletter') ?>
                    </a>
                    <a id='rss' class="btn link"
                       data-toggle="popover" title="" data-content="
                       <a href='http://feed.abhayagiri.org/abhayagiri-news' target='_blank' class='btn'><i class='icon icon-bullhorn'></i> <?php echo trans('page.footer.news') ?></a><br><br>
                       <a href='http://feed.abhayagiri.org/abhayagiri-calendar' target='_blank' class='btn'><i class='icon icon-calendar'></i> <?php echo trans('page.footer.calendar') ?></a><br><br>
                       <a href='http://feed.abhayagiri.org/abhayagiri-talks' target='_blank' class='btn'><i class='icon icon-volume-up'></i> <?php echo trans('page.footer.audio') ?></a><br><br>
                       <a href='http://itunes.apple.com/us/podcast/abhayagiri-dhamma-talks/id600138148?mt=2' target='_blank' class='btn'><i class='icon icon-music'></i> iTunes</a>"
                       data-original-title="RSS Links" data-placement="top">
                        <i class="icon icon-rss"></i>
                        RSS
                    </a>
                </div>
                <br><br>
                <div id="copyright" class="muted pull-right">&copy; Abhayagiri Foundation <?php echo date("Y") ?></div>
            </div>
        </div>
    </div>
</footer>
