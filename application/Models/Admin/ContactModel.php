<?php namespace App\Models\Admin;

use CodeIgniter\Model;
use Config\Database;

/**
 * Class ProjectModel
 *
 * @package App\Models
 */
class ContactModel extends Model
{

    /**
     * @var \CodeIgniter\Database\BaseBuilder
     */
    private $contact_table;

    /**
     * Site constructor.
     *
     * @param array ...$params
     *
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->db = Database::connect();
        $this->contact_table = $this->db->table('contact');
    }

    /**
     * @param int $etat
     *
     * @return array|mixed
     */
    public function getList(int $etat)
    {
        $this->contact_table->select("*, DATE_FORMAT(`date_created`,'<strong>%d-%m-%Y</strong> &agrave; <strong>%H:%i:%s</strong>') AS `date_created`");
        $this->contact_table->where('etat', $etat);
        $this->contact_table->orderBy('id', 'DESC');

        return $this->contact_table->get()->getResult();
    }

    /**
     * @param int $id
     *
     * @return bool
     */
    public function markedview(int $id): bool
    {
        $data = [
            'etat' => 1
        ];
        $this->contact_table->where('id', $id);
        $this->contact_table->update($data);

        return true;
    }

    /**
     * @param int $id
     *
     * @return bool
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function del(int $id): bool
    {
        $this->contact_table->delete(['id' => $id]);

        return true;
    }

    /**
     * @param int $id
     *
     * @return mixed
     */
    public function getContact(int $id)
    {
        $this->contact_table->select("*, DATE_FORMAT(`date_created`,'<strong>%d-%m-%Y</strong> &agrave; <strong>%H:%i:%s</strong>') AS `date_created`");
        $this->contact_table->where('id', $id);

        return $this->contact_table->get()->getRow();
    }
}
