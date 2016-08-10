<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that the gallery works');
$I->amOnPage('/gallery');
$I->wait(2);
$I->see('Reception Hall - Season 4 - Installment 8');

$I->click('Reception Hall - Season 4 - Installment 8');
$I->wait(2);
$I->see('Reception Hall - Season 4 - Installment 8');

?>
