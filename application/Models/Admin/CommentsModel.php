<?php namespace App\Models\Admin;

use CodeIgniter\Model;
use Config\Database;

/**
 * Class CommentsModel
 *
 * @package App\Models
 */
class CommentsModel extends Model
{

    /**
     * @var \CodeIgniter\Database\BaseBuilder
     */
    private $comments_table;

    /**
     * Site constructor.
     *
     * @param array ...$params
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->db = Database::connect();
        $this->comments_table = $this->db->table('comments');
    }

    /**
     * @param string $type
     *
     * @return mixed
     */
    public function countComments(string $type)
    {
        $this->comments_table->select('COUNT(id) as id');
        $this->comments_table->where('verified', $type);
        return $this->comments_table->get()->getRow()->id;
    }

    /**
     * @param string $type
     *
     * @return array|mixed
     */
    public function GetComments(string $type)
    {
        $this->comments_table->select("comments.*, article.title AS article_title, article.link AS article_slug, DATE_FORMAT(`created_date`,'<strong>%d-%m-%Y</strong> &agrave; <strong>%H:%i:%s</strong>') AS `created_date`");
        $this->comments_table->where('verified', $type);
        $this->comments_table->join('article', 'article.id = comments.post_id', 'left');
        return $this->comments_table->get()->getResult();
    }

    /**
     * @param int $id
     *
     * @return bool
     */
    public function valideComments(int $id)
    {
        $data = [
            'verified' => 1
        ];
        $this->comments_table->where('id', $id);
        $this->comments_table->update($data);
        return true;
    }

    /**
     * @param int $id
     *
     * @return bool
     */
    public function refuseComments(int $id)
    {
        $data = [
            'verified' => '-1'
        ];
        $this->comments_table->where('id', $id);
        $this->comments_table->update($data);
        return true;
    }
}
