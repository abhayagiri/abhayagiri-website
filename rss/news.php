<?
include('/home/abhayagiri/www/php/main.php');
echo "<?xml version='1.0' encoding='utf-8'?>";
?>
<rss version="2.0">
    <channel>
        <title>Abhayagiri News Feed</title>
        <link>https://www.abhayagiri.org/news</link>
        <description>Abhayagiri News</description>
        <?
        $data = $func->entry('news', 100);
        foreach ($data as $row) {
            $url = $row['url_title'];
            $body = $row['body'];
            $title = $row['title'];
            $date = date("r", strtotime($row['date']));
            ?>
            <item>
                <title><![CDATA[<?= $title ?>]]></title>
                <link><?= "https://www.abhayagiri.org/audio/$url" ?></link>
                <description><![CDATA[<?= $body ?>]]></description>
                <guid>https://www.abhayagiri.org/news/<?= $url ?></guid>
                <pubDate><?= $date ?></pubDate>
            </item>
        <? } ?>
    </channel>
</rss>

