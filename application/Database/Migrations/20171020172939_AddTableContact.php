<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Migration_AddTableContact extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'name' => ['type' => 'TEXT'],
            'email' => ['type' => 'TEXT'],
            'sujet' => ['type' => 'TEXT'],
            'message' => ['type' => 'LONGTEXT'],
            'ip' => ['type' => 'VARCHAR', 'constraint' => 250],
            'etat' => ['type' => 'INT', 'constraint' => 11, 'default' => '0']
        ]);

        $this->forge->addField('date_created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP');

        $this->forge->addPrimaryKey('id');

        $this->forge->createTable('contact');
    }

    //--------------------------------------------------------------------

    public function down()
    {
        $this->forge->dropTable('contact');
    }
}
