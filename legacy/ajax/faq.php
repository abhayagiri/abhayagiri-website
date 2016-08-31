<?php
$data = $db->_select('faq', '*');
foreach ($data as $row) {
?>
    <div>
        <p>
            <span class="title"><?= $row['title'] ?></span>
        <p>

        <p>
            <?= $row['body'] ?>
        </p>
    </div>
    <hr class='border'>
<?php
}
?>
