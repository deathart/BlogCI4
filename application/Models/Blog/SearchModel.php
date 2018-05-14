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
 * Class SearchModel
 *
 * @package App\Models
 */
class SearchModel extends Model
{
    /**
     * @var \CodeIgniter\Database\BaseBuilder
     */
    protected $article_table;

    /**
     * @var \CodeIgniter\Database\BaseBuilder
     */
    protected $comments_table;

    /**
     * SearchModel constructor.
     *
     * @param array ...$params
     *
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->db = Database::connect();
        $this->article_table = $this->db->table('article');
        $this->comments_table = $this->db->table('comments');
    }

    /**
     * @param string $valeur
     * @param int $type
     *
     * @return array
     */
    public function bytype(string $valeur, int $type):array
    {
        $key_delimiter = explode(',', $valeur);

        if ($type == 1) {
            $this->article_table->select();
            $this->article_table->like('content', $key_delimiter[0]);

            foreach ($key_delimiter as $key=>$key_data) {
                if ($key != 0) {
                    $this->article_table->orLike('content', $key_data);
                }
            }
            $this->article_table->orderBy('id', 'DESC');

            return $this->article_table->get()->getResult('array');
        }

        if ($type == 2) {
            $this->comments_table->select();
            $this->comments_table->like('content', $key_delimiter[0]);

            foreach ($key_delimiter as $key=>$key_data) {
                if ($key != 0) {
                    $this->comments_table->orLike('content', $key_data);
                }
            }
            $this->comments_table->orderBy('id', 'DESC');

            return $this->comments_table->get()->getResult('array');
        }
    }
}
