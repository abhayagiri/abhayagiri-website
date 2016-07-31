<?php
$_page = $_POST['_page'];
$_page_id = $_POST['_page_id'];
include('db.php');
$stmt = $db->_select('pages','body',array('id'=>$_page_id));
?>
<div id="breadcrumb-container">
    <ul class="breadcrumb container">
        <li><a href="/dashboard">Dashboard</a> 
            <? if($_page){?>
            <span class="divider">/</span>
                <?}?>
        </li>
        <?php 
        if(isset($_page)){ ?>
                <li class="active"><?=$_page?></li>
        <?}?>
    </ul>
</div>
<div class ="container-fluid">
    <?=$stmt[0]['body'];?>    
</div>