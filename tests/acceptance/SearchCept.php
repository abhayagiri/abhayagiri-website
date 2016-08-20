<?php

$searchInputSelector = '.input-append input';
$searchWait = 2;

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that the English search works');
$I->amOnPage('/news');
$I->fillField($searchInputSelector, 'birthday cake');
$I->wait($searchWait);
$I->see('Born On a Four');

$I->wantTo('ensure that the Thai search does not work in the English site');
$I->amOnPage('/news');
$I->fillField($searchInputSelector, 'ในเดือนพฤษภาคมปีนี้');
$I->wait($searchWait);
$I->dontSee('พระอาจารย์ตั๋นจะมาเยี่ยมวัดอภัยคีรี');

$I->wantTo('ensure that the Thai search works');
$I->amOnPage('/th/news');
$I->fillField($searchInputSelector, 'ในเดือนพฤษภาคมปีนี้');
$I->wait($searchWait);
$I->see('พระอาจารย์ตั๋นจะมาเยี่ยมวัดอภัยคีรี');
