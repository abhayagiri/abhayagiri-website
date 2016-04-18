<?
include('/home/abhayagiri/www/php/main.php');
$stmt = $func->entry('news', 10000);
foreach ($stmt as $row) {
    ?>
    <a href="http://www.abhayagiri.org/news/<?= $row['url_title'] ?>"><?= $row['title'] ?></a>
<? }
?>