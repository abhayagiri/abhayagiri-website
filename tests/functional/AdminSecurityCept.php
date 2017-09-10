<?php

$I = new FunctionalTester($scenario);
$I->wantTo('make sure the public cant access admin');

$I->amOnPage('/admin');
$I->seeCurrentUrlEquals('/admin/login');

$I->amOnPage('/admin/elfinder');
$I->seeCurrentUrlEquals('/admin/login');

$I->amOnPage('/admin/users');
$I->seeCurrentUrlEquals('/admin/login');
