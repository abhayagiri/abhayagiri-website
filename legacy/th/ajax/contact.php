
<!--image-->
<div id="banner">
    <div class="title"><?= "<i class='$_icon'></i> $_page_title" ?></div>
    <img src="/media/images/banner/contact.jpg">
</div>
<!--/image-->
<!--body-->
<div id="breadcrumb-container">
    <div class="container-fluid">
        <ul class="breadcrumb">
            <li><a href="/" onclick="nav('home');
                    return false;">หน้าหลัก</a> <span class="divider">/</span></li>
            <li class='active'>ติดต่อเรา</li>
        </ul>
    </div>
</div>
<form id="form" method='POST' class="form-horizontal" action="">
    <?php echo csrf_field(); ?>
    <div id="content" class="container-fluid">
        <div id="alert">
            <div class = "alert alert-success" style="display:none">ข้อความของคุณได้รับการส่งเรียบร้อยแล้ว</div>
            <div class = "alert alert-error" style="display:none">เกิดข้อผิดพลาดและข้อความไม่ได้ส่ง โปรดลองอีกครั้งในภายหลัง</div>
            <div class = "alert alert-warning" style="display:none">You message is being sent, please hold...</div>
        </div>
        <legend>แบบฟอร์มติดต่อเรา</legend>
        <?= \App\Models\Blob::getBlob('contact.form', [ 'lng' => 'th' ]) ?>
        <br><hr>
        <input id="page" type="hidden" name="page" value="contact">
        <div class = "control-group">
            <label class = "control-label" for = "name"><b>ชื่อ</b></label>
            <div class = "controls">
                <input type = "text" id="name" name="name" placeholder = "ชื่อ" class = "span3" required>
            </div>
        </div>
        <div class = "control-group">
            <label class = "control-label" for = "inputIcon"><b>อีเมล์</b></label>
            <div class = "controls">
                <div class = "input-prepend">
                    <span class = "add-on"><i class = "icon-envelope"></i></span>
                    <input class = "span3" id = "email" name="email" type = "email" placeholder = "อีเมล์" required>
                </div>
            </div>
        </div>
        <div class = "control-group">
            <label  class = "control-label" for = "email"><b>ข้อความ</b></label>
            <div class = "controls">
                <textarea id="message" rows = "12" class = "span8" name="message" required></textarea>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label"></label>
            <div class="controls">
                <?php echo app('captcha')->display() ?>
            </div>
        </div>
        <hr>
        <div class = "control-group">
            <button type = "submit" class = "btn btn-large btn-primary" onclick="
                    submitForm('contact');
                    return false;">ส่ง</button>
            <button type = "submit" class = "btn btn-large" onclick="clearForm();
                    return false;">ยกเลิก</button>
        </div>
    </div>
</form>
