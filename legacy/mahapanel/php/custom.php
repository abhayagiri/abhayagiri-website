<?php

require base_path('legacy/bootstrap.php');
require base_path('legacy/mahapanel/php/session.php');

$_page = $_POST['_page'];
$_page_id = $_POST['_page_id'];
$db = Abhayagiri\DB::getDB();
$stmt = $db->_select('pages','body',array('id'=>$_page_id));
?>
<div id="breadcrumb-container">
    <ul class="breadcrumb container">
        <li><a href="/mahapanel/dashboard">Dashboard</a> 
            <?php if($_page){?>
            <span class="divider">/</span>
                <?php ?>
        </li>
        <?php 
        if(isset($_page)){ ?>
                <li class="active"><?=$_page?></li>
        <?php ?>
    </ul>
</div>
<div class ="container-fluid">
    <?=$stmt[0]['body'];?>    
</div>