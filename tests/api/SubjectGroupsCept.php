<?php
$I = new ApiTester($scenario);
$I->wantTo('get subject groups via API');

$I->sendGET('/subject-groups');
$I->seeResponseContainsJson([
    [ 'titleEn' => 'Suffering and Hindrances' ],
    [ 'titleEn' => 'Spiritual Strengths and Factors of Awakening' ],
]);

$I->sendGET('/subject-groups/2');
$I->seeResponseContainsJson(
    [ 'titleEn' => 'Spiritual Strengths and Factors of Awakening' ]
);
