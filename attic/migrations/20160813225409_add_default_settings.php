<?php

use Phinx\Migration\AbstractMigration;

class AddDefaultSettings extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('settings');
        $table->insert([
            'key_' => 'home.news.count',
            'value' => '2',
            'user' => 0,
            'date' => date('Y-m-d H:i:s'),
        ]);
        $table->saveData();
    }

    public function down()
    {
        $this->execute('DELETE FROM settings');
    }
}
