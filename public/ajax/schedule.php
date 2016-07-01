<?php
$stmt = $db->_select('schedule', 'title,body,time', array("status" => "open", "language" => $_language));
$page = $db->_select('subpages', 'body', array("url_title" => "daily-schedule-thai"));
?>
<table class='table table-striped'>
    <thead>

    </thead>
    <tbody>
        <? foreach ($stmt as $row) { ?>
            <tr>
                <td>
                    <?= $row['time'] ?>
                </td>
                <td>
                    <?= $row['title'] ?>

                </td>
                <td>
                    <?= $row['body'] ?>

                </td>
            </tr>
        <? }
        ?>
    </tbody>
</table>
<hr>
<?= $page[0]['body'] ?>
