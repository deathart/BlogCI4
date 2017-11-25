<?php namespace App\Models\Blog;

use CodeIgniter\Model;
use Config\Database;

/**
 * Class CatModel
 *
 * @package App\Models\Blog
 */
class CatModel extends Model
{

    /**
     * @var \CodeIgniter\Database\BaseBuilder
     */
    private $cat_table;

    /**
     * CatModel constructor.
     *
     * @param array ...$params
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->db = Database::connect();
        $this->cat_table = $this->db->table('cat');
    }

    /**
     * @param int $id
     *
     * @return mixed
     */
    public function GetCatNameAndLink(int $id)
    {
        $this->cat_table->select();
        $this->cat_table->where('id', $id);
        return $this->cat_table->get()->getRow();
    }

    /**
     * @param int $id
     *
     * @return mixed
     */
    public function GetCatByID(int $id)
    {
        $this->cat_table->select();
        $this->cat_table->where('id', $id);
        return $this->cat_table->get()->getRow();
    }

    /**
     * @param string $slug
     *
     * @return mixed
     * @internal param string $slug
     */
    public function GetCatByLink(string $slug)
    {
        $this->cat_table->select();
        $this->cat_table->where('slug', $slug);
        return $this->cat_table->get()->getRow();
    }

    /**
     * @return array|mixed
     */
    public function GetCat():array
    {
        $this->cat_table->select();
        $this->cat_table->where('parent', '0');
        $this->cat_table->orderBy('id', 'DESC');
        return $this->cat_table->get()->getResult('array');
    }
}
