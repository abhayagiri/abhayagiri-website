<?php

    if (Lang::locale() === 'th') {
        $langJsPath = '/th/js/lang.js';
    } else {
        $langJsPath = '/js/lang.js';
    }

?>
<script type="text/javascript" src="/js/plugins/LAB.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>
    $LAB
            .script('<?php echo $langJsPath ?>?<?php echo $versionStamp ?>')
            .script('/js/main.js?<?php echo $versionStamp ?>')
            .script('/js/plugins.js?<?php echo $versionStamp ?>')
            .script('/js/plugins/jquery.masonry.js')
            .wait(function() {
        //initMasonry();
    })
            .script('/js/plugins/jquery.datatables.js')
            .wait(function() {
        initSearch();
    })
            .script('/js/plugins/jquery.fullcalendar.js')
            .wait(function() {
        $('head').append('<link rel="stylesheet" href="/css/jquery.fullcalendar.css">');
    })
            .script('/js/plugins/jquery.fullcalendar.agenda.js')
            .wait(function() {
        plugin(_page);
    })
            .script('/js/plugins/bootstrap.js')
            .wait(function() {
        $('#call,#rss').popover({
            html: true
        });
    })
</script>
<script src="/js/plugins/soundmanager2.js"></script>
<script>
    soundManager.setup({
        url: '/swf/',
        flashVersion: 9,
        useHTML5Audio: true,
        debugMode: false,
        onready: function() {
            console.log("SoundManager Ready!");
        },
        ontimeout: function() {
            console.log("SoundManager Timed Out :/");
        }
    });
</script>
