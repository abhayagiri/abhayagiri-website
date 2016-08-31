<?php

require base_path('legacy/bootstrap.php');
require base_path('legacy/mahapanel/php/session.php');

$_page = $_POST['_page'];
$_subpage = $_POST['_subpage'];
$_url = $_POST['url'];
?>
<div id="breadcrumb-container">
    <ul class="breadcrumb container">
        <li>
            <a href="/mahapanel/dashboard">Dashboard</a>
            <?php if ($_page) { ?>
                <span class="divider">/</span>
            <?php } ?>
        </li>
        <?php 
            if (isset($_page)) {
                if (!isset($_subpage)) {
        ?>
                    <li class="active"><?php echo $_page ?></li>
        <?php
                } else {
        ?>
                    <li>
                        <a href="<?php echo $url ?>"><?php echo $_page ?></a>
                        <span class="divider">/</span>
                    </li>
                    <li class="active"><?php echo $_subpage ?></li>
        <?php
                }
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
