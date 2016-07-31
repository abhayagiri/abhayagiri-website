<?
require("php/main.php");
$config = Abhayagiri\Config::getConfig();
/* ------------------------------------------------------------------------------
  Page and Subpage
  ------------------------------------------------------------------------------ */
if ($_REQUEST['_page']) {
    $_page = $_REQUEST['_page'];
    $_page_title = ucfirst($_page);
} else {
    $_page = "home";
}
if ($_REQUEST['_subpage']) {
    $_subpage = $_REQUEST['_subpage'];
    $_subpage_title = $func->title_case($_subpage);
}
if ($_REQUEST['_subsubpage']) {
    $_subsubpage = $_REQUEST['_subsubpage'];
    $_subsubpage_title = $func->title_case($_subsubpage);
}
/* ------------------------------------------------------------------------------
  Title
  ------------------------------------------------------------------------------ */
$_title = "Abhayagiri Buddhist Monastery";
$_title .= ($_page != "home") ? " - $_page_title" : "";
$_title .= ($_subpage != "") ? " - $_subpage_title" : "";
$_title .= ($_subsubpage != "") ? " - $_subsubpage_title" : "";
/* ------------------------------------------------------------------------------
  Nav
  ------------------------------------------------------------------------------ */
$_nav = "";
$stmt = $db->_query("SELECT * FROM pages WHERE www='yes' ORDER BY date DESC");
foreach ($stmt as $count => $nav) {
    if ($_page == $nav['url_title']) {
        $active = "active";
        $_page_title = $nav['thai_title'];
        $_type = $nav['display_type'];
        if (($_type == "Table" && $_subpage != "")) {
            $_type = "Entry";
            $_action = "entry";
            $_entry = $_subpage;
        } else if ($_page == "gallery" && $_subpage != "") {
            $_type = "Album";
            $_action = "album";
            $_album = $_subpage;
        } else if ($_page == "calendar" && $_subpage != "") {
            $_type = "Event";
            $_action = "event";
            $_event = $_subpage;
            $event = $func->google_calendar($_event);
            $_title = "Abhayagiri Buddhist Monastery > Calendar > {$event->title}";
        } else if ($_subpage == "residents" && $_subsubpage != "") {
            $_page = "Residents";
            $_subpage = $_subsubpage;
            $_type = "Resident";
            $_action = "resident";
            $_resident = $_subsubpage;
        } else if ($_subpage != "") {
            $_action = "subpage";
        }
        $_icon = $nav['icon'];
        $_meta_description = $nav['meta_description'];
    } else {
        $active = "";
    }
        if($nav['url_title']!="construction"){
    $_nav .= "
    <div class='brick'>
        <a href='/th/{$nav['url_title']}' onclick=\"nav('{$nav['url_title']}');return false;\">
            <div id='btn-{$nav['url_title']}' class='btn-nav {$active}'>
                <i class='{$nav['class']} icon {$nav['icon']}'></i><br>
                <span class='{$nav['class']} title-icon'>{$nav['thai_title']}</span>
            </div>
        </a>
    </div>";
}
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
        <title><?= $_title ?></title>
        <meta name="description" content="<?= $_meta_description ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <!--/meta-->

        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <!--css-->
        <link rel="stylesheet" href="/css/font-awesome.css">
        <link rel="stylesheet" href="/css/bootstrap.css">
        <link rel="stylesheet" href="/css/bootstrap-responsive.css">
        <link rel="stylesheet" href="/css/main.css">
        <link rel="stylesheet" href="/css/mods.css">
        <link rel="stylesheet" href="/th/css/thai.css">

        <script>document.cookie = 'resolution=' + Math.max(screen.width, screen.height) + '; path=/';</script>
        <!--/css-->

        <script>
            //Global Variables
            _page = "<?= $_page ?>";
            _subpage = "<?= $_subpage ?>";
            _subsubpage = "<?= $_subsubpage ?>";
            _type = "<?= $_type ?>";
            _icon = "<?= $_icon ?>";
            _action = "<?= $_action ?>";
            history.replaceState({action: _action, page: _page, entry: _subpage, album: _subpage, event: _subpage, resident: _subsubpage, subpage: _subpage}, null, "/th/" + _page + "/" + _subpage + ((_subsubpage == "") ? "" : ("/" + _subsubpage)));
            //Remove '#' from URI
            currlocation = window.location.href;
            if (currlocation.indexOf("#") !== -1) {
                window.location = currlocation.replace('#', '');
            }
        </script>

    </head>
    <!--/head-->
    <!--body-->
    <body>
        <a id='link-language' href='/' class='visible-desktop'><span class='flag flag-us'></span> English</a>
        <!--<div id="responsive-tester" style="position:absolute;top:10px;left:10px;background:pink;color:black;z-index:999999">Responsive Ruler</div>-->
        <?
        if (preg_match('/(?i)msie [2-8]/', $_SERVER['HTTP_USER_AGENT'])) {
            // if IE<=8
            echo '<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a>';
            exit;
        }
        ?>
        <div id="wrapper">

            <!--header-->
            <div id="header" role="banner">
                <div class="container-fluid">
                    <div id="logo" ><img src="/media/images/misc/thaiheader.jpg"></div>
                    <div id="btn-container">
                        <div id="btn-search" class="pull-right">
                            <img src="data:image/png;base64,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">
                        </div>
                        <div id="btn-menu" class="pull-right">
                            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAACmCAYAAAAswirrAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAiLQAAIi0BEpbgmAAAABl0RVh0U29mdHdhcmUAd3d3Lmlua3NjYXBlLm9yZ5vuPBoAAAkHSURBVHic7Z19rBxVFcB/Z7vQp8WCoIWqhbrbBmyp2tYWTE2AEENitaWkaYwmKCrWVoiChqRIbCIfjRHB+hUgopKABBo/wFJKqcKjEqGkVgxpCL4t+EH7KlgtxQLt6zv+ce/wptPtY/ft3bdH9/ySyezemTnnvvm9u3Nn7syuVCoVJREi0tvX13d2qngA1Wr1OeCUROH+UqvVJieKBcCUKVMeVtWzUsUrpQrkpMGFGMOFGMOFGMOFGMOFGMOFGMOFGMOFGMOFGMOFGMOFGMOFGMOFGMOFGMOFGMOFGMOFGKMMPJ4qmKpuSxUrx1agP1GsnYnivE78m3tSxRPVZEPqTgL8I8sYLsQYLsQYLsQYLsQYLsQYLsQYLsQYLsQYLsQYLsQYLsQYLsQYLsQYLsQYLsQYLsQYLsQYLsQYUqlUHksY7w+1Wm15wnhUq9VfAhMThdtZq9UWJYoFQLVa/SEwK1W8MnBGqmAi8mqqWDlmkvCLAxLFeR0Rmaaqyfahf2QZw4UYw4UYw4UYw4UYw4UYw4UYw4UYw4UYw4UYw4UYw4UYw4UYw4UYw4UYw4UYw4UYw4UYw59TN4a3EGO4EGO4EGO4EGO4EGO4EGO4EGO4EGO4EGO4EGO4EGO4EGO4EGO4EGO4EGO4EGO4EGO4EGO4EGNIpVL5Tqpgqtq3ffv276eKB1CtVr8OHJ8o3O5arfaNRLEAqFQql4jIlFTxysCXUgUrlUq9QFIhwGdI+5x6UiGlUmmx/8D9/zEuxBguxBguxBguxBguxBguxBguxBguxBguxBguxBguxBguxBguxBguxBguxBguxBguxBjlwcHBDyYLVi6/lCpWRqlUWjQwMDA2Raxyufxaijh5RGT5wYMHxyeL518cYAv/yDKGCzGGCzGGCzGGCzGGCzGGCzGGCzGGCzGGCzGGCzGGCzGGCzGGCzGGCzGGCzGGCzFGR4WISElEKiLSMwq5emIu0/+ELVdORFaJSL+I3NXENj0ishp4CagBe0VkrYg0/bvpIvLumL9fRN5aZ/k7RWQdsDfm2iMi14jImBHkWhHz3N/sto1SThBjPHAicNjOqEfcERuBeYV6zAe2iMjpqrq7ifzlmB9ACrlOALbklgMcA3wNmAR8qok82bYnAic0uV3DdKL5XkKQsR9YBZwKfBHYAUwErk2YaxVhBz4PLIu5vgUMABeKSLIH/lORooU0y6I4X6eqV8bXz4jIWOAGQktJxYI4X6WqN8XXV4jI+4EPx1y9CfO1TCdayGlxvqFQnr2fJCLjWk0SjyfZR9W6wuIH4vzUVvOkphNCdsT5GYXy2bnXKW6M2wO8Gl/PLSz7QJy/KUGepHRCyONxvkhEFkjgfST8EhwAVR0EnoxvvyoiM2Ku84GPpsyVkk4cQ64GPkHond0D7CQczNvBCuC3hBbxpzbnSsKotxBV3QFcAPTHorbtIFV9iNDyDhRymb1/thMtBFX9jYjMIPRypgN/B/4G/KINub4rIhuBc4GTgaeAdwDXpc6Vgo4IAVDVF4HbsvciMqeNubYB23K5Lm1XrlYxfV2nG3EhxnAhxnAhxnAhxrAk5G2drsCREJGyiHwIyHqCE0Vkvogkv/TS6RHDc0Tk1yKym0MvAK4XkZXxqmzHEJGpIvJjYDewiXCFGOBdwFrgBRG5SkTSnT6oaksT8APCme+GJrY5Dfhd3O6Npo3AmcPEmppb9/gG8186XJ2Bo4FvE8ZN8nXZH+cHCuV3AqVW96Wqjn4LEZHZhAuM2YjhPuBB4Ee51bYwdHnjXOBREbl+lMbeq8DvgcuBMYRh5hsJV6evj6ttBWYANxOuKH8cuCxJBRK2kKeAnwBPEMYbrgHGF9YdCzwb198HfBY4Ki6bQ+4/nXCZY2XcIVn5E8BJ7WohwExgVyFfJbf82li+OVd2USzrB3pa3p8JhdSbngVOya376Vg+CMwtxDlESK58AnB3btlfgemphRDGTP6di3UDcHRhu8OExPJNsXy+tY+sfcBNwCPx/WRgdW55Njy7TlU3NxJQVf+hqksIHyEDhJsTNopIJUmNARGZDNwLHBtzLFHVy1V1f4Mhtsb5ya3WJaWQfYTmvUzDrwUsi+UL490fMDRkekezwVX1RuBjwCvAScCGerf9NEscy7+HMNyrwEWquqbJMM/E+aRW65NSyFpV3ZV7fwdDQ6jZ72s8H+fH1dn+LbnXr9RLoKrrgfOA/wBV4Bbg4EgrTOjOXge8N75frqq3jyBOdj7S8ne9pBDyYpwXm/cgQzsr6zHV4vycOnEyabtUta4QAFXdRBhxHAQWA59stsKFnFnvKH9nypEYiPPimH/2BT5Pt1CXQIKD+mLCDt8DjMuVX8jQAfyYWLYglg0A83LrloHH4rJHGsx7GYeeG4zkoJ5Nm4FyA9tdnMvZE8vOi3/jXmBCy/szgZCx8T8j6/qtJPSKspOqWwrrPxTLXyb8Gs+KnAwFLmgi95rCjh2JkJeBqQ1uNyO33XpCB2Ywvr+y1X2ZREis6BzCDQTFbu+jwHGFdScT+vf1usm3Eb8yqsG84wkH1FaELG3yb72zTr1/SqGLPNIp2fdliciE+IfOJhwse4FbNdyOU1z3KMJJ4TzCecbTwAOqWryhrZG804Al8e03dZjjT26bucBHgD0aem/N5BsDfAU4i3ASeZ+q/ry5Wg8TP5UQJw2WLr93FBFJ9dN8LdH1QkRkqYjsBP4pIv860rMjInKmiDwcp6afLWmYFAei/9UJuIL6nYub66w7P7f8DbvII526toWIyNsJXfT9wJcJnYvPEbrBF4vIzE7Uq2uFELrqbwYeVNXVqvqCqt4K/IrwJNbZnahUNwvJxvCfLJT/Mc6TXU1uhm4W8uc4P79Qnj3hJXSAjt3ba4AthLP8aSKyDfgZsJChh3k6Qte2EA2DT58nXBR8D+G5lY7KgO5uIahqr4jMIjwZPJ3wMTaLwx+3GzW6WgiAqvYRur0AiMgahhdygNYGxYalaz+yRkDW69qhbbwA2PUtJCM+MDSboR1/uoh8Ib4eByyNr59raz38am9ARK4Grmpg1YWqem+76uEtpDFeI9wP8L12ygD4L3/qoLUPyCaWAAAAAElFTkSuQmCC">
                        </div>
                    </div>
                </div>
            </div>
            <div id="btn-mobile-container">
                <div class="btn-group">
                    <button id="btn-mobile-menu" class="btn btn-large btn-inverse">
                        <i class="icon-th"></i>
                        เมนู
                    </button>
                    <button id="btn-mobile-search" class="btn btn-large btn-inverse">
                        <i class="icon-search"></i>
                        ค้นหา
                    </button>
                </div>
            </div>
            <!--/header-->
            <!--nav-->
            <div id="nav-container">
                <div class="container-fluid">
                    <div id="nav">
                        <i class="icon-sort-up arrow"></i>
                        <?= $_nav ?>
                    </div>
                </div>
            </div>
            <!--/nav-->
            <!--search-->
            <div id="search" class="container">
                <script>
  (function() {
    var cx = '000972291994809008767:xp7cjel9soe';
    var gcse = document.createElement('script');
    gcse.type = 'text/javascript';
    gcse.async = true;
    gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
        '//www.google.com/cse/cse.js?cx=' + cx;
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(gcse, s);
  })();
</script>
<gcse:search></gcse:search>
            </div>
            <!--/search-->
            <div id="loading" >
                <i class="icon-spinner icon-spin"></i>
            </div>
            <!--page-->
            <div id="page">
                <?
                switch ($_type) {
                    case "Table":
                        include("$_base/php/table.php");
                        break;
                    case "Standard":
                        include("$_base/php/standard.php");
                        break;
                    case "Custom":
                        if ($_page == "home" || $_page == "contact") {
                            include("$_base/th/ajax/$_page.php");
                        } else {
                            include("$_base/ajax/$_page.php");
                        }
                        break;
                    case "Entry":
                        if ($_entry == "request") {
                            include("$_base/th/ajax/request.php");
                        } else {
                            include("$_base/php/entry.php");
                        }
                        break;
                    case "Album":
                        include("$_base/ajax/album.php");
                        break;
                    case "Event":
                        include("$_base/ajax/event.php");
                        break;
                    case "Resident":
                        include("$_base/ajax/resident.php");
                        break;
                    default:
                        include("$_base/th/ajax/404.php");
                }
                ?>
            </div>
            <!--/page-->


            <div id="fold">
                <footer id="footer">
                    <div class="container-fluid">
                        <i class="tab tab-audioplayer icon-volume-up pull-right"></i>
                        <div class="row-fluid">
                            <div class="span4">
                                <div class="btn-group">
                                    <a href="/contact" onclick="nav('contact');
                return false;" class="btn link">
                                        <i class="icon icon-envelope"></i>
                                        ติดต่อเรา
                                    </a>
                                    <a href="/visiting/directions-thai" onclick="navSub('visiting', 'directions-thai', 'กิจวัตรประจำวัน');
                return false;" class="btn link">
                                        <i class="icon icon-map-marker"></i>
                                        เส้นทาง
                                    </a>
                                    <a id="call" href="tel:+7074851630"
                                       data-toggle="popover" title="" data-content="(707)4851630" data-original-title="Abhayagiri Phone" data-placement="top"
                                       class="btn link">
                                        <i class="icon icon-phone"></i>
                                        โทรศัพท์
                                    </a>
                                </div>
                            </div>
                            <div class="span4 pull-right">
                                <a id='btn-language' href="/" class="btn pull-right" style='font-family:arial'>
                                    <span class='flag flag-us'></span>
                                    English
                                </a>
                                <a id='rss' class="btn link pull-right"
                                   data-toggle="popover" title="" data-content="
                                   <a href='http://feed.abhayagiri.org/abhayagiri-news' target='_blank' class='btn'><i class='icon icon-bullhorn'></i> ข่าว</a><br><br>
                                   <a href='http://feed.abhayagiri.org/abhayagiri-calendar' target='_blank' class='btn'><i class='icon icon-calendar'></i> ปฏิทิน</a><br><br>
                                   <a href='http://feed.abhayagiri.org/abhayagiri-talks' target='_blank' class='btn'><i class='icon icon-volume-up'></i> เสียงธรรม</a><br><br>
                                   <a href='http://itunes.apple.com/us/podcast/abhayagiri-dhamma-talks/id600138148?mt=2' target='_blank' class='btn'><i class='icon icon-music'></i> iTunes</a>"
                                   data-original-title="RSS Links" data-placement="top">
                                    <i class="icon icon-rss"></i>
                                    RSS
                                </a>
                                <br><br>
                                <div id="copyright" class="muted pull-right">&copy; Abhayagiri Foundation <?= date("Y") ?></div>
                            </div>
                        </div>
                    </div>
                </footer>

                <div id="audioplayer" class="navbar navbar-inverse navbar-fixed-bottom closed">
                    <?
                    $data = $func->entry('audio');
                    foreach ($data as $row) {
                        $title = $row['title'];
                        $author = $row['author'];
                        $date = $func->display_date($row['date']);
                        $mp3 = $row['mp3'];
                        $img = '/media/images/speakers/speakers_' . strtolower($func->stripDiacritics(str_replace(' ', '_', $author))) . '.jpg';
                        ?>
                        <div class="container-fluid">
                            <i class="tab tab-audioplayer icon-volume-up pull-right"></i>
                        </div>
                        <div class='audioplayer-inner'>
                            <div class="container-fluid">
                                <div class='row-fluid'>
                                    <div id='info-container' class='span4'>
                                        <div class='media'>
                                            <span class='pull-left'>
                                                <img id='speaker' class='media-object' src='<?= $img ?>' data-src='$img/50x50'>
                                            </span>
                                            <div id='text' class='media-body'>
                                                <span class='title'><?= $author ?></span>
                                                <div class='author'><?= $title ?></div>
                                                <div class='date'><i><?= $date ?></i></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id='time-container' class='span4'>
                                        <div  class='row-fluid'>
                                            <div class='media'>
                                                <span id='buttons' class='pull-left'>
                                                    <button class='btn play' onclick="play(this, <?= "'$title','$author','$date','$img','$mp3'" ?>);">
                                                        <i class='icon-play'></i>
                                                    </button>
                                                </span>
                                                <div class='media-body'>
                                                    <div id='time' class="progress">
                                                        <div id='duration' class="bar" style="position:absolute;z-index:100;width:0%;"></div>
                                                        <div id='buffer' class="bar bar-warning" style="position:absolute;width:0%;"></div>
                                                    </div>
                                                    <span id='elapsed'>00:00:00 / 00:00:00</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id='volume-container' class='span4'>
                                        <div class='media hidden-phone hidden-tablet'>
                                            <div class='media-body'>
                                                <span class='pull-right'>
                                                    <i class='icon-volume-up'></i>
                                                </span>
                                                <div id='volume' class="progress">
                                                    <div class="bar" style="width:100%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <? }
                    ?>
                </div>
            </div>
            <!--fold-->


        </div>
        <!-- /wrapper -->



        <!--script-->
        <script type="text/javascript" src="/js/plugins/LAB.js"></script>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script>
            $LAB
                    .script('/th/js/lang.js')
                    .script('/js/main.js?<?php echo $config['stamp']; ?>')
                    .script('/js/plugins.js')
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
                    .script('/js/plugins/jquery.masonry.js')
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
        <!--/script-->

    </body>
    <!--/body-->

</html>


