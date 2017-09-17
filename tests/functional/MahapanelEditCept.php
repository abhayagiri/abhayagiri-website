<?php

$I = new FunctionalTester($scenario);
$I->wantTo('to edit on mahapanel');

$I->amOnPage('/mahapanel_bypass?email=root@localhost');
$I->seeCurrentUrlEquals('/mahapanel');

$I->sendAjaxPostRequest('/mahapanel/php/ajax.php', [
    'action' => 'insert',
    'table' => 'construction',
    'columns' => [
        'title' => 'test123',
        'url_title' => 'test123',
        'date' => '2016-12-31 00:00:00',
        'body' => '123',
        'User' => '0',
        'status' => 'Open',
    ],
]);

$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);

DB::table('construction')->where('title', '=', 'test123')->delete();
