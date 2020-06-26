<?php

namespace Tests\Unit\Utilities;

use App\Utilities\AssociatedModels;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssociatedModelsTest extends TestCase
{
    use AssociatedModels;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->saveAssociatedChunkSize = static::$associatedChunkSize;
        static::$associatedChunkSize = 5;
        $this->c = $c = new class() extends Model {
            protected $table = 'redirects';
            protected $guarded = [];
            public function scopeCommonOrder($query) {
                return $query->orderBy('created_at', 'desc')
                             ->orderBy('id', 'desc');
            }
        };
        $c::truncate();
        for ($i = 1; $i <= 20; $i++) {
            $c::create([
                'id' => $i,
                'from' => 'a' . $i,
                'to' => '/',
                'created_at' => '2020-01-' . sprintf('%02d', $i) . ' 00:00:00',
            ]);
        }
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        static::$associatedChunkSize = $this->saveAssociatedChunkSize;
    }

    protected function assertAssociated($id, $afterId, $beforeId, $page)
    {
        $model = ($this->c)::find($id);
        $scope = ($this->c)::commonOrder();
        $result = static::getAssociatedModels($model, $scope, 3);
        $this->assertEquals($afterId, $result->after->id);
        $this->assertEquals($beforeId, $result->before->id);
        $this->assertEquals($page, $result->page);
    }

    public function testGetAssociatedModels()
    {
        $scope = ($this->c)::commonOrder();
        $this->assertEquals(20, ($this->c)::count());

        $result = static::getAssociatedModels(($this->c)::find(1), $scope, 3);
        $this->assertEquals(2, $result->after->id);
        $this->assertNull($result->before);
        $this->assertEquals(7, $result->page);

        $this->assertAssociated(2, 3, 1, 7);
        $this->assertAssociated(3, 4, 2, 6);
        $this->assertAssociated(4, 5, 3, 6);
        $this->assertAssociated(5, 6, 4, 6);
        $this->assertAssociated(6, 7, 5, 5);
        $this->assertAssociated(7, 8, 6, 5);
        $this->assertAssociated(8, 9, 7, 5);
        $this->assertAssociated(9, 10, 8, 4);
        $this->assertAssociated(10, 11, 9, 4);
        $this->assertAssociated(11, 12, 10, 4);
        $this->assertAssociated(12, 13, 11, 3);
        $this->assertAssociated(13, 14, 12, 3);
        $this->assertAssociated(14, 15, 13, 3);
        $this->assertAssociated(15, 16, 14, 2);
        $this->assertAssociated(16, 17, 15, 2);
        $this->assertAssociated(17, 18, 16, 2);
        $this->assertAssociated(18, 19, 17, 1);
        $this->assertAssociated(19, 20, 18, 1);

        $result = static::getAssociatedModels(($this->c)::find(20), $scope, 3);
        $this->assertNull($result->after);
        $this->assertEquals(19, $result->before->id);
        $this->assertEquals(1, $result->page);
    }
}
