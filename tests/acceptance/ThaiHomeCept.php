<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that the Thai homepage works');

$I->amOnPage('/th/home');
$I->waitForPageToLoad();
$I->see('ข่าว');
$I->see('ปฏิทิน');

$I->click('ต่อไป');
$I->waitForPageToLoad();
$I->see('กลับสู่ด้านบน');
$I->see('อ่านต่อ');

$I->click('เส้นทาง');
$I->waitForPageToLoad();
$I->see('เส้นทางมาวัด', 'legend');
$I->see('16201 Tomki Road');
