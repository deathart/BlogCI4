<?php namespace App\Models\Blog;

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
     * @var \CodeIgniter\Database\BaseBuilder
     */
    private $article_view_table;

    /**
     * Site constructor.
     *
     * @param array $params
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->db = Database::connect();
        $this->article_table = $this->db->table('article');
        $this->article_view_table = $this->db->table('article_view');
    }

    /**
     * @param string $column
     * @param string $data
     *
     * @return mixed
     */
    public function GetArticle(string $column, string $data)
    {
        $this->article_table->select("*, DATE_FORMAT(`date_created`,'Le %d-%m-%Y à %H:%i:%s') AS `date_created`, DATE_FORMAT(`date_update`,'Le %d-%m-%Y à %H:%i:%s') AS `date_update`");
        $this->article_table->where($column, $data);
        return $this->article_table->get()->getRow();
    }

    /**
     * @param int|NULL $limit
     * @param bool $important
     *
     * @return array
     */
    public function GetLastArticle(int $limit = null, bool $important = false): array
    {
        $this->article_table->select("*, DATE_FORMAT(`date_created`,'Le %d-%m-%Y à %H:%i:%s') AS `date_created`, DATE_FORMAT(`date_update`,'Le %d-%m-%Y à %H:%i:%s') AS `date_update`");
        if ($important) {
            $this->article_table->where('important', '1');
        }
        $this->article_table->where('published', '1');
        if ($limit != null) {
            $this->article_table->limit($limit);
        }
        $this->article_table->orderBy('id', 'DESC');
        return $this->article_table->get()->getResult('array');
    }

    /**
     * @param int $cat_id
     * @param int $per_page
     * @param int $page
     *
     * @return array
     */
    public function GetArticleByCat(int $cat_id, int $per_page, int $page): array
    {
        $offset = ($page-1)*$per_page;

        $this->article_table->select("*, DATE_FORMAT(`date_created`,'Le %d-%m-%Y à %H:%i:%s') AS `date_created`, DATE_FORMAT(`date_update`,'Le %d-%m-%Y à %H:%i:%s') AS `date_update`");
        $this->article_table->where('published', '1');
        $this->article_table->like('cat', $cat_id);
        $this->article_table->orderBy('id', 'DESC');
        $this->article_table->limit($per_page, $offset);

        return $this->article_table->get()->getResult('array');
    }

    /**
     * @param int $cat
     *
     * @return int
     */
    public function nb_articleByCat(int $cat):int
    {
        $this->article_table->select('COUNT(id) as id');
        $this->article_table->where('published', '1');
        $this->article_table->where('corriged', '1');
        $this->article_table->like('cat', $cat);
        return $this->article_table->get()->getRow()->id;
    }

    /**
     * @param int $id
     * @param string $ip
     *
     * @return bool
     */
    public function add_view(int $id, string $ip):bool
    {
        $this->article_view_table->select('*');
        $this->article_view_table->where('post_id', $id);
        $this->article_view_table->where('ip', $ip);
        $verify = $this->article_view_table->get()->getRow();
        if (!isset($verify->id)) {
            $data = [
                'post_id' => $id,
                'ip'      => $ip
            ];
            $this->article_view_table->insert($data);
            return true;
        }

        return false;
    }

    /**
     * @param int $id
     *
     * @return int|void
     */
    public function nb_PostView(int $id):int
    {
        $this->article_view_table->select('COUNT(id) as id');
        $this->article_view_table->where('post_id', $id);
        return $this->article_view_table->get()->getRow()->id;
    }

    /**
     * @param string $keyword
     *
     * @return array|mixed
     */
    public function GetRelated(string $keyword):array
    {
        $keys = explode(',', $keyword);

        $this->article_table->select('*');
        $this->article_table->like('keyword', $keys[0]);

        foreach ($keys as $key=>$key_data) {
            if ($key != 0) {
                $this->article_table->orLike('keyword', $key_data);
            }
        }

        $this->article_table->limit('5');
        $this->article_table->orderBy('id', 'DESC');

        return $this->article_table->get()->getResult('array');
    }

    /**
     * @param string $keyword
     * @param int $per_page
     * @param int|NULL $page
     *
     * @return array|mixed
     */
    public function GetArticleByTags(string $keyword, int $per_page = 8, int $page = null)
    {
        if ($page == null) {
            $this->article_table->select('COUNT(id) as id');
            $this->article_table->where('published', '1');
            $this->article_table->like('keyword', $keyword);

            return $this->article_table->get()->getRow()->id;
        } else {
            $offset = ($page-1)*$per_page;

            $this->article_table->select("*, DATE_FORMAT(`date_created`,'Le %d-%m-%Y à %H:%i:%s') AS `date_created`, DATE_FORMAT(`date_update`,'Le %d-%m-%Y à %H:%i:%s') AS `date_update`");
            $this->article_table->where('published', '1');
            $this->article_table->like('keyword', $keyword);
            $this->article_table->orderBy('id', 'DESC');
            $this->article_table->limit($per_page, $offset);

            return $this->article_table->get()->getResult('array');
        }
    }
}
