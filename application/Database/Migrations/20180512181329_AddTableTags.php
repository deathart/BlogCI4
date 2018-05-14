<?php

/*
 * BlogCI4 - Blog write with Codeigniter v4dev
 * @author Deathart <contact@deathart.fr>
 * @copyright Copyright (c) 2018 Deathart
 * @license https://opensource.org/licenses/MIT MIT License
 */

namespace App\Database\Migrations;

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
