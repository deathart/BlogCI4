<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

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
            'parent' => ['type' => 'INT', 'constraint' => 11, 'default' => "0"],
        ]);

        $this->forge->addKey('id', true);

        $this->forge->createTable('cat');

        $this->db->query("INSERT INTO `cat` (`id`, `title`, `description`, `slug`, `icon`, `parent`) VALUES (1, 'PHP', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas a posuere mauris, vel cursus magna. In scelerisque pharetra felis, nec faucibus ligula tincidunt tempor. Cras et sem nec erat laoreet lobortis. Donec id lorem quis orci interdum vehicula. Vivamus pretium in risus ac vestibulum.', 'php', 'http://www.leifzimmerman.com/images/icons/icon_large_php.png', 0)");
        $this->db->query("INSERT INTO `cat` (`id`, `title`, `description`, `slug`, `icon`, `parent`) VALUES (2, 'HTML/CSS', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas a posuere mauris, vel cursus magna. In scelerisque pharetra felis, nec faucibus ligula tincidunt tempor. Cras et sem nec erat laoreet lobortis. Donec id lorem quis orci interdum vehicula. Vivamus pretium in risus ac vestibulum.', 'html-css', 'http://solidfoundationwebdev.com/system/categories/images/000/000/004/original/html-css.png', 0)");
        $this->db->query("INSERT INTO `cat` (`id`, `title`, `description`, `slug`, `icon`, `parent`) VALUES (3, 'Node JS', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas a posuere mauris, vel cursus magna. In scelerisque pharetra felis, nec faucibus ligula tincidunt tempor. Cras et sem nec erat laoreet lobortis. Donec id lorem quis orci interdum vehicula. Vivamus pretium in risus ac vestibulum.', 'node-js', 'http://glenneggleton.com/files/2016-02/nodejs-logo.png', 0)");
        $this->db->query("INSERT INTO `cat` (`id`, `title`, `description`, `slug`, `icon`, `parent`) VALUES (4, 'Javascript', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas a posuere mauris, vel cursus magna. In scelerisque pharetra felis, nec faucibus ligula tincidunt tempor. Cras et sem nec erat laoreet lobortis. Donec id lorem quis orci interdum vehicula. Vivamus pretium in risus ac vestibulum.', 'js', 'http://detechter.com/wp-content/uploads/2015/03/jquery-javascript.png', 0)");
        $this->db->query("INSERT INTO `cat` (`id`, `title`, `description`, `slug`, `icon`, `parent`) VALUES (5, 'Autres', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas a posuere mauris, vel cursus magna. In scelerisque pharetra felis, nec faucibus ligula tincidunt tempor. Cras et sem nec erat laoreet lobortis. Donec id lorem quis orci interdum vehicula. Vivamus pretium in risus ac vestibulum.', 'others', 'https://cdn2.iconfinder.com/data/icons/eshop-outline-pack/100/Noun_Project_20Icon_5px_grid-13-512.png', 0)");
    }

    //--------------------------------------------------------------------

    public function down()
    {
        //
    }
}
