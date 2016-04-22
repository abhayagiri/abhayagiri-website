<?
$_base = dirname(dirname(__FILE__));
include($_base . '/php/main.php');
$stmt = $func->entry('books', 10000);
foreach ($stmt as $row) {
    ?>
    <a href="http://www.abhayagiri.org/books/<?= $row['url_title'] ?>"><?= $row['title'] ?></a>
<? }
?>