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
        <title><?= e($_title) ?></title>
        <meta name="description" content="<?= e($_meta_description) ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="csrf-token" content="<?= e(csrf_token()) ?>">
        <!--/meta-->

        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <!--css-->
        <link rel="stylesheet" href="/css/font-awesome.css">
        <link rel="stylesheet" href="/css/bootstrap.css">
        <link rel="stylesheet" href="/css/bootstrap-responsive.css">
        <link rel="stylesheet" href="/css/main.css?<?= e($versionStamp) ?>">
        <link rel="stylesheet" href="/css/mods.css?<?= e($versionStamp) ?>">
        <?php if (Lang::locale() === 'th') { ?>
            <link rel="stylesheet" href="/th/css/thai.css?<?= e($versionStamp) ?>">
        <?php } ?>
        <script>document.cookie = 'resolution=' + Math.max(screen.width, screen.height) + '; path=/';</script>
        <!--/css-->

        <script>
            //Global Variables
            _page = <?= json_encode($_page) ?>;
            _subpage = <?= json_encode($_subpage) ?>;
            _subsubpage = <?= json_encode($_subsubpage) ?>;
            _type = <?= json_encode($_type) ?>;
            _icon = <?= json_encode($_icon) ?>;
            _action = <?= json_encode($_action) ?>;
            history.replaceState({action: _action, page: _page, entry: _subpage, album: _subpage, event: _subpage, resident: _subsubpage, subpage: _subpage}, null, <?= json_encode($base) ?> + _page + "/" + _subpage + ((_subsubpage == "") ? "" : ("/" + _subsubpage)));
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
