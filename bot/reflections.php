<?
include('/home/abhayagiri/www/php/main.php');
$stmt = $func->entry('reflections', 10000);
foreach ($stmt as $row) {
    ?>
    <a href="http://www.abhayagiri.org/reflections/<?= $row['url_title'] ?>"><?= $row['title'] ?></a>
<? }
?>