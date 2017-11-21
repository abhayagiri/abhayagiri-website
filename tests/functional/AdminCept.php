<?php

// TODO Yuck...this is getting too complicated...

$I = new FunctionalTester($scenario);
$I->wantTo('make sure admin/* works');

$email = str_random(40) . '@gmail.com';
$user = \App\User::create(['email' => $email]);

\Auth::login($user);

$I->amOnPage('/admin');
$I->seeCurrentUrlEquals('/admin/dashboard');
$I->see('Dashboard');

foreach (config('admin.models') as $model) {

    if (array_get($model, 'super_admin')) {
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

    $I->sendAjaxPostRequest('/admin/' . $path . '/search');
    $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
    $recordsTotal = $I->grabDataFromResponseByJsonPath('$.recordsTotal')[0];
    $I->assertGreaterThan(0, $recordsTotal);

    $lastColumn = $I->grabDataFromResponseByJsonPath('$.data[0][-1]')[0];
    if (!preg_match('_<a.+?href=.+?(/admin/.+?/edit)_', $lastColumn, $matches)) {
        assertTrue(false);
    }

    $I->amOnPage($matches[1]);
    $I->seeCurrentUrlMatches('_^/admin/' . $path . '/\d+/edit$_');
    $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
    $I->see('Back to all');

    $I->click('Save and back');
    $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
    $I->seeCurrentUrlEquals('/admin/' . $path);

    $I->sendAjaxPostRequest('/admin/' . $path . '/search', [ 'search' => [
        'value' => 'some search term',
    ]]);
    $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
}

$user->forceDelete();
