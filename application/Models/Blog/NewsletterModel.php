<?php namespace App\Models\Blog;

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
        $this->newsletter_table->select('COUNT(id) as id');
        $this->newsletter_table->where('ip', $ip);
        $this->newsletter_table->orWhere('email', $email);
        $result = $this->newsletter_table->get()->getRow()->id;

        return !($result == 0);
    }

    /**
     * @param string $ip
     * @param string $email
     *
     * @return bool
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
