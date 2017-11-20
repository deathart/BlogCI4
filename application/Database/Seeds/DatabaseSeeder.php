<?php

class DatabaseSeeder extends \CodeIgniter\Database\Seeder
{
    public function run()
    {
        $this->db->query("INSERT INTO `config` (`key`, `data`) VALUES ('site_title', 'Mon blog')");
        $this->db->query("INSERT INTO `config` (`key`, `data`, `type`) VALUES ('site_description', 'Mon blog préféré', 'text')");
        $this->db->query("INSERT INTO `config` (`key`, `data`, `type`) VALUES ('site_keyword', 'ide, development, free, open source, articles, website, php, javascript, jquery, node.js, codeigniter, ci3, ci4, tutorials', 'text')");
        $this->db->query("INSERT INTO `config` (`key`, `data`, `type`) VALUES ('presentation', 'Lorem ipsun', 'text')");
        $this->db->query("INSERT INTO `config` (`key`, `data`, `type`) VALUES ('annonce_general', 'Annonce', 'text')");
        $this->db->query("INSERT INTO `config` (`key`, `data`, `type`) VALUES ('home_annonce_1', 'Annonce 1', 'text')");
        $this->db->query("INSERT INTO `config` (`key`, `data`, `type`) VALUES ('home_annonce_2', 'Annonce 2', 'text')");
        $this->db->query("INSERT INTO `config` (`key`, `data`) VALUES ('pub_active', '0')");
        $this->db->query("INSERT INTO `config` (`key`, `data`) VALUES ('analytics_active', '0')");
        $this->db->query("INSERT INTO `config` (`key`, `data`, `type`) VALUES ('analytics_code', '', 'text')");
        $this->db->query("INSERT INTO `config` (`key`, `data`) VALUES ('mail_host', 'localhost')");
        $this->db->query("INSERT INTO `config` (`key`, `data`) VALUES ('mail_port', '25')");
        $this->db->query("INSERT INTO `config` (`key`, `data`) VALUES ('mail_security', '')");
        $this->db->query("INSERT INTO `config` (`key`, `data`) VALUES ('mail_username', '')");
        $this->db->query("INSERT INTO `config` (`key`, `data`) VALUES ('mail_password', '')");
        $this->db->query("INSERT INTO `config` (`key`, `data`) VALUES ('mail_from_adress', '')");
        $this->db->query("INSERT INTO `config` (`key`, `data`) VALUES ('mail_from_name', '')");
        $this->db->query("INSERT INTO `config` (`key`, `data`) VALUES ('cache', 'off')");
        $this->db->query("INSERT INTO `config` (`key`, `data`) VALUES ('lang', 'en')");

        $this->db->query('INSERT INTO `users` (`username`, `password`, `email`) VALUES (\'admin\', \'$2y$10$YFeHHk2cC1GFPWhw2yQMSeY9DFiFNjsOu3/jGnLzyGBqVqG5sOUDO\', \'contact@blog.dev\')');

        $this->db->query("INSERT INTO `cat` (`id`, `title`, `description`, `slug`, `icon`, `parent`) VALUES (1, 'PHP', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas a posuere mauris, vel cursus magna. In scelerisque pharetra felis, nec faucibus ligula tincidunt tempor. Cras et sem nec erat laoreet lobortis. Donec id lorem quis orci interdum vehicula. Vivamus pretium in risus ac vestibulum.', 'php', 'http://www.leifzimmerman.com/images/icons/icon_large_php.png', 0)");
        $this->db->query("INSERT INTO `cat` (`id`, `title`, `description`, `slug`, `icon`, `parent`) VALUES (2, 'HTML/CSS', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas a posuere mauris, vel cursus magna. In scelerisque pharetra felis, nec faucibus ligula tincidunt tempor. Cras et sem nec erat laoreet lobortis. Donec id lorem quis orci interdum vehicula. Vivamus pretium in risus ac vestibulum.', 'html-css', 'http://solidfoundationwebdev.com/system/categories/images/000/000/004/original/html-css.png', 0)");
        $this->db->query("INSERT INTO `cat` (`id`, `title`, `description`, `slug`, `icon`, `parent`) VALUES (3, 'Node JS', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas a posuere mauris, vel cursus magna. In scelerisque pharetra felis, nec faucibus ligula tincidunt tempor. Cras et sem nec erat laoreet lobortis. Donec id lorem quis orci interdum vehicula. Vivamus pretium in risus ac vestibulum.', 'node-js', 'http://glenneggleton.com/files/2016-02/nodejs-logo.png', 0)");
        $this->db->query("INSERT INTO `cat` (`id`, `title`, `description`, `slug`, `icon`, `parent`) VALUES (4, 'Javascript', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas a posuere mauris, vel cursus magna. In scelerisque pharetra felis, nec faucibus ligula tincidunt tempor. Cras et sem nec erat laoreet lobortis. Donec id lorem quis orci interdum vehicula. Vivamus pretium in risus ac vestibulum.', 'js', 'http://detechter.com/wp-content/uploads/2015/03/jquery-javascript.png', 0)");
        $this->db->query("INSERT INTO `cat` (`id`, `title`, `description`, `slug`, `icon`, `parent`) VALUES (5, 'Autres', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas a posuere mauris, vel cursus magna. In scelerisque pharetra felis, nec faucibus ligula tincidunt tempor. Cras et sem nec erat laoreet lobortis. Donec id lorem quis orci interdum vehicula. Vivamus pretium in risus ac vestibulum.', 'others', 'https://cdn2.iconfinder.com/data/icons/eshop-outline-pack/100/Noun_Project_20Icon_5px_grid-13-512.png', 0)");
    }
}
