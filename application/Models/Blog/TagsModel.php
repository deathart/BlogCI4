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

class TagsModel extends Model
{
    /**
     * @var \CodeIgniter\Database\BaseBuilder 
     */
    private $tags_table;

    /**
     * TagsModel constructor.
     * @param array $params
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->db = Database::connect();
        $this->tags_table = $this->db->table('tags');
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function GetById(int $id)
    {
        $this->tags_table->select();
        $this->tags_table->where('id', $id);

        return $this->tags_table->get()->getRow();
    }

    /**
     * @param string $slug
     * @return mixed
     */
    public function GetBySlug(string $slug)
    {
        $this->tags_table->select();
        $this->tags_table->where('slug', $slug);

        return $this->tags_table->get()->getRow();
    }
}