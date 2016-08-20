<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that the contact form works');
$I->resetMail();
$I->amOnPage('/contact');
$I->see('Contact Form');

$I->fillField('#name', 'John Doe');
$I->fillField('#email', 'john@example.com');
$I->fillField('#message', 'great work!');
$I->click('Submit');
$I->waitForText('Your message has been sent successfully.');

$I->seeInLastMailSubject('Message from John Doe <john@example.com>');
$I->seeInLastMailFrom('devmonk@abhayagiri.org', 'Website Contact Form');
// TODO $I->seeInLastMailTo('devmonk@abhayagiri.org', null);

$I->seeInLastMailBody('great work!');

$I->resetMail();
