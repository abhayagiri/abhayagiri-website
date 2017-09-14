<?php

$I = new FunctionalTester($scenario);
$I->wantTo('to ensure old links work');

$I->amOnPage('/audio/metta-and-upekkha');
$I->seeCurrentUrlEquals('/new/talks/6235-metta-and-upekkha');

$I->amOnPage('/th/audio/what-are-you-doing%3F');
$I->seeCurrentUrlEquals('/new/th/talks/6591-what-are-you-doing');

$I->amOnPage('/audio/xyz-nowhere-xyz');
$I->seeCurrentUrlEquals('/new/talks');

$I->amOnPage('/th/audio/xyz-nowhere-xyz');
$I->seeCurrentUrlEquals('/new/th/talks');

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
