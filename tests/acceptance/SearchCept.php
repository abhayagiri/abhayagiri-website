<?php

$searchInputSelector = '.input-append input';

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that search works');

$I->amOnPage('/news');
$I->waitForPageToLoad();

$I->fillField($searchInputSelector, 'birthday cake');
$I->waitForPageToLoad();
$I->waitForText('Born On a Four');
$I->see('Rik Center was at the monastery');

$I->fillField($searchInputSelector, 'ในเดือนพฤษภาคมปีนี้');
$I->waitForPageToLoad();
$I->waitForText('No matching records found');

$I->amOnPage('/audio');
$I->waitForPageToLoad();

$I->click('#filter-category button');
$I->click('#filter-category li:nth-child(5) a'); // Collection
$I->waitForPageToLoad();
$I->waitForText('20th Anniversary Compilation');

$I->amOnPage('/th/news');
$I->waitForPageToLoad();

$I->fillField($searchInputSelector, 'ในเดือนพฤษภาคมปีนี้');
$I->waitForPageToLoad();
$I->waitForText('พระอาจารย์ตั๋นจะมาเยี่ยมวัดอภัยคีรี');
$I->see('ในเดือนพฤษภาคมปีนี้');
