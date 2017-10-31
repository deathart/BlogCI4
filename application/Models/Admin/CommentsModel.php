<?php namespace App\Models\Admin;

use CodeIgniter\Model;
use Config\Database;

/**
 * Class PostModel
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

    public function countComments(string $type)
    {
        $this->comments_table->select('COUNT(id) as id');
        $this->comments_table->where('verified', $type);
        return $this->comments_table->get()->getRow()->id;
    }

    public function GetComments(string $type)
    {
        $this->comments_table->select("comments.*, post.title AS post_title, post.link AS post_slug, DATE_FORMAT(`created_date`,'<strong>%d-%m-%Y</strong> &agrave; <strong>%H:%i:%s</strong>') AS `created_date`");
        $this->comments_table->where('verified', $type);
        $this->comments_table->join('post', 'post.id = comments.post_id', 'left');
        return $this->comments_table->get()->getResult();
    }

    public function valideComments(int $id)
    {
        $data = [
            'verified' => 1
        ];
        $this->comments_table->where('id', $id);
        $this->comments_table->update($data);
        return true;
    }
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
