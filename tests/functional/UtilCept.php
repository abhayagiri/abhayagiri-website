<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that version page work');
$I->amOnPage('/version');
$I->see('array');

?>
