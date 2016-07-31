<?
require('php/db.php');
require('php/session.php');
$_page = "dashboard"
?>
<!DOCTYPE html>
<!--head-->
<head>
    <!--meta-->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>MahaPanel</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width">
    <!--/meta-->
    <!--css-->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-responsive.min.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/datetimepicker.css">
    <link rel="stylesheet" href="css/tinyeditor.css">
    <!--/css-->
    <script>
        var email = "<?= $email ?>";
        var name = "<?= $name ?>";
        var user = "<?= $user ?>";
        var banner = "<?= $banner ?>";
    </script>
</head>
<!--/head-->

<!--body-->
<body>

    <!--wrapper-->
    <div id="wrapper">

        <div id="navbar" class="navbar navbar-inverse navbar-static-top">
            <div class="navbar-inner btn-primary">
                <div>
                    <a class="brand" href="https://mahapanel.abhayagiri.org" title="mahapanel.abhayagiri.org"><img id="logo" src="/img/mplogo.png"></a>
                    <!-- start: Header Menu -->
                    <div class="nav-no-collapse header-nav">

                        <ul class="nav pull-right">
                            <!-- start: User Dropdown -->
                            <li>
                                <div class="btn-group">
                                    <button type="button" onclick="togglePanel('#nav')" class="btn btn-inverse btn-menu" title="MENU (click or press Tab)"><i class="icon-reorder"></i> </button>
                                </div>
                            </li>
                            <li><div class="btn-group">
                                    <button class="btn btn-inverse btn-menu dropdown-toggle" data-toggle="dropdown">
                                        <img id="maha-avatar" class="avatar" src="<?= "/img/mahaguild/$avatar" ?>">&nbsp;<span id="mahaguildie"><?= $name ?></span>
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a href="javascript:navProfile();"><i class="icon-user"></i> Profile</a></li>
                                        <li class="divider"></li>
                                        <li><a target="_blank" href="http://mail.abhayagiri.org" title="mail.abhayagiri.org"><i class="icon-envelope"></i> Email</a></li>
                                        <li class="divider"></li>
                                        <li><a target="_blank" href="http://calendar.abhayagiri.org" title="calendar.abhayagiri.org"><i class="icon-calendar"></i> Calendar</a></li>
                                        <li class="divider"></li>
                                        <li><a target="_blank" href="http://docs.abhayagiri.org" title="docs.abhayagiri.org"><i class="icon-file-alt"></i> Documents</a></li>
                                        <li class="divider"></li>
                                        <li><a target="_blank" href="https://www.abhayagiri.org" title="www.abhayagiri.org"><i class="icon-globe"></i> Website</a></li>
                                        <li class="divider"></li>
                                        <li><a href="php/logout.php"><i class="icon-signout"></i> Logout</a></li>
                                    </ul>
                                </div></li>

                            <!-- end: User Dropdown -->
                        </ul>

                    </div>
                    <!-- end: Header Menu -->

                </div>
            </div>
        </div>

        <!--nav-->
        <div id="nav" class="panel" role="navigation">
            <br><br><br>
            <span class="btn-close" onclick="togglePanel('#nav')"><i class="icon-remove-sign"></i></span>
            <div class="container-fluid">
                <div id="masonry">
                    <div class="item">
                        <a href="/dashboard" onclick="navDash();
            return false;">
                            <div id="btn-dashboard" class="btn-nav">
                                <span class="admin icon icon-dashboard"></span><br>
                                <span class="admin title-icon">Dashboard</span>
                            </div>
                        </a>
                    </div>
                    <?php
                    $access = '("' . str_replace(',', '","', $access) . '")';
                    $stmt = $db->_query("SELECT * FROM pages WHERE url_title IN $access AND mahapanel = 'yes' ORDER BY date DESC");
                    foreach ($stmt as $nav) {
                        $title = $nav['title'];
                        $url = $nav['url_title'];
                        $id = $nav['id'];
                        $class = $nav['class'];
                        $icon = $nav['icon'];
                        $page_type = $nav['page_type'];
                        if ($url == "columns")
                            skip;
                        ?>
                        <div class="item">
                            <a href="/<?= $url ?>" onclick="nav<?= $page_type ?>(<?= "'$id','$url','$title','$icon','$page_type'" ?>);
                return false;">
                                <div id="btn-<?= $title ?>" class="btn-nav">
                                    <span class="<?= $class ?> icon <?= $nav['icon'] ?>"></span><br>
                                    <span class="<?= $class ?> title-icon"><?= $title ?></span>
                                </div>
                            </a>
                        </div>
                    <? } ?>
                </div>
            </div>
        </div>
        <!--/nav-->


        <!--form-->
        <div id="form" class="panel" role="navigation">
        </div>
        <!--form-->

        <div id="main">
            <div id="bg"></div>
            <div id="maha-banner" class="hero-unit" >
                <div class="container-fluid">
                    <h1 id="page-title"></h1><br>
                    <p>
                        <button id="newEntry" onclick="navForm()"class="btn btn-large btn-warning"><i class="icon-plus"></i>&nbsp;&nbsp;New Entry</button>
                    </p>
                </div>
            </div>


            <!-- start: Content -->
            <div id="table"></div>
        </div>
    </div>
    <!--/wrapper-->

    <!--script-->
    <script type="text/javascript" src="js/plugins/LAB.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>
        $LAB
                .script('/js/plugins/jquery.datatables.min.js')
                .script("/js/ajax.js")
                .script("/js/main.js")
                .wait(function() {
            setBanner(banner);
            if (_page === "dashboard") {
                loadDash();
                history.replaceState({
                    type: "Dash"
                }, null, "/dashboard");
            }
            else {
                getPageId(_page, function(data) {
                    if(typeof data[0].id =='undefined'){
                        window.location.href="/dashboard"
                    }
                    data = jQuery.parseJSON(data);
                    id = data[0].id;
                    url = _page;
                    title = data[0].title;
                    icon = data[0].icon;
                    type = data[0].page_type;
                    if (type === "Table") {
                        loadTable(id, url, title, icon);
                    }
                    else {
                        loadCustom(id, url, title, icon);
                    }
                    history.replaceState({
                        id: id,
                        url: url,
                        title: title,
                        icon: icon,
                        type: type
                    }, null, "/" + url);
                });
            }
        })
                .script('/js/plugins/jquery.masonry.min.js')
                .script("/js/plugins.js")

                .wait(function() {
            $('#masonry').masonry({itemSelector: '.item', isFitWidth: true});
        })
                .script("/js/plugins/bootstrap.min.js")
                .script('/js/plugins/jquery.tinyeditor.js')
                .script('/js/plugins/ajaxfileupload.js')
                .script('/js/plugins/bootstrap.datetimepicker.min.js')
                .script('/js/plugins/bootstrap.duallist.js')
    </script>
    <!--/script-->
</body>
<!--/body-->
</html>


