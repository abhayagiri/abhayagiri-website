<?php

$I = new FunctionalTester($scenario);
$I->wantTo('make sure the admin works');

$email = str_random(40) . '@gmail.com';
$user = \App\User::create(['email' => $email]);

\Auth::login($user);

$I->amOnPage('/admin');
$I->seeCurrentUrlEquals('/admin/dashboard');
$I->see('Dashboard');

$I->click('Authors');
$I->seeCurrentUrlEquals('/admin/authors');
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
$I->see('Ajahn');

$I->click('Books');
$I->seeCurrentUrlEquals('/admin/books');
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
$I->see('Add book');

$I->click('Languages');
$I->seeCurrentUrlEquals('/admin/languages');
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
$I->see('English and Thai');

$I->click('Playlists');
$I->seeCurrentUrlEquals('/admin/playlists');
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
$I->see('Add playlist');

$I->click('Settings');
$I->seeCurrentUrlEquals('/admin/setting');
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
$I->see('Home news article count');

$I->click('Subject Groups');
$I->seeCurrentUrlEquals('/admin/subject-groups');
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
$I->see('Add subject group');

$I->click('Subjects');
$I->seeCurrentUrlEquals('/admin/subjects');
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
$I->see('Add subject');

$I->click('Tags');
$I->seeCurrentUrlEquals('/admin/tags');
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
$I->see('Add tag');

$I->click('Talk Types');
$I->seeCurrentUrlEquals('/admin/talk-types');
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
$I->see('Dhamma Talks');

$I->click('Talks');
$I->seeCurrentUrlEquals('/admin/talks');
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
$I->see('Add talk');

$I->sendAjaxPostRequest('/admin/talks/search', [ 'search' => [
    'value' => 'right intention',
]]);
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
$I->see('right intention');

$I->amOnPage('/admin');
$I->dontSee('Users');
$I->amOnPage('/admin/users');
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::FORBIDDEN);

$user->forceDelete();
