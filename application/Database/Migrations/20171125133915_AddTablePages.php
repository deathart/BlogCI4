<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Class Migration_AddTablePages
 *
 * @package App\Database\Migrations
 */
class Migration_AddTablePages extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'title' => ['type' => 'TEXT'],
            'content' => ['type' => 'LONGTEXT'],
            'slug' => ['type' => 'TEXT'],
            'active' => ['type' => 'INT', 'constraint' => 11, 'default' => 1],
        ]);

        $this->forge->addPrimaryKey('id');

        $this->forge->createTable('pages');
    }

    //--------------------------------------------------------------------

    public function down()
    {
        $this->forge->dropTable('pages');
    }
}
