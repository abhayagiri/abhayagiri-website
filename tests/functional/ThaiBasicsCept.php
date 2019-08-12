<?php

$I = new FunctionalTester($scenario);
$I->wantTo('make sure the basic functionality works on Thai pages');

$I->amOnPage('/th/home');
$I->see('Abhayagiri');

$I->amOnPage('/th/news');
$I->see('ข่าว');

$I->amOnPage('/th/calendar');
$I->see('ปฏิทิน');

$I->amOnPage('/th/about');
$I->see('เป้าหมาย');

$I->amOnPage('/th/community');
$I->see('หมู่คณะ');
$I->see('หลวงพ่อ ปสนฺโน');

$I->amOnPage('/th/support');
$I->see('อนุเคราะห์');
$I->see('ลักษณะความเป็นอยู่');

$I->amOnPage('/th/support/dana-wish-list-thai');
$I->see('อนุเคราะห์');
$I->see('รายการสิ่งของที่จำเป็น');
$I->see('Please No Bottled Water');

$I->amOnPage('/th/books');
$I->see('หนังสือ');

$I->amOnPage('/th/reflections');
$I->see('แง่ธรรม');

$I->amOnPage('/th/visiting');
$I->see('เยี่ยม');
