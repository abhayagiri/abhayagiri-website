<?php

namespace Tests\Unit\YouTubeSync;

use App\YouTubeSync\ServiceWrapper;
use ArrayIterator;
use Google_Collection;
use Google_Model;
use Mockery;
use stdClass;
use Tests\TestCase;

class ServiceWrapperTest extends TestCase
{
    public function testBasics()
    {
        $serviceMock = $this->mockYouTubeService();
        $clientMock = Mockery::mock('Google_Client');
        $serviceMock->shouldReceive('getClient')->once()->andReturn($clientMock);
        $service = new ServiceWrapper($serviceMock);
        $this->assertEquals($clientMock, $service->getClient());
        $this->assertEquals($serviceMock, $service->getService());
        $this->assertEquals(50, $service->getPageSize());
        $service->setPageSize(10);
        $this->assertEquals(10, $service->getPageSize());
    }

    public function testConvertChannelPlaylistId()
    {
        $this->assertEquals('UUI3Fm3DjOkedz-fI_I0rGFQ',
            ServiceWrapper::convertChannelPlaylistId('UCI3Fm3DjOkedz-fI_I0rGFQ'));
    }

    public function testGetChannelVideos()
    {
        $playlistResponse1 = $this->mockCollection([
            $this->mockPlaylistVideo('abc'),
            $this->mockPlaylistVideo('123'),
            $this->mockPlaylistVideo('xyz'),
        ], 'next_1');
        $playlistResponse2 = $this->mockCollection([
            $this->mockPlaylistVideo('qqq'),
            $this->mockPlaylistVideo('ttt'),
        ]);
        $videoResponse1 = $this->mockCollection([
            $this->mockVideo('abc'),
            $this->mockVideo('123'),
            $this->mockVideo('xyz'),
        ]);
        $videoResponse2 = $this->mockCollection([
            $this->mockVideo('qqq'),
            $this->mockVideo('ttt'),
        ]);

        $mock = $this->mockYouTubeService();
        $mock->playlistItems->shouldReceive('listPlaylistItems')->once()
             ->with('snippet', ['playlistId' => 'UUp1', 'maxResults' => 3])
             ->andReturn($playlistResponse1);
        $mock->playlistItems->shouldReceive('listPlaylistItems')->once()
             ->with('snippet', ['playlistId' => 'UUp1', 'maxResults' => 3,
                                'pageToken' => 'next_1'])
             ->andReturn($playlistResponse2);
        $mock->videos->shouldReceive('listVideos')->once()
             ->with('status', ['id' => 'abc,123,xyz', 'maxResults' => 3])
             ->andReturn($videoResponse1);
        $mock->videos->shouldReceive('listVideos')->once()
             ->with('status', ['id' => 'qqq,ttt', 'maxResults' => 3])
             ->andReturn($videoResponse2);

        $service = new ServiceWrapper($mock);
        $service->setPageSize(3);
        $iterator = $service->getChannelVideos('status', 'UCp1');
        $videos = collect($iterator);
        $this->assertEquals(5, count($videos));
        $this->assertEquals('123', $videos[1]->id);
        $this->assertEquals('ttt', $videos[4]->id);
    }

    public function testGetPlaylists()
    {
        $mock = $this->mockYouTubeService();
        $mock->playlists->shouldReceive('listPlaylists')->once()
             ->with('status', ['channelId' => 'foo', 'maxResults' => 50])
             ->andReturn($this->mockCollection(range('1', '3')));
        $service = new ServiceWrapper($mock);
        $iterator = $service->getPlaylists('status', 'foo');
        $this->assertEquals(range('1', '3'), collect($iterator)->toArray());
    }

    public function testGetPlaylistVideos()
    {
        $mock = $this->mockYouTubeService();
        $mock->playlistItems->shouldReceive('listPlaylistItems')->once()
             ->with('id', ['playlistId' => '123', 'maxResults' => 50])
             ->andReturn($this->mockCollection(['A', 'B']));
        $service = new ServiceWrapper($mock);
        $iterator = $service->getPlaylistVideos('id', '123');
        $this->assertEquals(['A', 'B'], collect($iterator)->toArray());
    }

    public function testGetPlaylistVideosPaged()
    {
        $mock = $this->mockYouTubeService();
        $mock->playlistItems->shouldReceive('listPlaylistItems')->once()
             ->with('id', ['playlistId' => 'z', 'maxResults' => 10])
             ->andReturn($this->mockCollection(range(1, 10), '!'));
        $mock->playlistItems->shouldReceive('listPlaylistItems')->once()
             ->with('id', ['playlistId' => 'z', 'maxResults' => 10,
                           'pageToken' => '!'])
             ->andReturn($this->mockCollection(range(11, 15)));
        $service = new ServiceWrapper($mock);
        $service->setPageSize(10);
        $iterator = $service->getPlaylistVideos('id', 'z');
        $this->assertEquals(range(1, 15), collect($iterator)->toArray());
    }

    public function testGetVideos()
    {
        $mock = $this->mockYouTubeService();
        $mock->videos->shouldReceive('listVideos')->once()
             ->with('snippet', ['id' => 'abc', 'maxResults' => 50])
             ->andReturn($this->mockCollection(['def']));
        $service = new ServiceWrapper($mock);
        $iterator = $service->getVideos('snippet', ['abc']);
        $this->assertEquals(['def'], collect($iterator)->toArray());
    }

    protected function mockYouTubeService()
    {
        $mock = Mockery::mock('Google_Service_YouTube');
        $mock->playlistItems = Mockery::mock('Google_Service_YouTube_Resource_PlaylistItems');
        $mock->playlists = Mockery::mock('Google_Service_YouTube_Resource_Playlists');
        $mock->videos = Mockery::mock('Google_Service_YouTube_Resource_Videos');
        return $mock;
    }

    protected function mockCollection(array $items,
                                      string $nextPageToken = null)
                                      : Google_Collection
    {
        $collection = new Google_Collection;
        $collection->items = $items;
        if ($nextPageToken) {
            $collection->nextPageToken = $nextPageToken;
        }
        return $collection;
    }

    protected function mockPlaylistVideo(string $videoId) : Google_Model
    {
        $model = $this->mockVideo($videoId);
        $model->id = 'PLAYLISTID ' . $videoId;
        return $model;
    }

    protected function mockVideo(string $videoId) : Google_Model
    {
        $model = new Google_Model;
        $model->id = $videoId;
        $model->snippet = new stdClass;
        $model->snippet->title = 'TITLE ' . $videoId;
        $model->snippet->resourceId = new stdClass;
        $model->snippet->resourceId->videoId = $videoId;
        return $model;
    }
}
