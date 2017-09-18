<?php

$I = new FunctionalTester($scenario);
$I->wantTo('make sure admin/* works');

$email = str_random(40) . '@gmail.com';
$user = \App\User::create(['email' => $email]);

\Auth::login($user);

$I->amOnPage('/admin');
$I->seeCurrentUrlEquals('/admin/dashboard');
$I->see('Dashboard');

foreach (config('admin.models') as $model) {

    if ($model['name'] === 'talks' || array_get($model, 'super_admin')) {
        continue;
    }

    $path = array_get($model, 'path', $model['name']);
    $link = array_get($model, 'label',
        str_plural(title_case(str_replace('-', ' ', $model['name']))));

    $I->wantTo('make sure admin/' . $path . ' works with ' . $link);

    $I->click($link);
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
