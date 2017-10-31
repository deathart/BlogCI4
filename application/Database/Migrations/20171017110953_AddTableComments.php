<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Migration_AddTableComments extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'post_id' => ['type' => 'INT', 'constraint' => 11],
            'author_name' => ['type' => 'VARCHAR', 'constraint' => 250],
            'author_email' => ['type' => 'TEXT'],
            'author_ip' => ['type' => 'VARCHAR', 'constraint' => 250]
        ]);

        $this->forge->addField("created_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP");

        $this->forge->addField([
            'verified' => ['type' => 'INT', 'constraint' => 11, 'default' => "0"],
            'content' => ['type' => 'LONGTEXT'],
        ]);

        $this->forge->addKey('id', true);

        $this->forge->createTable('comments');
    }

    //--------------------------------------------------------------------

    public function down()
    {
        //
    }
}
