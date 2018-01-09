<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Class Migration_AddTableCat
 *
 * @package App\Database\Migrations
 */
class Migration_AddTableCat extends Migration
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

        $this->forge->createTable('cat');
    }

    //--------------------------------------------------------------------

    public function down()
    {
        $this->forge->dropTable('cat');
    }
}
