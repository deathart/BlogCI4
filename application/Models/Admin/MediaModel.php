<?php namespace App\Models\Admin;

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

    public function Add(string $slug, string $name)
    {
        $data = [
            'slug' => $slug,
            'name' => $name
        ];
        $this->medias_table->insert($data);
        return $this->db->insertID();
    }
}
