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

        $this->db->query('INSERT INTO `users` (`username`, `password`, `email`) VALUES (\'admin\', \'$2y$10$YFeHHk2cC1GFPWhw2yQMSeY9DFiFNjsOu3/jGnLzyGBqVqG5sOUDO\', \'contact@blog.dev\')');
    }
}
