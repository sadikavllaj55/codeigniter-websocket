<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;


class Department extends Model
{
    public $table = 'departments';

    public $allowedFields = ['name', 'parent'];

    /**
     * @throws Exception
     */
    public function getDepartments()
    {
        $sql = '
            SELECT d.*, COUNT(u.id) as employees, p.name as parent_name
            FROM departments d
                LEFT JOIN users u on d.id = u.department_id
                LEFT JOIN departments p on d.parent = p.id
            GROUP BY d.id
            ';
        $query = $this->db->query($sql);
        if (!$query) {
            throw new Exception($this->db->error());
        }

        return $query->getResult();
    }

    public function getByName($name)
    {
        $sql = 'SELECT * FROM departments WHERE name = ?';
        $query = $this->db->query($sql, $name);

        if ($query->getNumRows() === 0) {
            return false;
        } else {
            return $query->getRowObject();
        }
    }

    public function getById($id)
    {
        $sql = 'SELECT * FROM departments WHERE id = ?';
        $query = $this->db->query($sql, $id);

        if ($query->getNumRows() > 0) {
            return $query->getRowObject();
        } else {
            return false;
        }
    }

    /**
     * @throws Exception
     */
    public function createDepartment(array $data)
    {
        $sql = 'INSERT INTO departments (name, parent) VALUES (?,?)';
        $bind = [$data['name'], $data['parent']];
        $query = $this->db->query($sql, $bind);

        if (!$query) {
            throw new Exception('Could not create the department. Something went wrong');
        }

        return $this->db->insertID();
    }

    /**
     * @throws Exception
     */
    public function editDepartment($id, $data)
    {
        $sql = "
            UPDATE departments
            SET name =?, parent=?
            WHERE id=?
        ";
        $bind = [$data['name'], $data['parent'], $id];

        $query = $this->db->query($sql, $bind);
        if (!$query) {
            throw new Exception('Could not edit the department. Something went wrong');
        }
    }

    /**
     * @throws Exception
     */
    public function deleteDepartment($id)
    {
        $delete = "DELETE FROM departments WHERE id = ?";
        $update = "UPDATE departments SET parent=null WHERE parent = ?";
        $users = "UPDATE users SET department_id=null WHERE department_id = ?";

        $query_delete = $this->db->query($delete, $id);
        $query_update = $this->db->query($update, $id);
        $query_users = $this->db->query($users, $id);

        if (!$query_delete || !$query_update || !$query_users) {
            throw new Exception('Could not delete the department. Something went wrong');
        }
    }

    /**
     * @throws Exception
     */
    public function removeEmployee($id, $user_id) {
        $sql = '
            UPDATE users 
            SET department_id=null
            WHERE department_id=? AND id=?';

        $query = $this->db->query($sql, [$id, $user_id]);

        if (!$query) {
            throw new Exception('Could not remove employee');
        }
    }

    /**
     * @throws Exception
     */
    public function moveEmployee($from, $to, $user_id) {
        $sql = '
            UPDATE users 
            SET department_id=?
            WHERE department_id=? AND id=?';

        $query = $this->db->query($sql, [$to, $from, $user_id]);

        if (!$query) {
            throw new Exception('Could not move employee');
        }
    }

    public function getUsers($id)
    {
        $sql = "
            SELECT u.*, d.name as department, d.parent
            FROM departments d
                RIGHT JOIN users u on d.id = u.department_id
            WHERE d.id = ? ";
        $query = $this->db->query($sql, $id);

        return $query->getResult();
    }
}
