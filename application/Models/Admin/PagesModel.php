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
     *
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->db = Database::connect();
        $this->pages_table = $this->db->table('pages');
    }

    /**
     * @param bool $active
     *
     * @return array|mixed
     */
    public function GetPages(bool $active = true):array
    {
        $this->pages_table->select();
        if ($active) {
            $this->pages_table->where('active', 1);
        }
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

    /**
     * @param int $id
     * @param string $title
     * @param string $slug
     * @param string $content
     * @param int $active
     *
     * @return bool
     */
    public function Edit(int $id, string $title, string $slug, string $content, int $active): bool
    {
        $data = [
            'title'   => $title,
            'content' => $content,
            'slug'    => $slug,
            'active'  => $active,
        ];

        $this->pages_table->where('id', $id);
        $this->pages_table->update($data);

        return true;
    }

    /**
     * @param string $title
     * @param string $slug
     * @param string $content
     * @param int $active
     *
     * @return bool
     */
    public function Add(string $title, string $slug, string $content, int $active): bool
    {
        $data = [
            'title'   => $title,
            'content' => $content,
            'slug'    => $slug,
            'active'  => $active,
        ];

        $this->pages_table->insert($data);

        return true;
    }
}
