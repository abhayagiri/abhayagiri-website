<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that the residents page works');

$I->amOnPage('/home');
$I->waitForPageToLoad();

$I->click('#btn-menu');
$I->click('#btn-community');
$I->waitForPageToLoad();
$I->see('Community');
$I->see('Residents');
$I->see('Ajahn Pasanno (Abbot)');

$I->click('Ajahn Pasanno');
$I->waitForPageToLoad();
$I->see('Ajahn Pasanno took ordination in Thailand');

$I->amOnPage('/th/home');
$I->waitForPageToLoad();

$I->click('#btn-menu');
$I->click('#btn-community');
$I->waitForPageToLoad();
$I->see('หมู่คณะ');
$I->see('พระภิกษุสงฆ์ นักบวชและอุบาสิกา');
$I->see('หลวงพ่อ ปสนฺโน (เจ้าอาวาส)');

$I->click('หลวงพ่อ ปสนฺโน');
$I->waitForPageToLoad();
$I->see('หลวงพ่อปสนฺโนได้รับการอุปสมบทเป็นพระภิกษุสงฆ์ในปี');
