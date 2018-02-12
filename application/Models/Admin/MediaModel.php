<?php namespace App\Models\Admin;

use CodeIgniter\Model;
use Config\Database;

/**
 * Class MediaModel
 *
 * @package App\Models\Admin
 */
class MediaModel extends Model
{

    /**
     * @var \CodeIgniter\Database\BaseBuilder
     */
    protected $media_table;

    /**
     * MediaModel constructor.
     *
     * @param array ...$params
     *
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->db = Database::connect();
        $this->media_table = $this->db->table('medias');
    }

    /**
     * @param string $slug
     * @param string $name
     *
     * @return int
     */
    public function Add(string $slug, string $name): int
    {
        $data = [
            'slug' => $slug,
            'name' => $name
        ];
        $this->media_table->insert($data);

        return $this->db->insertID();
    }

    /**
     * @return array|mixed
     */
    public function Get_All()
    {
        $this->media_table->select("*, DATE_FORMAT(`date`,'Upload le %d-%m-%Y &agrave; %H:%i:%s') AS `date`");
        $this->media_table->orderBy('date', 'DESC');

        return $this->media_table->get()->getResult();
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function isAlreadyExists(string $name): bool
    {
        $this->media_table->select('id');
        $this->media_table->where('name', $name);
        $this->media_table->limit('1');
        $media_id_query = $this->media_table->get()->getRow();

        return \count($media_id_query) > 0;
    }

    /**
     * @param int $id
     *
     * @return bool
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function removeMedia(int $id): bool
    {
        $this->media_table->delete(['id' => $id]);

        return true;
    }
}
