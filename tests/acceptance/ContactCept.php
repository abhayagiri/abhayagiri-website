<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that the contact is available');

$I->amOnPage('/contact');
$I->see('Contact Form');

$I->fillField('#name', 'John Doe');
$I->fillField('#email', 'john@example.com');
$I->fillField('#message', 'great work!');
