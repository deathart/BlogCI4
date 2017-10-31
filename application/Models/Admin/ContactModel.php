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
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->db = Database::connect();
        $this->contact_table = $this->db->table('contact');
    }

    public function getList(int $etat)
    {
        $this->contact_table->select("*, DATE_FORMAT(`date_created`,'<strong>%d-%m-%Y</strong> &agrave; <strong>%H:%i:%s</strong>') AS `date_created`");
        $this->contact_table->where('etat', $etat);
        $this->contact_table->orderBy('id', 'DESC');
        return $this->contact_table->get()->getResult();
    }

    public function markedview(int $id)
    {
        $data = [
            'etat' => 1
        ];
        $this->contact_table->where('id', $id);
        $this->contact_table->update($data);
        return true;
    }

    public function del(int $id)
    {
        $this->contact_table->delete(['id' => $id]);
        return true;
    }

    public function getContact(int $id)
    {
        $this->contact_table->select("*, DATE_FORMAT(`date_created`,'<strong>%d-%m-%Y</strong> &agrave; <strong>%H:%i:%s</strong>') AS `date_created`");
        $this->contact_table->where('id', $id);
        return $this->contact_table->get()->getRow();
    }
}
