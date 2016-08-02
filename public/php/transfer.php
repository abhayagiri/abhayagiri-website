<?php

require_once __DIR__ . '/../www-bootstrap.php';

/* ------------------------------------------------------------------------------
  Connect
  ------------------------------------------------------------------------------ */

$db = Abhayagiri\DB::getPDOConnection();
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

/* ------------------------------------------------------------------------------
  News
  ------------------------------------------------------------------------------ */

function xfer_news($db) {
    try {
        $stmt = $db->query("SELECT entry_id, field_id_52 FROM data WHERE channel_id = 28");
        $stmt = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($stmt as $data) {
            $entry_id = $data['entry_id'];
            echo $entry_id . '<br/>';
            $content = $data['field_id_52'];
            $stmt2 = $db->query("SELECT title, year, month, day FROM titles WHERE entry_id = $entry_id");
            $titles = $stmt2->fetch();
            $title = $titles['title'];
            echo $title;
            $year = $titles['year'];
            $month = $titles['month'];
            $day = $titles['day'];
            $date = $year . '-' . $month . '-' . $day;
            $stmt3 = $db->prepare("INSERT into news (title,date,content) VALUES (:title,:date,:content)");
            $stmt3->bindParam(":title", $title);
            $stmt3->bindParam(":date", $date);
            $stmt3->bindParam(":content", $content);

            $stmt3->execute();
        }
    } catch (PDOException $e) {
        echo("Failed to connect to database: " . $e->getMessage());
    }
}

function xfer_audio($db) {
    try {
        $stmt = $db->query("SELECT entry_id, field_id_59, field_id_60, field_id_61, field_id_62, field_id_77, field_id_100 FROM data WHERE channel_id = 6");
        $stmt = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($stmt as $data) {
            $entry_id = $data['entry_id'];
            echo $entry_id . '<br/>';
            $author = explode("] ", $data['field_id_59']);
            $author = explode(" ", $author[2]);
            if ($author[1] == "Liam")
                $author[1] = "Liem";
            echo $author[1];
            $stmt2 = $db->query("SELECT id FROM authors WHERE lname = '$author[1]'");
            $id = $stmt2->fetch();
            $author = $id['id'];
            echo $author;
            $date = date("Y-m-d", $data['field_id_60']);
            $file = $data['field_id_61'];
            $content = $data['field_id_62'];
            $language = $data['field_id_77'];
            $type = $data['field_id_100'];
            $stmt2 = $db->query("SELECT title, year, month, day,  url_title FROM titles WHERE entry_id = $entry_id");
            $titles = $stmt2->fetch();
            $title = $titles['title'];
            $url_title = str_replace("-", "_", strtolower($titles['url_title']));
            $year = $titles['year'];
            $month = $titles['month'];
            $day = $titles['day'];
            echo $title;
            if ($title == "Novice Ordination Talk")
                $author = 1;
            if ($date == "1969-12-31")
                $date = $year . '-' . $month . '-' . $day;
            $stmt3 = $db->prepare("INSERT into audio (title, url_title, date, author,content,language,type,filepath) VALUES (:title,:url_title,:date,:author,:content,:language,:type,:filepath)");
            $stmt3->bindParam(":title", $title);
            $stmt3->bindParam(":url_title", $url_title);
            $stmt3->bindParam(":date", $date);
            $stmt3->bindParam(":author", $author);
            $stmt3->bindParam(":content", $content);
            $stmt3->bindParam(":language", $language);
            $stmt3->bindParam(":type", $type);
            $stmt3->bindParam(":filepath", $file);
            if ($author != "") {
                $stmt3->execute();
            }
            else
                echo "<span style='color:red'>ERROR</span>";
        }
    } catch (PDOException $e) {
        echo("Failed to connect to database: " . $e->getMessage());
    }
}

