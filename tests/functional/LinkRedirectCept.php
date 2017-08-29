<?php

$I = new FunctionalTester($scenario);
$I->wantTo('to ensure old audio links work');

$I->amOnPage('/audio/metta-and-upekkha');
$I->seeCurrentUrlEquals('/new/talks/6235-metta-and-upekkha');

$I->amOnPage('/audio/xyz-nowhere-xyz');
$I->seeCurrentUrlEquals('/new/talks');
