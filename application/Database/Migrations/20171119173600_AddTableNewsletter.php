<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Migration_AddTableNewsletter extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'email' => ['type' => 'TEXT'],
            'ip' => ['type' => 'VARCHAR', 'constraint' => 250]
        ]);

        $this->forge->addField('date_created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP');

        $this->forge->addKey('id', true);

        $this->forge->createTable('newsletter');
    }

    //--------------------------------------------------------------------

    public function down()
    {
        $this->forge->dropTable('newsletter');
    }
}
