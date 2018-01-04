<?php namespace App\Models\Blog;

use CodeIgniter\Model;
use Config\Database;

/**
 * Class CommentsModel
 *
 * @package App\Models\Blog
 */
class CommentsModel extends Model
{

    /**
     * @var \CodeIgniter\Database\BaseBuilder
     */
    private $comments_table;

    /**
     * CommentsModel constructor.
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
     * @param int $id
     *
     * @return array|mixed
     */
    public function GetComs(int $id):array
    {
        $this->comments_table->select("*, DATE_FORMAT(`created_date`,'Le %d-%m-%Y &agrave; %H:%i:%s') AS `created_date`");
        $this->comments_table->where('post_id', $id);
        $this->comments_table->where('verified', 1);
        $this->comments_table->orderBy('id', 'DESC');

        return $this->comments_table->get()->getResult('array');
    }

    /**
     * @param int $id
     *
     * @return int
     */
    public function CountComs(int $id):int
    {
        $this->comments_table->select('COUNT(id) as id');
        $this->comments_table->where('post_id', $id);
        $this->comments_table->where('verified', 1);

        return $this->comments_table->get()->getRow()->id;
    }

    /**
     * @param int $id
     * @param string $name
     * @param string $email
     * @param string $content
     * @param string $ip
     *
     * @return array|mixed
     */
    public function AddComments(int $id, string $name, string $email, string $content, string $ip)
    {
        $data = [
            'post_id' => $id,
            'author_name' => $name,
            'author_email' => $email,
            'author_ip' => $ip,
            'content' => $content
        ];

        return $this->comments_table->insert($data);
    }
}
