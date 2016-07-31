<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that the thai home works');
$I->amOnPage('/th/home');
$I->see('ต่อไป');
$I->see('ปฏิทิน');

?>
