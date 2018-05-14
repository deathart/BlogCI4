<?php

/*
 * BlogCI4 - Blog write with Codeigniter v4dev
 * @author Deathart <contact@deathart.fr>
 * @copyright Copyright (c) 2018 Deathart
 * @license https://opensource.org/licenses/MIT MIT License
 */

namespace App\Models\Admin;

use CodeIgniter\Model;
use Config\Database;

/**
 * Class CatModel
 *
 * @package App\Models
 */
class CategoriesModel extends Model
{
    /**
     * @var \CodeIgniter\Database\BaseBuilder
     */
    private $categories_table;

    /**
     * Site constructor.
     *
     * @param array ...$params
     *
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->db = Database::connect();
        $this->categories_table = $this->db->table('categories');
    }

    /**
     * @return array
     */
    public function getlist():array
    {
        $this->categories_table->select();
        $this->categories_table->where('parent', '0');
        $this->categories_table->orderBy('id', 'ASC');

        return $this->categories_table->get()->getResult('array');
    }

    /**
     * @param int $id
     * @param string $column
     * @param string $data
     *
     * @return bool
     */
    public function UpdateCategories(int $id, string $column, string $data):bool
    {
        $this->categories_table->set($column, $data);
        $this->categories_table->where('id', $id);
        $this->categories_table->update();

        return true;
    }

    /**
     * @param string $title
     * @param string $content
     * @param string $slug
     * @param string $icon
     */
    public function AddCategories(string $title, string $content, string $slug, string $icon)
    {
        $data = [
            'title'       => $title,
            'description' => $content,
            'slug'        => $slug,
            'icon'        => $icon
        ];
        $this->categories_table->insert($data);
    }
}
