<?php

/*
 * BlogCI4 - Blog write with Codeigniter v4dev
 * @author Deathart <contact@deathart.fr>
 * @copyright Copyright (c) 2018 Deathart
 * @license https://opensource.org/licenses/MIT MIT License
 */

namespace App\Models\Blog;

use CodeIgniter\Model;
use Config\Database;

/**
 * Class ContactModel
 *
 * @package App\Models\Blog
 */
class ContactModel extends Model
{
    /**
     * @var \CodeIgniter\Database\BaseBuilder
     */
    private $contact_table;

    /**
     * Contact constructor.
     *
     * @param array $params
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
     * @param string $ip
     *
     * @return bool
     */
    public function Check(string $ip): bool
    {
        $this->contact_table->select('COUNT(id) as id');
        $this->contact_table->where('ip', $ip);
        $this->contact_table->where('date_created >', date('Y-m-d H:i:s', strtotime('-1 day')));
        $result = $this->contact_table->get()->getRow()->id;

        return !($result == 0);
    }

    /**
     * @param string $name
     * @param string $email
     * @param string $sujet
     * @param string $message
     * @param string $ip
     *
     * @return mixed
     */
    public function Add(string $name, string $email, string $sujet, string $message, string $ip)
    {
        $data = [
            'name' => $name,
            'email' => $email,
            'sujet' => $sujet,
            'message' => $message,
            'ip' => $ip,
        ];

        return $this->contact_table->insert($data);
    }
}
