<?php

/*
 * BlogCI4 - Blog write with Codeigniter v4dev
 * @author Deathart <contact@deathart.fr>
 * @copyright Copyright (c) 2018 Deathart
 * @license https://opensource.org/licenses/MIT MIT License
 */

namespace App\Models\Blog;

use CodeIgniter\Model;
use Config\Database;

/**
 * Class CategoriesModel
 *
 * @package App\Models\Blog
 */
class CategoriesModel extends Model
{
    /**
     * @var \CodeIgniter\Database\BaseBuilder
     */
    private $categories_table;

    /**
     * CatModel constructor.
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
     * @param int $id
     *
     * @return mixed
     */
    public function GetCategoriesNameAndLink(int $id)
    {
        $this->categories_table->select();
        $this->categories_table->where('id', $id);

        return $this->categories_table->get()->getRow();
    }

    /**
     * @param int $id
     *
     * @return mixed
     */
    public function GetCategoriesByID(int $id)
    {
        $this->categories_table->select();
        $this->categories_table->where('id', $id);

        return $this->categories_table->get()->getRow();
    }

    /**
     * @param string $slug
     *
     * @return mixed
     * @internal param string $slug
     */
    public function GetCategoriesBySlug(string $slug)
    {
        $this->categories_table->select();
        $this->categories_table->where('slug', $slug);

        return $this->categories_table->get()->getRow();
    }

    /**
     * @return array|mixed
     */
    public function GetCategories():array
    {
        $this->categories_table->select();
        $this->categories_table->where('parent', '0');
        $this->categories_table->orderBy('id', 'DESC');

        return $this->categories_table->get()->getResult('array');
    }
}
