<?php

use Illuminate\Database\Seeder;

class BooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('books')->insert([
            [
                'id' => 1,
                'language_id' => 1,
                'author_id' => 3,
                'author2_id' => null,
                'title' => 'What is Buddhism?',
                'slug' => 'what-is-buddhism',
                'description_en' => 'What is Buddhism offers a very clear and concise overview of Buddhism and its core teachings.',
                'description_th' => null,
                'weight' => '2 oz',
                'image_path' => 'images/books/whatisbuddhism.JPG',
                'pdf_path' => 'books/chandako_what_is_buddhism.pdf',
                'epub_path' => null,
                'mobi_path' => null,
                'request' => 1,
                'draft' => 0,
                'published_on' => '2015-12-01',
                'posted_at' => '2015-12-01 20:00:00',
            ],
            [
                'id' => 2,
                'language_id' => 2,
                'author_id' => 1,
                'author2_id' => null,
                'title' => 'สติปัฏฐาน ๔',
                'slug' => 'satipatthana',
                'description_en' => null,
                'description_th' => 'โดยเนื้อหาในหนังสือนั้น เป็นพระธรรมเทศนาและการถามตอบปัญหา เมื่อครั้งที่หลวงพ่อปสนฺโนได้เมตตารับกิจนิมนต์ในการอบรมการปฏิบัติธรรม ระหว่างวันที่ ๑๗ ถึง ๒๔ ธันวาคม ๒๕๔๙ณ ยุวพุทธิกสมาคมแห่งประเทศไทย',
                'weight' => '2 oz',
                'image_path' => 'images/books/LPP_Satipatthan4_2E_Ebook.jpg',
                'pdf_path' => 'books/LPP_Satipatthan4_2E_Ebook.pdf',
                'epub_path' => null,
                'mobi_path' => null,
                'request' => 0,
                'draft' => 0,
                'published_on' => '2018-01-01',
                'posted_at' => '2019-01-01 12:00:00',
            ],
        ]);
    }
}
