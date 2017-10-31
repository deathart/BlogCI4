<?php namespace App\Models\Blog;

use CodeIgniter\Model;
use Config\Database;

class MediaModel extends Model
{
    /**
     * MediaModel constructor.
     *
     * @param array ...$params
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->db = Database::connect();
        $this->medias_table = $this->db->table('medias');
    }

    public function get_link(int $id)
    {
        $this->medias_table->select('*');
        $this->medias_table->where('id', $id);
        return $this->medias_table->get()->getRow();
    }
}
