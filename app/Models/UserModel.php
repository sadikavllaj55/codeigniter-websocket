<?php

namespace App\Models;

use CodeIgniter\Database\Query;
use CodeIgniter\Model;
use Config\Services;
use Exception;

class UserModel extends Model {
    public const PAGE_SIZE = 10;

    protected $table = 'users';

    protected $allowedFields = ['name', 'email', 'password', 'verification_key', 'email_verified', 'role_id'];

    public function canLogin($email, $password) {
        $user = $this->getByEmail($email, true);

        if (!$user) {
            return 'Account does not exist.';
        }

        if ($user->email_verified) {
            if (password_verify($password, $user->password)) {
                $session = Services::session();
                unset($user->password);
                $session->set('user', $user);
                return true;
            } else {
                return 'Invalid credentials.';
            }
        } else {
            return 'Email address not verified yet. <a class="alert-link" href="/register/verify/resend?email=' . $email . '">Resend</a> verification email.';
        }
    }

    public function getByEmail($email, $with_password = false) {
        $sql = '
            SELECT users.*, roles.role 
            FROM users 
                LEFT JOIN roles ON users.role_id = roles.id
            WHERE email = ?';
        $query = $this->db->query($sql, $email);
        if (!$query || $query->getNumRows() != 1) {
            return null;
        } else {
            $user = $query->getResult()[0];

            if (!$with_password) {
                unset($user->password);
            }

            return $user;
        }
    }

    public function getById($id, $with_password = false) {
        $sql = '
            SELECT u.*, r.role, d.name as department
            FROM users u
                LEFT JOIN roles r ON u.role_id = r.id
                LEFT JOIN departments d on u.department_id = d.id
            WHERE u.id = ?';
        $query = $this->db->query($sql, $id);
        if (!$query || $query->getNumRows() != 1) {
            return null;
        } else {
            $user = $query->getResult()[0];

            if (!$with_password) {
                unset($user->password);
            }

            return $user;
        }

    }

    public function new(array $data) {
        $user = $this->getByEmail($data[1]);

        if ($user) {
            session()->setFlashdata('warning', 'An account already exists with this email');
            return -1;
        }
        $query = $this->db->prepare(function ($db) {
            $sql = 'INSERT INTO users (name, email, password, verification_key, department_id) VALUES (?, ?, ?, ?, ?)';
            return (new Query($db))->setQuery($sql);
        });

        if (!isset($data[4])) {
            $data[4] = 1;
        }

        $query->execute(...$data);

        return $this->db->insertID();
    }

    /**
     * @throws Exception
     */
    public function verifyEmail($email, $token) {
        $user = $this->getByEmail($email);

        if (!$user || $token != $user->verification_key) {
            return false;
        }

        $sql = "UPDATE users SET email_verified = 1, verification_key = '' WHERE email = ?";

        $query = $this->db->query($sql, $email);

        if (!$query) {
            throw new Exception($this->db->error());
        }

        return true;
    }

    /**
     * @throws Exception
     */
    public function newPasswordReset($email) {
        helper('text');
        $token = random_string('alnum', 32);
        $sql = "INSERT INTO password_resets(email, token) VALUES (?, ?)";

        $query = $this->db->query($sql, [$email, $token]);

        if (!$query) {
            throw new Exception($this->db->error());
        }

        return $token;
    }

    /**
     * @throws Exception
     */
    public function resetPassword($email, $token, $password) {
        $token_sql = 'SELECT 1 FROM password_resets WHERE email=? AND token=?';
        $token_query = $this->db->query($token_sql, [$email, $token]);
        $result = $token_query->getResultArray();

        if (count($result) === 1) {
            $this->db->transBegin();
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = '
                UPDATE users 
                SET password=? 
                WHERE email=?';
            $query = $this->db->query($sql, [$hashed_password, $email]);
            $this->db->query('DELETE FROM password_resets WHERE email=?', [$email]);
            $this->db->transComplete();

            if (!$query || $this->db->transStatus() === false) {
                throw new Exception('Transaction failed.');
            } else {
                return true;
            }
        } else {
            throw new Exception('Link may have expired. Try sending another reset request.');
        }
    }

    /**
     * @param $user_id
     * @param array $data
     * @return void
     * @throws Exception
     */
    public function editUser($user_id, array $data) {
        $sql = "
            UPDATE users
            SET `name`=?, profile_image=?, telephone=?, website=?, address=?, city=?, country=?, zipcode=?, bio=?
            WHERE id=?
        ";
        $bind = [$data['name'], $data['profile'], $data['tel'], $data['web'], $data['address'], $data['city'],
            $data['country'], $data['zip'], $data['bio'], $user_id];

        $query = $this->db->query($sql, $bind);
        if(!$query) {
            throw new Exception($this->db->error());
        }
    }

    /**
     * @throws Exception
     */
    public function deleteUser($user_id) {
        $sql = "DELETE FROM users WHERE id = ?";
        $query = $this->db->query($sql, $user_id);

        if(!$query){
            throw new Exception($this->db->error());
        }
    }

    public function getAll($search = '') {
        $where = '';
        if (strlen($search) >= 3) {
            $where = "WHERE u.name LIKE '%$search%'";
        }

        $sql = "
            SELECT u.*, d.name as department, r.role
            FROM users u
                LEFT JOIN departments d on u.department_id = d.id
                LEFT JOIN roles r on u.role_id = r.id
            $where
            ORDER BY u.name";
        $query = $this->db->query($sql);

        return $query->getResult();
    }

    public function listUsers($search, $page = 1, $order = 'name') {
        $limit = self::PAGE_SIZE;
        $offset = ($page - 1) * $limit;
        $where = '';
        if (strlen($search) > 3) {
            $where = "WHERE u.name LIKE '%$search%'";
        }

        $sql = "
            SELECT u.*, d.name as department, r.role
            FROM users u
                LEFT JOIN departments d on u.department_id = d.id
                LEFT JOIN roles r on u.role_id = r.id
            $where
            ORDER BY " . $order . "
            LIMIT ? OFFSET ?";
        $query = $this->db->query($sql, [$limit, $offset]);

        return $query->getResult();
    }

    public function numPages($search)
    {
        $where = '';
        if (strlen($search) > 3) {
            $where = "WHERE name LIKE '%$search%'";
        }
        $sql = "
            SELECT COUNT(*) as total 
            FROM users
            $where
        ";

        $total = $this->db->query($sql)->getResult()[0]->total;

        return ceil($total / self::PAGE_SIZE);
    }
}
