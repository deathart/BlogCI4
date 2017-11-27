<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Migration_AddTableMedias extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'slug' => ['type' => 'TEXT'],
            'name' => ['type' => 'VARCHAR', 'constraint' => 250]
        ]);

        $this->forge->addField('date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP');

        $this->forge->addKey('id', true);

        $this->forge->createTable('medias');
    }

    //--------------------------------------------------------------------

    public function down()
    {
        //
    }
}
