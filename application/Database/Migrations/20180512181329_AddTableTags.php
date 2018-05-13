<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Migration_AddTableTags extends Migration
{
	public function up()
	{
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'title' => ['type' => 'TEXT'],
            'slug' => ['type' => 'TEXT']
        ]);

        $this->forge->addPrimaryKey('id');

        $this->forge->createTable('tags');
	}

	//--------------------------------------------------------------------

	public function down()
	{
		//
	}
}
