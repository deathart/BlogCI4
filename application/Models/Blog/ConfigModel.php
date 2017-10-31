<?php namespace App\Models\Blog;

use CodeIgniter\Model;
use Config\Database;

/**
 * Class ConfigModel
 *
 * @package App\Models
 */
class ConfigModel extends Model
{

    /**
     * @var \CodeIgniter\Database\BaseBuilder
     */
    private $config_table;

    /**
     * Site constructor.
     *
     * @param array ...$params
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->db = Database::connect();
        $this->config_table = $this->db->table('config');
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function GetConfig(string $key)
    {
        $this->config_table->select('data');
        $this->config_table->where('key', $key);
        return $this->config_table->get()->getRow()->data;
    }
}
