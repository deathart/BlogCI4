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
 * Class ArticleModel
 *
 * @package App\Models
 */
class ArticleModel extends Model
{
    /**
     * @var \CodeIgniter\Database\BaseBuilder
     */
    private $article_table;

    /**
     * ArticleModel constructor.
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
    }

    /**
     * @param string $column
     * @param string $data
     *
     * @return mixed
     */
    public function GetArticle(string $column, string $data)
    {
        $this->article_table->select("*, DATE_FORMAT(`date_created`,'Le %d-%m-%Y &agrave; %H:%i:%s') AS `date_created`, DATE_FORMAT(`date_update`,'Le %d-%m-%Y &agrave; %H:%i:%s') AS `date_update`");
        $this->article_table->where($column, $data);

        return $this->article_table->get()->getRow();
    }

    /**
     * @return array|mixed
     */
    public function lastFive():array
    {
        $this->article_table->select("*, DATE_FORMAT(`date_created`,'<strong>%d-%m-%Y</strong> &agrave; <strong>%H:%i:%s</strong>') AS `date_created`, DATE_FORMAT(`date_update`,'<strong>%d-%m-%Y</strong> &agrave; <strong>%H:%i:%s</strong>') AS `date_update`");
        $this->article_table->limit('5');
        $this->article_table->orderBy('id', 'DESC');

        return $this->article_table->get()->getResult();
    }

    /**
     * @return int|void
     */
    public function count_publied():int
    {
        $this->article_table->select('COUNT(id) as id');
        $this->article_table->where('published', 1);

        return $this->article_table->get()->getRow()->id;
    }

    /**
     * @return int|void
     */
    public function count_attCorrect():int
    {
        $this->article_table->select('COUNT(id) as id');
        $this->article_table->where('corriged', 0);
        $this->article_table->where('published', 0);
        $this->article_table->where('brouillon', 0);

        return $this->article_table->get()->getRow()->id;
    }

    /**
     * @return int|void
     */
    public function count_attPublished():int
    {
        $this->article_table->select('COUNT(id) as id');
        $this->article_table->where('corriged', 1);
        $this->article_table->where('published', 0);

        return $this->article_table->get()->getRow()->id;
    }

    /**
     * @return int|void
     */
    public function count_brouillon():int
    {
        $this->article_table->select('COUNT(id) as id');
        $this->article_table->where('brouillon', 1);

        return $this->article_table->get()->getRow()->id;
    }

    /**
     * @param string $title
     * @param string $link
     * @param string $content
     * @param string $tags
     * @param string $categories
     * @param string $pic
     * @param int $important
     *
     * @return int (Return id)
     */
    public function Add(string $title, string $link, string $content, string $tags, string $categories, string $pic, int $important):int
    {
        $data = [
            'title'          => $title,
            'content'        => $content,
            'author_created' => 1,
            'important'      => $important,
            'link'           => $link,
            'picture_one'    => $pic,
            'categories'     => $categories,
            'tags'           => $tags
        ];
        $this->article_table->insert($data);

        return $this->db->insertID();
    }

    /**
     * @param int $id
     * @param string $title
     * @param string $link
     * @param string $content
     * @param string $tags
     * @param string $categories
     * @param string $pic
     * @param int $important
     * @param int $type
     *
     * @return bool
     */
    public function Edit(int $id, string $title, string $link, string $content, string $tags, string $categories, string $pic, int $important, int $type): bool
    {
        $data = [
            'title'          => $title,
            'content'        => $content,
            'author_created' => 1,
            'author_update'  => 1,
            'important'      => $important,
            'link'           => $link,
            'picture_one'    => $pic,
            'categories'     => $categories,
            'tags'           => $tagsy
        ];

        if ($type == 1) {
            $data['published'] = 1;
            $data['corriged'] = 1;
        } elseif ($type == 2) {
            $data['published'] = 0;
            $data['corriged'] = 0;
            $data['brouillon'] = 0;
        } elseif ($type == 3) {
            $data['published'] = 0;
            $data['corriged'] = 1;
            $data['brouillon'] = 0;
        }

        $this->article_table->where('id', $id);
        $this->article_table->set('date_update', 'NOW()', false);
        $this->article_table->update($data);

        return true;
    }

    /**
     * @param int $type (1 = publied, 2 = wait corrected, 3 = wait publied, 4 = brouillon)
     *
     * @return mixed
     */
    public function getArticleListAdmin(int $type)
    {
        $this->article_table->select("*, DATE_FORMAT(`date_created`,'<strong>%d-%m-%Y</strong> &agrave; <strong>%H:%i:%s</strong>') AS `date_created`, DATE_FORMAT(`date_update`,'<strong>%d-%m-%Y</strong> &agrave; <strong>%H:%i:%s</strong>') AS `date_update`");

        if ($type == 1) {
            $this->article_table->where('published', 1);
        } elseif ($type == 2) {
            $this->article_table->where('corriged', 0);
            $this->article_table->where('published', 0);
            $this->article_table->where('brouillon', 0);
        } elseif ($type == 3) {
            $this->article_table->where('corriged', 1);
            $this->article_table->where('published', 0);
        } elseif ($type == 4) {
            $this->article_table->where('brouillon', 1);
        }

        return $this->article_table->get()->getResult();
    }
}
