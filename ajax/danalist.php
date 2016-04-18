<?php
$stmt = $db->_select('danalist', 'title,link,body', array("status" => "open"));
$subpage = $db->_select('subpages', 'source,body,title', array("url_title" => "dana-wish-list", "language" => $_language, "status" => "Open"), '');
?>
<p>
	<?=$subpage[0]['body']?>
</p>
<br>
<table class='table table-striped table-bordered'>
    <thead>

    </thead>
    <tbody>
        <? foreach ($stmt as $row) { ?>
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
        <? }
        ?>
    </tbody>
</table>

