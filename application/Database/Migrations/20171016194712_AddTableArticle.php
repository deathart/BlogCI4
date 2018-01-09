<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Class Migration_AddTableArticle
 *
 * @package App\Database\Migrations
 */
class Migration_AddTableArticle extends Migration
{
    public function up()
    {
        $this->forge->addField([
                'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
                'title' => ['type' => 'TEXT'],
                'content' => ['type' => 'LONGTEXT'],
                'author_created' => ['type' => 'INT', 'constraint' => 11],
        ]);

        $this->forge->addField('date_created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP');

        $this->forge->addField([
                'author_update' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
                'date_update' => ['type' => 'DATETIME', 'null' => true],
                'important' => ['type' => 'INT', 'constraint' => 11, 'default' => '0'],
                'link' => ['type' => 'TEXT'],
                'picture_one' => ['type' => 'TEXT'],
                'cat' => ['type' => 'CHAR', 'constraint' => 50],
                'published' => ['type' => 'INT', 'constraint' => 11, 'default' => '0'],
                'corriged' => ['type' => 'INT', 'constraint' => 11, 'default' => '0'],
                'keyword' => ['type' => 'TEXT'],
                'brouillon' => ['type' => 'INT', 'constraint' => 11, 'default' => '0']
        ]);

        $this->forge->addPrimaryKey('id');

        $this->forge->createTable('article');
    }

    //--------------------------------------------------------------------

    public function down()
    {
        $this->forge->dropTable('article');
    }
}
