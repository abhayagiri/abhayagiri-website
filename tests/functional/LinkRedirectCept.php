<?php

$I = new FunctionalTester($scenario);
$I->wantTo('to ensure old links work and redirect');

$I->amOnPage('/audio/metta-and-upekkha');
$I->seeCurrentUrlEquals('/new/talks/6235-metta-and-upekkha');

$I->amOnPage('/th/audio/what-are-you-doing%3F');
$I->seeCurrentUrlEquals('/new/th/talks/6591-what-are-you-doing');

$I->amOnPage('/audio/xyz-nowhere-xyz');
$I->seeCurrentUrlEquals('/new/talks');

$I->amOnPage('/th/audio/xyz-nowhere-xyz');
$I->seeCurrentUrlEquals('/new/th/talks');

$I->amOnPage('/books/abhayagiri-chanting-book');
$I->seeCurrentUrlEquals('/books/424-abhayagiri-chanting-book');

$I->amOnPage('/news/abhayagiris-20th-anniversary');
$I->seeCurrentUrlEquals('/news/148-abhayagiris-20th-anniversary-on-saturday-june-4th');

$I->amOnPage('/th/reflections/on-teaching-him-a-lesson');
$I->seeCurrentUrlEquals('/th/reflections/206-on-teaching-him-a-lesson');

$I->amOnPage('/about/thai-forest-tradition-thai');
$I->seeCurrentUrlEquals('/th/about/thai-forest-tradition');

$redirect = \App\Models\Redirect::create([
    'from' => 'abc',
    'to' => json_encode([
        'type' => 'Basic',
        'url' => '/books',
    ]),
]);

$I->amOnPage('/abc');
$I->seeCurrentUrlEquals('/books');

$redirect->delete();
