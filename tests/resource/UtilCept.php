<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that version page work');
$I->amOnPage('/version.php');
$I->see('-');

?>
