<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

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

        $this->forge->addKey('id', true);

        $this->forge->createTable('users');
    }

    //--------------------------------------------------------------------

    public function down()
    {
        //
    }
}
