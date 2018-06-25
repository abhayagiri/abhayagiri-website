<?php

$I = new FunctionalTester($scenario);
$I->wantTo('ensure that the books work');

$I->amOnPage('/books');
$I->see('Books');

$I->sendAjaxPostRequest('/books/cart/608', []);
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);

$I->amOnPage('/books/request');
$I->see('Not for Resale');

$I->amOnPage('/th/books/request');
$I->see('ไม่ใช่เพื่อขายต่อ');
