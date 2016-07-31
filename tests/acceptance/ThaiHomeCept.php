<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that the Thai homepage works');
$I->amOnPage('/th/home');
$I->see('ข่าว');
$I->see('ปฏิทิน');

$I->click('ต่อไป');
$I->wait(2);
$I->see('กลับสู่ด้านบน');
$I->see('อ่านต่อ');

$I->click('เส้นทาง');
$I->wait(1);
$I->see('วัดป่าอภัยคีรี');

?>
