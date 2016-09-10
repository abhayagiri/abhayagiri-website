<?php

if (Lang::locale() === 'th') {
    $url_title = 'dana-wish-list-thai';
} else {
    $url_title = 'dana-wish-list';
}

$stmt = $db->_select('danalist', 'title,link,body', array("status" => "open"));
$subpage = $db->_select('subpages', 'source,body,title', array("url_title" => $url_title, "language" => $_language, "status" => "Open"), '');
?>
<p>
	<?=$subpage[0]['body']?>
</p>
<br>
<table class='table table-striped table-bordered'>
    <thead>

    </thead>
    <tbody>
        <?php foreach ($stmt as $row) { ?>
            <tr>
                <td>
                    <?= $row['title'] ?>
                </td>
                <td>
                    <a href='<?= $row['link'] ?>'><?= $row['link'] ?></a>

                </td>
                <td>
                    <?= $row['body'] ?>

                </td>
            </tr>
        <?php }
        ?>
    </tbody>
</table>

