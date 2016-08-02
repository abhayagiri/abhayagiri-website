<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that Mahapanel redirects to Google');
$I->amOnPage('/mahapanel');
$I->see('Sign in with your Google Account');

?>
