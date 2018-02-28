<?php

/**
 * Class DatabaseSeeder
 */
class DatabaseSeeder extends \CodeIgniter\Database\Seeder
{
    /**
     * @return mixed|void
     */
    public function run()
    {
        $this->db->query("INSERT INTO `config` (`key`, `data`) VALUES ('site_title', 'Mon blog')");
        $this->db->query("INSERT INTO `config` (`key`, `data`, `type`) VALUES ('site_description', 'Mon blog préféré', 'text')");
        $this->db->query("INSERT INTO `config` (`key`, `data`, `type`) VALUES ('site_keyword', 'ide, development, free, open source, articles, website, php, javascript, jquery, node.js, codeigniter, ci3, ci4, tutorials', 'text')");
        $this->db->query("INSERT INTO `config` (`key`, `data`, `type`) VALUES ('presentation', 'Change the presentation from <strong>administration</strong>', 'text')");
        $this->db->query("INSERT INTO `config` (`key`, `data`, `type`) VALUES ('annonce_general', 'You can change this in the <strong>administration</strong>', 'text')");
        $this->db->query("INSERT INTO `config` (`key`, `data`, `type`) VALUES ('home_annonce_1', 'You can change this in the <strong>administration</strong>', 'text')");
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
        $this->db->query("INSERT INTO `config` (`key`, `data`) VALUES ('cache', 'on')");
        $this->db->query("INSERT INTO `config` (`key`, `data`) VALUES ('lang', 'en')");
        $this->db->query("INSERT INTO `config` (`key`, `data`) VALUES ('btn_facebook_link', '')");
        $this->db->query("INSERT INTO `config` (`key`, `data`) VALUES ('btn_twitter_link', '')");
        $this->db->query("INSERT INTO `config` (`key`, `data`) VALUES ('btn_googleplus_link', '')");
        $this->db->query("INSERT INTO `config` (`key`, `data`) VALUES ('contact_active', '1')");
        $this->db->query("INSERT INTO `config` (`key`, `data`) VALUES ('debug', '0')");
        $this->db->query("INSERT INTO `config` (`key`, `data`) VALUES ('theme_blog', 'default')");
        $this->db->query("INSERT INTO `config` (`key`, `data`) VALUES ('theme_admin', 'default')");

        $this->db->query('INSERT INTO `users` (`username`, `password`, `email`) VALUES (\'admin\', \'$2y$10$YFeHHk2cC1GFPWhw2yQMSeY9DFiFNjsOu3/jGnLzyGBqVqG5sOUDO\', \'contact@blog.dev\')');

        $this->db->query("INSERT INTO `cat` (`id`, `title`, `description`, `slug`, `icon`, `parent`) VALUES (1, 'PHP', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas a posuere mauris, vel cursus magna. In scelerisque pharetra felis, nec faucibus ligula tincidunt tempor. Cras et sem nec erat laoreet lobortis. Donec id lorem quis orci interdum vehicula. Vivamus pretium in risus ac vestibulum.', 'php', 'http://www.leifzimmerman.com/images/icons/icon_large_php.png', 0)");

        $this->db->query("INSERT INTO `article` VALUES(1, 'Welcome', '[header=\"h2\"]Lorem ipsum dolor sit amet[/header]\n\n[align=\"left\"]Lorem [b][u]ipsum dolor sit amet[/u][/b], consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut[i] aliquip ex ea commodo[/i] consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.[/align]\n\n[align=\"center\"]Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.[/align]\n\n[align=\"right\"]Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.[/align]\n\n[link=\"https://github.com/deathart/BlogCI4\"]BlogCI4 Homepage[/link]\n\n[quote]Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.[/quote]\n\n[code=\"php\"]<?php namespace App\Libraries;\n\nuse Config\App;\nuse Config\Services;\n\n[/code]\n\n[img id=\"1\" width=\"0\" height=\"0\"]\n\n[alert=\"success\"]success[/alert]\n[alert=\"info\"]info[/alert]\n[alert=\"warning\"]warning[/alert]\n[alert=\"danger\"]danger[/alert]', 1, '2018-01-05 16:06:54', 1, '2018-01-05 16:18:15', 1, 'Welcome', 'uploads/2018/1/welcome.png', '1', 1, 1, 'PHP, HTML, CSS', 0)");

        $this->db->query("INSERT INTO `pages` VALUES(2, 'Welcome', '[header=\"h2\"]Lorem ipsum dolor sit amet[/header]\n\n[align=\"left\"]Lorem [b][u]ipsum dolor sit amet[/u][/b], consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut[i] aliquip ex ea commodo[/i] consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.[/align]\n\n[align=\"center\"]Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.[/align]\n\n[align=\"right\"]Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.[/align]\n\n[link=\"https://github.com/deathart/BlogCI4\"]BlogCI4 Homepage[/link]\n\n[quote]Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.[/quote]\n\n[code=\"php\"]<?php namespace App\Libraries;\n\nuse Config\App;\nuse Config\Services;\n\n[/code]\n\n[img id=\"1\" width=\"0\" height=\"0\"]\n\n[alert=\"success\"]success[/alert]\n[alert=\"info\"]info[/alert]\n[alert=\"warning\"]warning[/alert]\n[alert=\"danger\"]danger[/alert]', 'Welcome', 1)");

        $this->db->query("INSERT INTO `medias` VALUES(1, 'uploads/2018/1/', 'welcome.png', '2018-01-05 16:06:28')");
    }
}
