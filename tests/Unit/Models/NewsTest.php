<?php

namespace Tests\Unit;

use App\Models\News;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class NewsTest extends TestCase
{
    use DatabaseTransactions;

    public function testScopePostOrdered()
    {
        DB::table('news')->delete();
        $news1 = factory(News::class)->create(['id' => 1, 'rank' => null,
                                               'posted_at' => '2019-01-01']);
        $news2 = factory(News::class)->create(['id' => 2, 'rank' => null,
                                               'posted_at' => '2019-07-01']);
        $news3 = factory(News::class)->create(['id' => 3, 'rank' => null,
                                               'posted_at' => '2019-05-01']);
        $this->assertEquals([2, 3, 1], News::postOrdered()->pluck('id')->toArray());
        $news1->rank = 1;
        $news1->save();
        $this->assertEquals([1, 2, 3], News::postOrdered()->pluck('id')->toArray());
        $news3->rank = 1;
        $news3->save();
        $this->assertEquals([3, 1, 2], News::postOrdered()->pluck('id')->toArray());
        $news3->rank = 2;
        $news3->save();
        $this->assertEquals([1, 3, 2], News::postOrdered()->pluck('id')->toArray());
    }
}
