<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that the contact form works');
$I->amOnPage('/contact');
$I->see('Contact Form');

$I->fillField('#name', 'John Doe');
$I->fillField('#email', 'john@example.com');
$I->fillField('#message', 'great work!');
$I->click('Submit');
$I->wait(2);
$I->see('Your message has been sent successfully.');

// TODO see if mail shows up
