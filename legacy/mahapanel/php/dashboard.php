<?php

require base_path('legacy/bootstrap.php');
require base_path('legacy/mahapanel/php/session.php');

$db = Abhayagiri\DB::getDB();

?>

<div id="breadcrumb-container">
    <ul class="breadcrumb container">
        <li></li>
    </ul>
</div>
<div class ="container-fluid dashboard">
    <div class="row-fluid">
        <div class="span12 item">
            <!-------------------- MESSAGES TABLE (part of span9) -------------------->
            <div class="messages">
                <h2>Recent Messages</h2>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Message</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?
                        $data = $db->_query('SELECT title,body,date,name,avatar FROM
            (SELECT messages.title,messages.body,messages.date,mahaguild.avatar,mahaguild.title AS name
            FROM messages LEFT JOIN mahaguild ON mahaguild.id = messages.user) sel ORDER by date DESC LIMIT 10');
                        foreach ($data as $row) {
                            $row['body'] = preg_replace("/<img([^>]+)\>/i", "<div style='text-align:center'><img style='max-width:500px; max-height:500px' $1 /></div>", $row['body']); 
                            ?>
                            <tr>
                                <td class="user" style="width:150px">
                                    <span class="pull-left" ><img class="avatar" src="/media/mahaguild/<?= $row['avatar'] ?>"></span>
                                    <span class="pull-left" style="padding-left:5px"><?= str_replace(' ', '<br>', $row['name']) ?></span>
                                </td>
                                <td>
                                    <div class="pull-right"><?= date("n/j/y", strtotime($row['date'])) ?></div>
                                    <div class="message-title"><?= $row['title'] ?></div><br>
                                    <div><?= $row['body'] ?></div>
                                </td>
                            </tr>
                        <? } ?>
                    </tbody>
                </table>
                <h4><a class ="pull-right" href="https://mahapanel.abhayagiri.org/messages"> View More Messages</a></h4>
            </div>
        </div>
</div>
    <!-------------------- TASKS TABLE (part of span9) -------------------->

<div class="row-fluid">
        <div class="span12 item">
            <div class="tasks">
                <h2>Tasks</h2>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Task</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?
                        $data = $db->_query('SELECT title,body,date,name,avatar FROM
            (SELECT tasks.title,tasks.date,tasks.body,mahaguild.avatar,mahaguild.title AS name
            FROM tasks LEFT JOIN mahaguild ON mahaguild.id = tasks.user) sel ORDER by date DESC LIMIT 10');
                        foreach ($data as $row) {
                            ?>
                            <tr>
                                <td style="width:150px">
                                    <span class="task"><?= $row['title'] ?></span>
                                </td>
                                <td>
                                    <div class="pull-right"><?= date("n/j/y", strtotime($row['date'])) ?></div><br>
                                    <div><?= $row['body'] ?></div>
                                <? } ?>
                    </tbody>
                </table>
                <h4><a class ="pull-right" href="https://mahapanel.abhayagiri.org/tasks"> View More Tasks</a></h4>
            </div>
        </div>
    </div>
    <!-------------------- LOG TABLE  -------------------->
    <br><div class="row-fluid">

        <div class="span12 item">
            <h2>Activity Log</h2>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Activity</th>
                    </tr>
                </thead>
                <tbody>
                    <?
                    $data = $db->_query('SELECT title,body,date,name,avatar FROM
            (SELECT logs.title,logs.date,logs.body,mahaguild.avatar,mahaguild.title AS name
            FROM logs LEFT JOIN mahaguild ON mahaguild.id = logs.user) sel ORDER by date DESC LIMIT 30');
                    foreach ($data as $row) {
                        ?>
                        <tr>
                            <td>
                                <div class="pull-right"><?= date("n/j/y", strtotime($row['date'])) ?></div><br>
                                <div><?= $row['name'] ?> <?= lcfirst($row['title']) ?></div>
                            </td>
                        </tr>
                    <? } ?>
                </tbody>
            </table>
            <h4><a class ="pull-right" href="https://mahapanel.abhayagiri.org/logs"> View More Logs</a></h4>
        </div>
    </div>
