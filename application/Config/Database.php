<?php namespace Config;

/**
 * Database Configuration
 *
 * @package Config
 */
class Database extends \CodeIgniter\Database\Config
{
    /**
     * The directory that holds the Migrations
     * and Seeds directories.
     * @var string
     */
    public $filesPath = APPPATH.'Database/';

    /**
     * Lets you choose which connection group to
     * use if no other is specified.
     *
     * @var string
     */
    public $defaultGroup = 'default';

    /**
     * The default database connection.
     *
     * @var array
     */
    public $default = [
        'DSN'          => '',
        'hostname'     => '',
        'username'     => '',
        'password'     => '',
        'database'     => '',
        'DBDriver'     => 'MySQLi',
        'DBPrefix'     => '',
        'pConnect'     => false,
        'DBDebug'     => (ENVIRONMENT !== 'production'),
        'cacheOn'     => false,
        'cacheDir'     => '',
        'charset'      => 'utf8',
        'DBCollat'     => 'utf8_general_ci',
        'swapPre'      => '',
        'encrypt'      => false,
        'compress'     => false,
        'strictOn'     => false,
        'failover'     => [],
    ];

    //--------------------------------------------------------------------
}
