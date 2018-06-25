<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class AddContactFormPreambleSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $add = function ($key, $value) {
            DB::table('settings')->insert([
                'key' => $key,
                'value' => $value,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        };

        $add('contact.preamble_en', '<p>Please use this page to contact the monastery. We do ask, however, that you read through the website thoroughly before asking any questions.</p><p>In addition, before contacting Abhayagiri, please take note of the following:</p><ul><li><p>Due to a high volume of emails, we are unable to respond to general questions about Buddhism whether these concern school projects, college research or are of general interest. Short, specific questions about our monastery and tradition, however, are welcome.</p></li><li><p>We are not able to provide spiritual or pastoral counseling via email or telephone.</p></li><li><p>If inquiring about an overnight stay please use this contact form to make your inquiry.</p></li><li><p>Please keep in mind the following notes concerning overnight stays: First time guests may stay for one week if coming from northern California, ten days if coming from southern California or out of state, or two weeks if coming from outside the country. We encourage first time guests to stay for at least three nights.</p></li><li><p>For April 1st - Dec. 24th: When writing to request an overnight stay, please include your age, gender, physical restrictions of any kind, where you are traveling from and briefly describe your experience with meditation, following precepts, previous stays in a monastery and information about any medication you take for physical or mental well being. If you are easily accessible by phone, it may also be helpful to provide us with your phone number.</p></li><li><p>For Dec. 25th - March 31st: We do not accept overnight guests during this time.</p></li></ul><p><u>Current guest accommodation availability:</u></p><p>The accommodations allocated for guests are full for during the remainder of this calendar year (2017).</p><p>The monastery will not be accepting overnight guests during the months of January, February, and March 2018. If interested in visiting in April 2018 or after, please contact the monastery on April 1st, 2018.</p><p>Please do not make reservations more than two months in advance of the date you wish to arrive.</p><p>After receiving your reservation request we will usually respond within five days.</p><p>Finally, we ask that you please do not request a reservation unless you have a sincere commitment to follow through.</p><p>Thank you for your consideration,<br/> The Abhayagiri Saṅgha</p>');
        $add('contact.preamble_th', null);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('settings')->where('key', 'like', 'contact.%')->delete();
    }
}