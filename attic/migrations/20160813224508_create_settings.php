<?php

use Phinx\Migration\AbstractMigration;

class CreateSettings extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('settings');
        $table->addColumn('key_', 'string')
            ->addColumn('value', 'text')
            ->addColumn('user', 'integer')
            ->addColumn('date', 'datetime')
            ->addIndex(['key_'], ['unique' => true])
            ->create();
    }
}
