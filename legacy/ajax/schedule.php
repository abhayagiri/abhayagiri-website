<?php
$stmt = $db->_select('schedule', 'title,body,time', array("status" => "open", "language" => $_language));
?>
<table class='table table-striped'>
    <thead>

    </thead>
    <tbody>
        <?php foreach ($stmt as $row) { ?>
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
        <?php }
        ?>
    </tbody>
</table>
<hr>
