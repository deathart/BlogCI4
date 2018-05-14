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
 * Class Migration_AddTableMedias
 *
 * @package App\Database\Migrations
 */
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

        $this->forge->addPrimaryKey('id');

        $this->forge->createTable('medias');
    }

    //--------------------------------------------------------------------

    public function down()
    {
        $this->forge->dropTable('medias');
    }
}
