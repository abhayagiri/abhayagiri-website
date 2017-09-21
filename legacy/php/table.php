<?php

$stmt = $func->authors();
$authors = array();
foreach ($stmt as $row) {
    $authors[] = $row['title'];
}
$authors = '["' . implode('","', $authors) . '"]';

?>
<!--image-->
<div id="banner" style="margin-bottom:0px">
    <div class="title"><?= "<i class='$_icon'></i> $_page_title" ?></div>
    <img src="/media/images/banner/<?= $_page ?>.jpg">
</div>
<!--/image-->
<!--body-->

<div id="breadcrumb-container">
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span4">
                <ul class="breadcrumb">
                    <li><a href="<?= $_lang['base'] ?>/" onclick="nav('home');
                            return false;"><?= $_lang['home'] ?></a> <span class="divider">/</span></li>
                    <li class="active"><?= $_page_title ?></li>
                </ul>
            </div>
            <div class="span8">
                <div id="filter" class="pull-right">
                    <span id='filter-search'>
                        <div class="input-append">
                            <input type="text" class="span2" data-provide="typeahead" data-source='
                            <?php
                            if ($_page != "news") {
                                echo $authors;
                            }
                            ?>' autocomplete="off">
                            <button onclick="return false;" class="btn btn-filter" type="button"><i class="<?= $_icon ?>"></i></button>
                        </div>
                    </span>
                    <?php if ($_page == "audio" || $_page == "books") { ?>
                        <div id="filter-category" class="btn-group btn-dropdown">
                            <button class="btn dropdown-toggle" data-toggle="dropdown">
                                <?= $_lang['category'] ?>
                                <i class="icon icon-caret-down"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="option"><?= $_lang['all'] ?></a></li>
                                <?php if ($_page == "audio") { ?>
                                    <li><a class="option"><?= $_lang['dhamma_talk'] ?></a></li>
                                    <li><a class="option"><?= $_lang['chanting'] ?></a></li>
                                    <li><a class="option"><?= $_lang['retreat'] ?></a></li>
   <li><a class="option"><?= $_lang['collection'] ?></a></li>
                                <?php } else { ?>
                                    <li><a class="option">mobi</a></li>
                                    <li><a class="option">ePub</a></li>
                                    <li><a class="option">PDF</a></li>
                                    <li><a class="option"><?= $_lang['print_copy'] ?></a></li>
                                <?php } ?>
                            </ul>
                        </div>
                        <div  class="input-append date form_datetime" data-date="">
                            <span class="add-on"><i class="icon-th"></i> Date </span>
                        </div>
                        <?php if ($_language == "Thai") { ?>
                            <div id = "filter-language" class = "btn-group btn-dropdown">
                                <button class = "btn dropdown-toggle" data-toggle = "dropdown">
                                    <?= $_lang['language'] ?>
                                    <i class="icon icon-caret-down"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="option"><?= $_lang['thai'] ?></a></li>
                                    <li><a class="option"><?= $_lang['english'] ?></a></li>
                                </ul>
                            </div>
                            <?php
                        }
                    }
                    ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php if (strtolower($_page) === "audio") { ?>
    <div class="audio-subscriptions">
        Receive dhamma talks automatically by subscribing through
        <a href="https://itunes.apple.com/us/podcast/abhayagiri-dhamma-talks/id600138148?mt=2" target="_blank">iTunes</a>,
        <a href="https://www.youtube.com/c/AbhayagiriBuddhistMonastery" target="_blank">YouTube</a>, or
        <a href="http://feed.abhayagiri.org/abhayagiri-talks" target="_blank">RSS</a>.
    </div>
<?php }?>
<div id="page-info">
    <?php if ($_page === 'reflections') { ?>
        <p>Receive Reflections automatically with:</p>
        <p><a href="http://eepurl.com/cavvzX" class="btn">
            <i class="icon icon-envelope"></i> Email
        </a></p>
        <p><a href="https://feeds.feedburner.com/abhayagiri-reflections" class="btn">
            <i class="icon icon-rss"></i> RSS
        </a></p>
    <?php } ?>
</div>
<div id="content" class="container-fluid">
    <table cellpadding="0" cellspacing="0" border="0" class="table" id="datatable">
        <thead>
            <tr>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>

            </tr>
        </tbody>
    </table>
</div>