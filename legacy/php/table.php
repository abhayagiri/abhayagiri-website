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
                            <?
                            if ($_page != "news") {
                                echo $authors;
                            }
                            ?>' autocomplete="off">
                            <button onclick="return false;" class="btn btn-filter" type="button"><i class="<?= $_icon ?>"></i></button>
                        </div>
                    </span>
                    <? if ($_page == "audio" || $_page == "books") { ?>
                        <div id="filter-category" class="btn-group btn-dropdown">
                            <button class="btn dropdown-toggle" data-toggle="dropdown">
                                <?= $_lang['category'] ?>
                                <i class="icon icon-caret-down"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="option"><?= $_lang['all'] ?></a></li>
                                <? if ($_page == "audio") { ?>
                                    <li><a class="option"><?= $_lang['dhamma_talk'] ?></a></li>
                                    <li><a class="option"><?= $_lang['chanting'] ?></a></li>
                                    <li><a class="option"><?= $_lang['retreat'] ?></a></li>
   <li><a class="option"><?= $_lang['collection'] ?></a></li>
                                <? } else { ?>
                                    <li><a class="option">mobi</a></li>
                                    <li><a class="option">ePub</a></li>
                                    <li><a class="option">PDF</a></li>
                                    <li><a class="option"><?= $_lang['print_copy'] ?></a></li>
                                <? } ?>
                            </ul>
                        </div>
                        <? if ($_language == "Thai") { ?>
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
                            <?
                        }
                    }
                    ?>

                </div>
            </div>
        </div>
    </div>
</div>
<div id="content" class="container-fluid">
<?
        $stmt = $db->_select("pages", "body", array("url_title" => $_page));
        echo $stmt[0]['body'];
        ?>

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