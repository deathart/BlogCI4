<?php namespace App\Models\Blog;

use CodeIgniter\Model;
use Config\Database;

/**
 * Class PagesModel
 *
 * @package App\Models
 */
class PagesModel extends Model
{

    /**
     * @var \CodeIgniter\Database\BaseBuilder
     */
    protected $pages_table;

    /**
     * PagesModel constructor.
     *
     * @param array ...$params
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->db = Database::connect();
        $this->pages_table = $this->db->table('pages');
    }

    /**
     * @return array|mixed
     */
    public function GetPages():array
    {
        $this->pages_table->select();
        $this->pages_table->where('active', 1);
        $this->pages_table->orderBy('id', 'DESC');
        return $this->pages_table->get()->getResult('array');
    }

    /**
     * @param string $slug
     *
     * @return mixed
     */
    public function GetPage(string $slug)
    {
        $this->pages_table->select();
        $this->pages_table->where('slug', $slug);
        return $this->pages_table->get()->getRow();
    }
}
