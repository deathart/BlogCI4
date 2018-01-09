<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Class Migration_AddTableArticleView
 *
 * @package App\Database\Migrations
 */
class Migration_AddTableArticleView extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'post_id' => ['type' => 'INT', 'constraint' => 11],
            'ip' => ['type' => 'VARCHAR', 'constraint' => 250]
        ]);

        $this->forge->addPrimaryKey('id');

        $this->forge->createTable('article_view');
    }

    //--------------------------------------------------------------------

    public function down()
    {
        $this->forge->dropTable('article_view');
    }
}
