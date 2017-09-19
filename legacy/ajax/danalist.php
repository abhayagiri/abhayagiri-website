<?php

if (Lang::locale() === 'th') {
    $url_title = 'dana-wish-list-thai';
} else {
    $url_title = 'dana-wish-list';
}

$stmt = App\Models\Danalist::getLegacyStatement();

?>
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