function fix_authors($db) {
    try {
        $stmt = $db->query("SELECT title FROM options WHERE parent = 7");
        $stmt = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($stmt as $row) {
            $name = $row['title'];
            $stmt_books = $db->query("INSERT INTO authors (title) VALUES ('$name')");
            $stmt_books->execute();
        }
    } catch (PDOException $e) {
        echo("Failed to connect to database: " . $e->getMessage());
    }
}

/*
  function fix_books($db) {
  try {
  $stmt = $db->query("SELECT id,img FROM books");
  $stmt = $stmt->fetchAll(PDO::FETCH_ASSOC);
  foreach ($stmt as $data) {
  $entry_id = $data['id'];
  $img = $data['img'];
  $img = explode("}", $img);
  $img = $img[0];
  $stmt2 = $db->query("UPDATE books SET img='$img' WHERE id = $entry_id");
  $stmt2->execute();
  }
  } catch (PDOException $e) {
  echo("Failed to connect to database: " . $e->getMessage());
  }
  }
 */

function xfer_dropdown($db) {
    try {
        $stmt = $db->query("SELECT name FROM authors");
        $stmt = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($stmt as $data) {
            $name = $data['name'];
            $stmt2 = $db->query("INSERT INTO dropdowns (title,value) VALUES ('author','$name')");
            $stmt2->execute();
        }
    } catch (PDOException $e) {
        echo("Failed to connect to database: " . $e->getMessage());
    }
}

function fix_books($db) {
    try {
        $stmt = $db->query("SELECT entry_id, field_id_63, field_id_64, field_id_66, field_id_67, field_id_68, field_id_69, field_id_70, field_id_71, field_id_72, field_id_76, field_id_87 FROM exp_data WHERE channel_id = 5 AND field_id_71 = 'Yes'");
        $stmt = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($stmt as $data) {
            $entry_id = $data['entry_id'];
            //$date = $data['field_id_66'] . '-00-00';

            $stmt2 = $db->query("SELECT url_title FROM exp_titlez WHERE entry_id = $entry_id");
            $titles = $stmt2->fetch();
            $url_title = $titles['url_title'];
            $stmt3 = $db->query("UPDATE books SET request = 'Print Copy' WHERE url_title = '$url_title'");
            echo "UPDATE books SET request = 'Print Copy' WHERE url_title = '$url_title'<br>";
            $stmt3->execute();
        }
    } catch (PDOException $e) {
        echo("Failed to connect to database: " . $e->getMessage());
    }
}

function underscore_to_hyphen($db, $table) {
    try {
        $stmt = $db->query("SELECT id,url_title FROM $table");
        $stmt = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($stmt as $data) {
            $url_title = str_replace('_', '-', $data['url_title']);
            $id = $data['id'];
            $stmt2 = $db->query("UPDATE $table SET url_title = '$url_title' WHERE id = $id");
            $stmt2->execute();
            echo "changed $id to $url_title<br>";
        }
    } catch (PDOException $e) {
        echo("Failed to connect to database: " . $e->getMessage());
    }
}

function xfer_search($db, $table) {
    try {
        $stmt = $db->query("SELECT title,url_title,body,date,author,status FROM $table");
        $stmt = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $id = 0;
        foreach ($stmt as $data) {
            $id = $data['id'];
            $title = $data['title'];
            $url_title = $data['url_title'];
            $date = $data['date'];
            $body = $data['body'];
            $author = $data['author'];
            //$author = "";
            $status = $data['status'];
            $stmt2 = $db->prepare("INSERT IGNORE INTO search (page,url_title,title,date,body,author,status) VALUES (:page,:url_title,:title,:date,:body,:author,:status)");
            $stmt2->bindParam(":page", $table);
            $stmt2->bindParam(":url_title", $url_title);
            $stmt2->bindParam(":title", $title);
            $stmt2->bindParam(":date", $date);
            $stmt2->bindParam(":body", $body);
            $stmt2->bindParam(":author", $author);
            $stmt2->bindParam(":status", $status);
            $stmt2->execute();
            $id++;
            echo "INSERT INTO search (url_title,title,body,author) VALUES ('$url_title','$title','$body','$author')<br>";
        }
    } catch (PDOException $e) {
        echo("Failed to connect to database: " . $e->getMessage());
    }
}

