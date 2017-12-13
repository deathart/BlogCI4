<?php namespace App\Models\Admin;

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
     * @return array|mixed
     */
    public function GetConfigAll()
    {
        $this->config_table->select();
        return $this->config_table->get()->getResult();
    }

    /**
     * @param int $id
     * @param string $key
     * @param string $data
     *
     * @return bool
     */
    public function UpdateConfig(int $id, string $key, string $data)
    {
        $this->config_table->where('id', $id);
        $this->config_table->update([
            'key'  => $key,
            'data' => $data
        ]);
        return true;
    }

    /**
     * @param int $id
     *
     * @return bool
     */
    public function DelConfig(int $id)
    {
        $this->config_table->where('id', $id);
        $this->config_table->delete();
        return true;
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
