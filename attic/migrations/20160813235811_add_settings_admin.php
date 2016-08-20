<?php

use Phinx\Migration\AbstractMigration;

class AddSettingsAdmin extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('pages');
        $table->insert([
            'id' => 102,
            'title' => 'Settings',
            'thai_title' => '',
            'url_title' => 'settings',
            'body' => '',
            'status' => 'Open',
            'icon' => 'icon-cog',
            'date' => date('Y-m-d H:i:s'),
            'class' => 'admin',
            'www' => 'no',
            'mahapanel' => 'yes',
            'meta_description' => 'Persistent settings',
            'user' => 0,
            'page_type' => 'Table',
            'display_type' => 'Table',
        ]);
        $table->saveData();

        $table = $this->table('columns');
        $table->insert([
            [
                'parent' => 102,
                'display_title' => 'Key',
                'title' => 'key_',
                'column_type' => 'text',
                'upload_directory' => '',
                'date' => date('Y-m-d H:i:s'),
                'display' => 'yes',
                'user' => 0,
                'status' => 'open',
            ],
            [
                'parent' => 102,
                'display_title' => 'Value',
                'title' => 'value',
                'column_type' => 'text',
                'upload_directory' => '',
                'date' => date('Y-m-d H:i:s'),
                'display' => 'yes',
                'user' => 0,
                'status' => 'open',
            ],
            [
                'parent' => 102,
                'display_title' => 'Date',
                'title' => 'date',
                'column_type' => 'date',
                'upload_directory' => '',
                'date' => date('Y-m-d H:i:s'),
                'display' => 'yes',
                'user' => 0,
                'status' => 'open',
            ],
            [
                'parent' => 102,
                'display_title' => 'User',
                'title' => 'user',
                'column_type' => 'user',
                'upload_directory' => '',
                'date' => date('Y-m-d H:i:s'),
                'display' => 'yes',
                'user' => 0,
                'status' => 'open',
            ],
        ]);
        $table->saveData();
    }

    public function down()
    {
        $this->execute("DELETE FROM `pages` WHERE `url_title` = 'settings'");
        $this->execute("DELETE FROM `columns` WHERE `parent` = '102'");
    }
}
