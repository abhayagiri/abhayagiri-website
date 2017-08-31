<?php
$I = new ApiTester($scenario);
$I->wantTo('get talks via API');

// Simple Test

$I->sendGET('/talks', ['authorId' => 3, 'endDate' => 1236211400]);
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
$I->seeResponseIsJson();
$I->seeResponseContainsJson(['result' => [[
    'title' => 'Āṭānāṭiya Paritta',
    'mediaUrl' => '/media/audio/Chanting2009/C19AtanatiyaParitta.mp3',
    // TODO need much better test
]]]);

// authorSlug Test

$I->sendGET('/talks', ['authorId' => 27]);
$I->seeResponseContainsJson(['total' => 4]);

// categorySlug Test

$collectionId = App\Models\TalkType::where('slug', 'collection-zip-file')->first()->id;
$I->sendGET('/talks', ['typeId' => $collectionId]);
$I->seeResponseContainsJson(['total' => 15]);

// tagSlug Test
// TODO nothing is currently assigned

$I->sendGET('/talks', ['tagId' => 1]);
$I->seeResponseContainsJson(['total' => 0]);

// searchText Test

$I->sendGET('/talks', ['searchText' => "don't hold back"]);
$I->seeResponseContainsJson(['result' => [
    [ 'title' => 'Monastic Retreat 2013: Questions and Answers 5' ],
]]);

$I->sendGET('/talks', ['searchText' => "100% of the holy life"]);
$I->seeResponseContainsJson(['result' => [
    [ 'title' => 'Swept Along by the Steam of the Dhamma' ],
]]);

$I->sendGET('/talks', ['searchText' => "%%"]);
$I->seeResponseContainsJson([]);

$I->sendGET('/talks/6635');
$I->seeResponseContainsJson(['title' => 'See the World as a Royal Chariot']);

$I->sendGET('/talks/see-the-world-as-a-royal-chariot');
$I->seeResponseContainsJson(['id' => 6635]);