function xfer_books($db) {
    try {
        $stmt = $db->query("SELECT entry_id, field_id_63, field_id_64, field_id_66, field_id_67, field_id_68, field_id_69, field_id_70, field_id_71, field_id_72, field_id_76, field_id_87 FROM data WHERE channel_id = 5");
        $stmt = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($stmt as $data) {
            $date = date("Y", $data['field_id_66']);

//$entry_id = $data['entry_id'];
//echo $entry_id . '<br/>';
//$author = explode("] ", $data['field_id_87']);
//echo $author[2];
//if (preg_match('/\d/', $author[2]))
//$author[2] = 0;
//echo $author[2];
//if ($author[3])
//$author2 = str_replace("-", " ", substr($author[3], 1));
//else
//$author2 = 0;
//$stmt2 = $db->query("SELECT id FROM authors WHERE name = '$author[2]'");
//$id = $stmt2->fetch();
//$author = $id['id'];
//if (!$author)
//$author = 0;
//echo $author;
//$pdf = $data['field_id_68'];
//$epub = $data['field_id_69'];
//$mobi = $data['field_id_70'];
//$request = $data['field_id_71'];
//$body = $data['field_id_67'];
//$language = $data['field_id_76'];
//$weight = $data['field_id_72'];
//$subtitle = $data['field_id_67'];
//$img = $data['field_id_63'];
//$img = explode("}", $img);
//$img = $img[1];
//$stmt2 = $db->query("SELECT title, year, month, day,  url_title FROM titles WHERE entry_id = $entry_id");
//$stmt2 = $db->query("SELECT title, year, month, day,  url_title FROM titles WHERE entry_id = $entry_id");
            /*
              $titles = $stmt2->fetch();
              $title = $titles['title'];
              $url_title = str_replace("-", "_", strtolower($titles['url_title']));
              $year = $titles['year'];
              $month = $titles['month'];
              $day = $titles['day'];
              echo $title;
              if ($date == "1969-12-31")
              $date = $year . '-' . $month . '-' . $day;
              $stmt3 = $db->prepare("INSERT into books (author, author2, title, url_title, subtitle, date, body, language, img, weight, pdf, epub, mobi, request) VALUES (:author, :author2, :title, :url_title, :subtitle, :date, :body, :language, :img, :weight, :pdf, :epub, :mobi, :request)");
              $stmt3->bindParam(":author", $author);
              $stmt3->bindParam(":author2", $author2);
              $stmt3->bindParam(":title", $title);
              $stmt3->bindParam(":url_title", $url_title);
              $stmt3->bindParam(":subtitle", $subtitle);
              $stmt3->bindParam(":date", $date);
              $stmt3->bindParam(":body", $body);
              $stmt3->bindParam(":language", $language);
              $stmt3->bindParam(":img", $img);
              $stmt3->bindParam(":weight", $weight);
              $stmt3->bindParam(":pdf", $pdf);
              $stmt3->bindParam(":epub", $epub);
              $stmt3->bindParam(":mobi", $mobi);
              $stmt3->bindParam(":request", $request);
              $stmt3->execute(); */
        }
    } catch (PDOException $e) {
        echo("Failed to connect to database: " . $e->getMessage());
    }
}

/* ------------------------------------------------------------------------------
  Main
  ------------------------------------------------------------------------------ */
//xfer_news($db);
//xfer_audio($db);
//xfer_books($db);
//fix_books($db);
//xfer_dropdown($db);
//underscore_to_hyphen($db, 'subpages');
//fix_books($db);
xfer_search($db, 'audio');
?>