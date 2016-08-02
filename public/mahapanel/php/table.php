<?php

require_once __DIR__ . '/../mahapanel-bootstrap.php';

$_page = $_POST['_page'];
$_subpage = $_POST['_subpage'];
$_url = $_POST['url'];
?>
<div id="breadcrumb-container">
    <ul class="breadcrumb container">
        <li><a href="/mahapanel/dashboard">Dashboard</a> 
            <? if($_page){?>
            <span class="divider">/</span>
                <?}?>
        </li>
        <?php 
        if(isset($_page)){ 
            if(!isset($_subpage)){?>
                <li class="active"><?=$_page?></li><?
            }
        else{?>
            <li><a href="<?=$url?>"><?=$_page?></a><span class="divider">/</span></li> 
            <li class="active"><?=$_subpage?></li>
        <?}
        }
        ?>
    </ul>
</div>
<div class ="container-fluid">
    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatable" >
        <thead>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>