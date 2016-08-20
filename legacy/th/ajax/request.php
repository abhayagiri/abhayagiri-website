<?
	$stmt = $db->_select("misc", "body", array("url_title" => "book-request-thai"));
?>
<!--image-->
<div id="banner">
    <div class="title"><?= "<i class='icon-book'></i> Book Request" ?></div>
    <img src="/media/images/banner/books.jpg">
</div>
<!--/image-->
<!--body-->
<div id="breadcrumb-container">
    <div class="container-fluid">
        <ul class="breadcrumb">
            <li>
                <a href="/" onclick="nav('home', 'Custom');
                        return false;">หน้าหลัก</a>
                <span class="divider">/</span>
            </li>
            <li>
                <a href="/" onclick="nav('books', 'Table', '<?= $_icon ?>');
                        return false;">หนังสือ</a>
                <span class="divider">/</span>
            </li>
            <li class='active'>ขอหนังสือ</li>
        </ul>
    </div>
</div>
<div id="content">
    <form id="form" method='POST' class="form-horizontal" action="">
        <div class='container-fluid'>
            <div id="alert">
                <div class = "alert alert-success" style="display:none">ข้อความของคุณได้รับการส่งเรียบร้อยแล้ว</div>
                <div class = "alert alert-error" style="display:none">กรุณากรอกข้อมูลทั้งหมดก่อนที่จะส่ง</div>
            </div>
                     <?=$stmt[0]['body']?>
            <br><br>
            <legend>หนังสือที่เลือก</legend>
            <div id="selection">
                <?php
                $books = Session::get('books');
                if (!empty($books)) {
                    foreach ($books as $id => $quantity) {
                        $stmt = $func->book($id);
                        $title = $stmt['title'];
                        $author = $stmt['author'];
                        $cover = $stmt['cover'];
                        $weight = $stmt['weight'];
                        ?>

                        <div class='media'>
                            <span class='pull-left'>
                                <img class='img-books media-object' src="/media/images/books/<?= $cover ?>">
                            </span>
                            <div class='media-body'>
                                <div class="row-fluid">
                                    <div class="span8">
                                        <span class='title'><?= $title ?></span><br>
                                        <?= $author ?><br>
                                        <a href='javascript:removeBook(<?= $id ?>)'>ยกเลิกหนังสือ</a>
                                        <input name="title[]" value="<?= $title ?>" type="hidden">
                                        <input name="author[]" value="<?= $author ?>" type="hidden">
                                    </div >
                                    <div class="body span2">
                                        น้ำหนัก: <?= $weight ?>
                                        <input name="weight[]" value="<?= $weight ?>" type="hidden">
                                    </div>
                                    <div class="body span2">
                                        จำนวน: <br>
                                        <div class="input-append">
                                            <input value="<?= $quantity ?>" name="quantity[]" class='span2' type='text' maxlength=2 />
                                            <button class="btn" type="button" onclick="updateBook(<?= $id ?>, $(this).siblings('input').val())">Update</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <?
                    }
                }
                ?>
            </div>
            <br>
            <hr>
            <div class="row-fluid">
                <span class="span6"></span>
                <span class="span6">
                    <a class="btn pull-left" href="/books" style="margin-left:-73px" onclick="nav('books');
                        return false;"><i class="icon icon-plus"></i> Add More Books
                    </a>
                </span>
            </div>
            <br>
            <br>
            <legend>ข้อมูลการจัดส่ง</legend>
            <div class="control-group">
                <label class="control-label" for="fname">ชื่อ</label>
                <div class="controls">
                    <input id="fname" class="span3" name="fname" type="text" placeholder="ชื่อ" required>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="lname">นามสกุล</label>
                <div class="controls">
                    <input id="lname" class="span3" name="lname" type="text" placeholder="นามสกุล" required>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="email">อีเมล์</label>
                <div class="controls">
                    <div class="input-prepend">
                        <span class="add-on"><i class="icon-envelope"></i></span>
                        <input id="email" class="span4" name="email" id="inputIcon" type="email" placeholder="อีเมล์" required>
                    </div>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="address">ที่อยู่</label>
                <div class="controls">
                    <input id="address" class="span5" name="street" type="text" placeholder="ถนน" required>
                </div>
                <div class="controls controls-row">
                    <input id="city" class="span3" name="city" type="text" placeholder="เมือง" required>
                    <input id="state" class="span1" name="state" type="text" placeholder="รัฐ" required>
                    <input id="zip" class="span1" name="zip" type="text" placeholder="รหัสไปรษณีย์" required>

                </div>
                <div class="controls">
                    <input id="country" class="span5" name="country" type="text" placeholder="ประเทศ" required>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="comments">ความเห็นเพิ่มเติม</label>
                <div class="controls">
                    <textarea id="comments" name="comments" rows="12" class="span8"></textarea>
                </div>
            </div>
            <hr>
            <div class = "control-group">
                <input type="hidden" name="page" value="contact">
                <button type = "submit" class = "btn btn-large btn-primary" onclick="submitForm('contact');
                        return false;">ส่ง</button>
                <button type = "submit" class = "btn btn-large" onclick="clearForm();
                        return false;">ยกเลิก</button>
            </div>
        </div>
    </form>
</div>
