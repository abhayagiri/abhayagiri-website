<?php

$I = new FunctionalTester($scenario);
$I->wantTo('ensure that the danalist works');

$I->amOnPage('/support/dana-wish-list');
$I->see('no bottled water');

$I->amOnPage('/th/support/dana-wish-list-thai');
$I->see('no bottled water');
