<?php

if (Lang::locale() === 'th') {
    $base = '/th/';
} else {
    $base = '/';
}

?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en" class="no-js"> <!--<![endif]-->

    <head>

        <!--dns prefetch-->
        <link rel="dns-prefetch" href="//www.google-analytics.com">
        <!--/dns prefetch-->

        <!--meta-->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title><?php echo $_title ?></title>
        <meta name="description" content="<?php echo $_meta_description ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="csrf-token" content="<?php echo csrf_token() ?>">
        <!--/meta-->

        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <!--css-->
        <link rel="stylesheet" href="/css/font-awesome.css">
        <link rel="stylesheet" href="/css/bootstrap.css">
        <link rel="stylesheet" href="/css/bootstrap-responsive.css">
        <link rel="stylesheet" href="/css/main.css?<?php echo $versionStamp ?>">
        <link rel="stylesheet" href="/css/mods.css?<?php echo $versionStamp ?>">
        <?php if (Lang::locale() === 'th') { ?>
            <link rel="stylesheet" href="/th/css/thai.css?<?php echo $versionStamp ?>">
        <?php } ?>
        <script>document.cookie = 'resolution=' + Math.max(screen.width, screen.height) + '; path=/';</script>
        <!--/css-->

        <script>
            //Global Variables
            _page = "<?php echo $_page ?>";
            _subpage = "<?php echo $_subpage ?>";
            _subsubpage = "<?php echo $_subsubpage ?>";
            _type = "<?php echo $_type ?>";
            _icon = "<?php echo $_icon ?>";
            _action = "<?php echo $_action ?>";
            history.replaceState({action: _action, page: _page, entry: _subpage, album: _subpage, event: _subpage, resident: _subsubpage, subpage: _subpage}, null, "<?php echo $base ?>" + _page + "/" + _subpage + ((_subsubpage == "") ? "" : ("/" + _subsubpage)));
            //Remove '#' from URI
            currlocation = window.location.href;
            if (currlocation.indexOf("#") !== -1) {
                window.location = currlocation.replace('#', '');
            }
        </script>

        <?php
            $extraHeadPath = public_path('head.html');
            if (file_exists($extraHeadPath)) {
                include $extraHeadPath;
            }
        ?>

    </head>
    <!--/head-->
