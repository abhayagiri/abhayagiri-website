<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\ContactOption;

class AddRankAndConfirmationToContactOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contact_options', function (Blueprint $table) {
            $table->unsignedInteger('rank')->default(0)->after('published');
            $table->text('confirmation_en')->after('body_th');
            $table->text('confirmation_th')->nullable()->after('confirmation_en');
        });

        ContactOption::all()->each(function ($contactOption) {
            $contactOption->update(['confirmation_en' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque eget urna non metus scelerisque tincidunt. Morbi non mi ut tortor imperdiet porttitor. Praesent eget ipsum mauris. Sed a tellus pulvinar, pharetra enim eget, aliquet quam. Sed tincidunt ullamcorper turpis nec porttitor. Vivamus lacus odio, pulvinar in ornare sit amet, vestibulum in mauris. Phasellus sed ex metus.<br>Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Suspendisse eu auctor tortor. Donec et laoreet elit. Curabitur ornare quam nibh, eget lacinia neque condimentum et. Praesent at fringilla felis. Vivamus lobortis eros enim, vel porta metus tristique in. Cras eget tellus vehicula, congue dui sed, luctus mauris. Cras aliquet elit posuere libero pretium lobortis. Nunc imperdiet efficitur commodo. Vestibulum vulputate massa quis dui rutrum finibus. Mauris vitae luctus tortor. Nullam non est eleifend, efficitur eros quis, consequat mi.<br>Integer rhoncus pellentesque lacus at varius. Praesent ornare ante turpis, id efficitur ex mattis ac. Praesent elit tortor, porta rutrum augue at, bibendum laoreet magna. Duis tristique convallis odio, nec sollicitudin tellus condimentum at. Nulla at ante at purus eleifend vestibulum vitae non quam. Duis ac lacus at velit lacinia rutrum pulvinar sed sapien. Suspendisse nec massa sit amet nibh varius euismod quis eget massa. In eget libero quam.<br>']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contact_options', function (Blueprint $table) {
            $table->dropColumn('rank');
            $table->dropColumn('confirmation_en');
            $table->dropColumn('confirmation_th');
        });
    }
}
