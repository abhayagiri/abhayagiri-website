<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that the Thai homepage works');
$I->amOnPage('/th/home');
$I->see('ข่าว');
$I->see('ปฏิทิน');

$I->click('ต่อไป');
$I->waitForElementVisible('#datatable_wrapper');
$I->see('กลับสู่ด้านบน');
$I->see('อ่านต่อ');

$I->click('เส้นทาง');
$I->waitForText('เส้นทางมาวัด', 10, 'legend');
$I->see('16201 Tomki Road');

?>
