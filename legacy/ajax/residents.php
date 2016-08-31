<?php
$stmt = $db->_select('residents', '*', array("language"=>$_language,"status"=>"open"));
foreach ($stmt as $row) {
?>
    <div class='media residents'>
        <span class='pull-left'>
            <img class='media-object img-residents' src="/media/images/residents/<?= $row['photo']; ?>">
        </span>
        <div class='media-body'>
            <a href='<?= $_lang['base'] ?>/community/residents/<?= $row['url_title'] ?>' onclick='navResident("<?= $row['url_title'] ?>");
                        return false;' class='title'>
               <?= $row['title'] ?>
            </a>
            <br><br><?= $row['body']; ?>
        </div>
    </div>
    <div class='backtotop' onclick="backtotop()">
        <span class='pull-right'>
            <i class='icon-caret-up'></i>
            back to top
        </span>
    </div>
    <hr class='border'>
<?php
}
