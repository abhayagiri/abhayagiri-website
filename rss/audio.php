<?
include('/home/abhayagiri/www/php/main.php');
echo "<?xml version='1.0' encoding='utf-8'?>";
?>
<rss version="2.0">
    <channel >
        <title>Abhayagiri Audio</title>
        <link>http://www.abhayagiri.org/audio</link>
        <description>Abhayagiri Dahamma Talks</description>
        <?
        $data = $func->entry('audio', 100);
        foreach ($data as $row) {
            $url = $row['url_title'];
            $link = 'http://www.abhayagiri.org/audio/' . $url;
            $body = $row['body'];
            $title = $row['title'];
            $date = date("r", strtotime($row['date']));
            $author = $row['author'];
            $mp3 = $row['mp3'];  
            $desc = $body . '<br>' . "<a href='http://www.abhayagiri.org/media/audio/$mp3'>Download File</a>";
            ?>
            <item>
                <title><![CDATA[<?= $title ?>]]></title>
                <link><![CDATA[<?= $link ?>]]></link> 
                <description><![CDATA[<?= $desc ?>]]></description>
                <guid>http://www.abhayagiri.org/audio/<?= $url ?></guid>
                <pubDate><?= $date ?></pubDate>
            </item>
        <? } ?>
    </channel>
</rss>

