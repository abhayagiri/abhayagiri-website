<?php

$I = new FunctionalTester($scenario);
$I->wantTo('ensure that the feeds work');
$I->amOnPage('/rss/audio.php');
$I->see('Abhayagiri Audio');

$I->amOnPage('/rss/news.php');
$I->see('Abhayagiri News');

$I->amOnPage('/rss/reflections.php');
$I->see('Abhayagiri Reflections');
