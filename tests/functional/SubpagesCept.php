<?php

$I = new FunctionalTester($scenario);
$I->wantTo('make sure the subpages work');

$pages = [
    'about/a-typical-day',
    'about/ajahn-chah',
    'about/origins-of-abhayagiri',
    'about/purpose',
    'about/thai-forest-tradition',
    'about/western-sangha',
    'community/associated-lay-groups',
    'community/associated-monasteries',
    'community/monastic-training-for-women',
    'community/pacific-hermitage',
    'community/residents',
    'community/residents/pasanno',
    'community/upasika-program',
    'support/dana-wish-list',
    'support/donations',
    'support/ethos',
    'support/food-and-supplies',
    'support/volunteer',
    'visiting/daily-schedule',
    'visiting/day-visits',
    'visiting/directions',
    'visiting/eight-precepts',
    'visiting/faq',
    'visiting/monastery-etiquette',
    'visiting/overnight-stays',
    'visiting/transportation',
];

foreach ($pages as $page) {
    $I->amOnPage('/' . $page);
    $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);

    $I->amOnPage('/th/' . $page);
    $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
}
