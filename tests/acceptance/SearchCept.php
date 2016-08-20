<?php

$searchInputSelector = '.input-append input';
$searchWait = 2;

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that search works');
$I->amOnPage('/news');
$I->fillField($searchInputSelector, 'birthday cake');
$I->waitForText('Born On a Four');
$I->see('Rik Center was at the monastery');

$I->fillField($searchInputSelector, 'ในเดือนพฤษภาคมปีนี้');
$I->waitForText('No matching records found');

$I->amOnPage('/th/news');
$I->fillField($searchInputSelector, 'ในเดือนพฤษภาคมปีนี้');
$I->waitForText('พระอาจารย์ตั๋นจะมาเยี่ยมวัดอภัยคีรี');
$I->see('ในเดือนพฤษภาคมปีนี้');
