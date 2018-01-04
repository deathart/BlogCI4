<?php namespace App\Models\Admin;

use CodeIgniter\Model;
use Config\Database;

/**
 * Class CatModel
 *
 * @package App\Models
 */
class CatModel extends Model
{

    /**
     * @var \CodeIgniter\Database\BaseBuilder
     */
    private $cat_table;

    /**
     * Site constructor.
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
     * @return array
     */
    public function getlist():array
    {
        $this->cat_table->select();
        $this->cat_table->where('parent', '0');
        $this->cat_table->orderBy('id', 'ASC');

        return $this->cat_table->get()->getResult('array');
    }

    /**
     * @param int $id
     * @param string $column
     * @param string $data
     *
     * @return bool
     */
    public function UpdateCat(int $id, string $column, string $data):bool
    {
        $this->cat_table->set($column, $data);
        $this->cat_table->where('id', $id);
        $this->cat_table->update();

        return true;
    }

    /**
     * @param string $title
     * @param string $content
     * @param string $slug
     * @param string $icon
     */
    public function AddCat(string $title, string $content, string $slug, string $icon)
    {
        $data = [
            'title'       => $title,
            'description' => $content,
            'slug'        => $slug,
            'icon'        => $icon
        ];
        $this->cat_table->insert($data);
    }
}
