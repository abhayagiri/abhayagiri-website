<?php

$I = new FunctionalTester($scenario);
$I->wantTo('make admin/* works');

$email = str_random(40) . '@gmail.com';
$user = \App\User::create(['email' => $email]);

\Auth::login($user);

$I->amOnPage('/admin');
$I->seeCurrentUrlEquals('/admin/dashboard');
$I->see('Dashboard');

$models = [
    ['Authors', 'authors'],
    ['Blobs', 'blobs'],
    ['Books', 'books'],
    ['Languages', 'languages'],
    ['News', 'news'],
    ['Playlists', 'playlists'],
    ['Settings', 'setting'],
    ['Subject Groups', 'subject-groups'],
    ['Subjects', 'subjects'],
    ['Tags', 'tags'],
    ['Talk Types', 'talk-types'],
];

foreach ($models as list($link, $path)) {

    $I->wantTo('make sure admin/' . $path .' works');

    $I->click(str_plural($link));
    $I->seeCurrentUrlEquals('/admin/' . $path);
    $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
    $I->see('All ' . $link . ' in the database');

    $I->click('Edit');
    $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
    $I->seeCurrentUrlMatches('_^/admin/' . $path . '/\d+/edit$_');
    $I->see('Back to all');

    $I->click('Save and back');
    $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
    $I->seeCurrentUrlEquals('/admin/' . $path);

}

// Talks use ajax table so we do something different.

$I->wantTo('make admin/talks works');

$I->click('Talks');
$I->seeCurrentUrlEquals('/admin/talks');
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
$I->see('All talks in the database');

$talk = \App\Models\Talk::first();

$I->amOnPage('/admin/talks/' . $talk->id . '/edit');
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
$I->seeCurrentUrlMatches('_^/admin/talks/\d+/edit$_');
$I->see('Back to all');

$I->click('Save and back');
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
$I->seeCurrentUrlEquals('/admin/talks');

$I->sendAjaxPostRequest('/admin/talks/search', [ 'search' => [
    'value' => 'right intention',
]]);
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
$I->see('right intention');

$I->wantTo('make sure admin/* works');

$user->forceDelete();
