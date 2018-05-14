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
 * Class NewsletterModel
 *
 * @package App\Models
 */
class NewsletterModel extends Model
{
    /**
     * @var \CodeIgniter\Database\BaseBuilder
     */
    private $newsletter_table;

    /**
     * NewsletterModel constructor.
     *
     * @param array $params
     *
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->db = Database::connect();
        $this->newsletter_table = $this->db->table('newsletter');
    }

    /**
     * @param string $ip
     * @param string $email
     *
     * @return bool
     */
    public function Check(string $ip, string $email): bool
    {
        $this->newsletter_table->select('id');
        $this->newsletter_table->where('ip', $ip);
        $this->newsletter_table->orWhere('email', $email);

        if (\count($this->newsletter_table->get()->getRow()) > 0) {
            return true;
        }

        return false;
    }

    /**
     * @param string $ip
     * @param string $email
     *
     * @return mixed
     */
    public function Add(string $ip, string $email)
    {
        $data = [
            'ip' => $ip,
            'email' => $email
        ];

        return $this->newsletter_table->insert($data);
    }
}
