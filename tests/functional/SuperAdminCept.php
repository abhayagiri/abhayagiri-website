<?php

$I = new FunctionalTester($scenario);
$I->wantTo('make sure the super admin works');

$I->amASuperAdmin();
$I->amOnPage('/admin');
$I->see('Dashboard');

$I->click('Users');
$I->seeCurrentUrlEquals('/admin/users');
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
$I->see('Add user');
