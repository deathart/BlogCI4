<?php namespace App\Models\Admin;

use CodeIgniter\Model;
use Config\Database;

/**
 * Class AuthModel
 *
 * @package App\Models
 */
class AuthModel extends Model
{

    /**
     * @var \CodeIgniter\Database\BaseBuilder
     */
    private $user_table;

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
        $this->user_table = $this->db->table('users');
    }

    /**
     * @param $email
     *
     * @return bool
     */
    public function isValidEmail($email):bool
    {
        $this->user_table->select('id');
        $this->user_table->where('email', $email);
        $this->user_table->limit('1');
        $account_id_query = $this->user_table->get()->getRow();
        if (\count($account_id_query) > 0) {
            return $account_id_query->id;
        }

        return false;
    }

    /**
     * @param $pass
     * @param $account
     *
     * @return bool
     */
    public function isValidPassword($pass, $account):bool
    {
        $this->user_table->select('password');
        $this->user_table->where('id', $account);
        $pass_db = $this->user_table->get()->getRow()->password;

        if (password_verify($pass, $pass_db)) {
            return true;
        }

        return false;
    }

    /**
     * @param $account
     *
     * @return mixed
     */
    public function GetUsername($account)
    {
        $this->user_table->select('username');
        $this->user_table->where('id', $account);

        return $this->user_table->get()->getRow()->username;
    }

    /**
     * @param $account
     *
     * @return mixed
     */
    public function GetAvatar($account)
    {
        $this->user_table->select('avatar');
        $this->user_table->where('id', $account);
        if ($this->user_table->get()->getRow()->avatar != 'user.png') {
            return 'uploads/avatar/' . $this->user_table->get()->getRow()->avatar;
        }

        return 'images/admin/user.png';
    }
}
