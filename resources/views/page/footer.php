<?php

    if (Lang::locale() === 'th') {
        $visitingSubpage = 'directions-thai';
        $otherBasePath = '/';
        $otherFlag = 'flag-us';
        $otherLang = 'English';
    } else {
        $visitingSubpage = 'directions';
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
                    <a href="/contact" onclick="nav('contact');
return false;" class="btn link">
                        <i class="icon icon-envelope"></i>
                        <?php echo trans('page.footer.contact') ?>
                    </a>
                    <a href="/visiting/<?php echo $visitingSubpage ?>" onclick="navSub('visiting', '<?php echo $visitingSubpage ?>', '<?php echo trans('page.footer.directions') ?>'); return false;" class="btn link">
                        <i class="icon icon-map-marker"></i>
                        <?php echo trans('page.footer.directions') ?>
                    </a>
                    <a id="call" href="tel:+7074851630"
                       data-toggle="popover" title="" data-content="(707)4851630" data-original-title="Abhayagiri Phone" data-placement="top"
                       class="btn link">
                        <i class="icon icon-phone"></i>
                        <?php echo trans('page.footer.call') ?>
                    </a>
                    <?php if (config('abhayagiri.feedback_token')) { ?>
                        <a href="https://feedback.userreport.com/<?php echo config('abhayagiri.feedback_token') ?>/
" target="_blank" class="btn link">
                            <i class="icon icon-fire"></i>
                            <?php echo trans('page.footer.feedback') ?>
                        </a>
                    <?php } ?>
                </div>
            </div>
            <div class="span4 pull-right">
                <a id='btn-language' href="<?php echo $otherBasePath ?>" class="btn pull-right" style='font-family:arial'>
                    <span class="flag <?php echo $otherFlag ?>"></span>
                    <?php echo $otherLang ?>
                </a>
                <a id='rss' class="btn link pull-right"
                   data-toggle="popover" title="" data-content="
                   <a href='http://feed.abhayagiri.org/abhayagiri-news' target='_blank' class='btn'><i class='icon icon-bullhorn'></i> <?php echo trans('page.footer.news') ?></a><br><br>
                   <a href='http://feed.abhayagiri.org/abhayagiri-calendar' target='_blank' class='btn'><i class='icon icon-calendar'></i> <?php echo trans('page.footer.calendar') ?></a><br><br>
                   <a href='http://feed.abhayagiri.org/abhayagiri-talks' target='_blank' class='btn'><i class='icon icon-volume-up'></i> <?php echo trans('page.footer.audio') ?></a><br><br>
                   <a href='http://itunes.apple.com/us/podcast/abhayagiri-dhamma-talks/id600138148?mt=2' target='_blank' class='btn'><i class='icon icon-music'></i> iTunes</a>"
                   data-original-title="RSS Links" data-placement="top">
                    <i class="icon icon-rss"></i>
                    RSS
                </a>
                <br><br>
                <div id="copyright" class="muted pull-right">&copy; Abhayagiri Foundation <?php echo date("Y") ?></div>
            </div>
        </div>
    </div>
</footer>
