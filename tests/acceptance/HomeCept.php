<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that home works');
$I->amOnPage('/home');
$I->see('Abhayagiri Buddhist Monastery');
$I->see('More News');
$I->see('View Full Calendar');

?>
