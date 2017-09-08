<?php

$I = new FunctionalTester($scenario);
$I->wantTo('ensure that the books work');

$I->amOnPage('/books');
$I->see('Books');

$I->sendAjaxPostRequest('/books/cart/608', []);
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
