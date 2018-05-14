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
 * Class Migration_AddTableUsers
 *
 * @package App\Database\Migrations
 */
class Migration_AddTableUsers extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'username' => ['type' => 'VARCHAR', 'constraint' => 250],
            'password' => ['type' => 'VARCHAR', 'constraint' => 250],
            'email' => ['type' => 'TEXT'],
            'avatar' => ['type' => 'VARCHAR', 'constraint' => 250, 'default' => 'user.png']
        ]);

        $this->forge->addPrimaryKey('id');

        $this->forge->createTable('users');
    }

    //--------------------------------------------------------------------

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
