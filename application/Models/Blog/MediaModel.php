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
 * Class MediaModel
 *
 * @package App\Models\Blog
 */
class MediaModel extends Model
{
    /**
     * @var \CodeIgniter\Database\BaseBuilder
     */
    protected $medias_table;

    /**
     * MediaModel constructor.
     *
     * @param array ...$params
     *
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->db = Database::connect();
        $this->medias_table = $this->db->table('medias');
    }

    /**
     * @param int $id
     *
     * @return mixed
     */
    public function get_link(int $id)
    {
        $this->medias_table->select();
        $this->medias_table->where('id', $id);

        return $this->medias_table->get()->getRow();
    }
}
