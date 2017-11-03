<?php namespace App\Models\Blog;

use CodeIgniter\Model;
use Config\Database;

/**
 * Class ProjectModel
 *
 * @package App\Models
 */
class SearchModel extends Model
{

    /**
     * Site constructor.
     *
     * @param array ...$params
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->db = Database::connect();
        $this->post_table = $this->db->table('post');
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
            $this->post_table->select('*');
            $this->post_table->like('content', $key_delimiter[0]);

            foreach ($key_delimiter as $key=>$key_data) {
                if ($key != 0) {
                    $this->post_table->orLike('content', $key_data);
                }
            }
            $this->post_table->orderBy('id', 'DESC');
            return $this->post_table->get()->getResult('array');
        } elseif ($type == 2) {
            $this->comments_table->select('*');
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
