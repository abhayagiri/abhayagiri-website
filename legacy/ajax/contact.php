
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
                    return false;">Home</a> <span class="divider">/</span></li>
            <li class='active'>Contact</li>
        </ul>
    </div>
</div>
<form id="form" method='POST' class="form-horizontal" action="">
    <?php echo csrf_field(); ?>
    <div id="content" class="container-fluid">
        <div id="alert">
            <div class = "alert alert-success" style="display:none">Your message has been sent successfully.</div>
            <div class = "alert alert-error" style="display:none">There was an error in processing your request.</div>
            <div class = "alert alert-warning" style="display:none">You message is being sent, please hold...</div>
        </div>
        <legend>Contact Form</legend>
        <?
        $stmt = $db->_select("misc", "body", array("url_title" => "contact"));
        echo $stmt[0]['body'];
        ?><br><hr>
        <input id="page" type="hidden" name="page" value="contact">
        <div class = "control-group">
            <label class = "control-label" for = "name"><b>Name</b></label>
            <div class = "controls">
                <input type = "text" id="name" name="name" placeholder = "Name" class = "span3" required>
            </div>
        </div>
        <div class = "control-group">
            <label class = "control-label" for = "inputIcon"><b>Email address</b></label>
            <div class = "controls">
                <div class = "input-prepend">
                    <span class = "add-on"><i class = "icon-envelope"></i></span>
                    <input class = "span3" id = "email" name="email" type = "email" placeholder = "Email" required>
                </div>
            </div>
        </div>
        <div class = "control-group">
            <label  class = "control-label" for = "email"><b>Message</b></label>
            <div class = "controls">
                <textarea id="message" rows = "12" class = "span8" name="message" required></textarea>
            </div>
        </div>
        <hr>
        <div class = "control-group">
            <button type = "submit" class = "btn btn-large btn-primary" onclick="
                    submitForm('contact');
                    return false;">Submit</button>
            <button type = "submit" class = "btn btn-large" onclick="clearForm();
                    return false;">Cancel</button>
        </div>
    </div>
</form>
