<?php

/*
 * BlogCI4 - Blog write with Codeigniter v4dev
 * @author Deathart <contact@deathart.fr>
 * @copyright Copyright (c) 2018 Deathart
 * @license https://opensource.org/licenses/MIT MIT License
 */

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Class Migration_AddTableCategories
 *
 * @package App\Database\Migrations
 */
class Migration_AddTableCategories extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'title' => ['type' => 'VARCHAR', 'constraint' => 250],
            'description' => ['type' => 'TEXT'],
            'slug' => ['type' => 'VARCHAR', 'constraint' => 250],
            'icon' => ['type' => 'VARCHAR', 'constraint' => 250],
            'parent' => ['type' => 'INT', 'constraint' => 11, 'default' => '0'],
        ]);

        $this->forge->addPrimaryKey('id');

        $this->forge->createTable('categories');
    }

    //--------------------------------------------------------------------

    public function down()
    {
        $this->forge->dropTable('categories');
    }
}
