<?php

namespace App\Controllers;
use App\Models\Department;
use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class DepartmentsController extends BaseController
{
    /**
     * @throws \Exception
     */
    public function showDepartments() {
        $term = $this->request->getGet('term') ?? '';

        $model = new Department();
        $departments = $model->getDepartments();

        $dept_indexed = [];
        $dept_tree = [];

        foreach ($departments as $department) { // Index by id
            $dept_indexed[$department->id] = $department;
        }

        foreach ($dept_indexed as $department) {
            if ($department->parent === null) {
                $dept_tree[] = $department;
            } else {
                $parent = $dept_indexed[$department->parent];
                if (isset($parent->children)) {
                    $parent->children[] = $department;
                } else {
                    $parent->children = [$department];
                }
            }
        }

        $dept_html = $this->printDepartmentTree($dept_tree);

        return view('departments/index', compact('departments', 'term', 'dept_tree', 'dept_html'));
    }

    /**
     * @throws \Exception
     */
    public function getDepartment($id) {
        $model = new Department();

        $department = $model->getById($id);

        if ($department === false) {
            throw new PageNotFoundException('This department does not exist');
        }

        $departments = $model->getDepartments();
        $employees = $model->getUsers($id);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => true,
                'department' => $department,
                'employees' => $employees,
                'departments' => $departments
            ]);
        } else {
            return view('departments/show', compact('department', 'departments', 'employees'));
        }
    }

    /**
     * @throws \Exception
     */
    public function showCreate() {
        $model = new Department();
        $departments = $model->getDepartments();

        $dept_indexed = [];
        $dept_array = [];

        foreach ($departments as $department) { // Index by id
            $dept_indexed[$department->id] = $department;
        }

        foreach ($dept_indexed as $department) {
            if ($department->parent === null) {
                $dept_array[] = $department;
            } else {
                $parent = $dept_indexed[$department->parent];
                if (isset($parent->children)) {
                    $parent->children[] = $department;
                } else {
                    $parent->children = [$department];
                }
            }
        }

        $dept_tree = $this->printDepartmentTree($dept_array);

        $validation = [];

        return view('departments/new', compact('validation', 'departments', 'dept_tree'));
    }

    public function createDepartment() {
        $name = $this->request->getPost('name');
        $parent = $this->request->getPost('parent');
        $model = new Department();
        $departments = $model->getDepartments();

        $rules = [
            'name' => 'required|min_length[3]'
        ];

        if ($this->validate($rules)) {
            $department = $model->getByName($name);

            if ($department) {
                $this->validator->setError('name', 'A department already exists with this name.');
                return view('departments/new', ['validation' => $this->validator->getErrors(), 'departments' => $departments]);
            }

            if ($parent == '') {
                $parent = null;
            } else {
                $parent_dept = $model->getById($parent);

                if (!$parent_dept) {
                    $this->validator->setError('parent', 'The selected parent was invalid');
                    return view('departments/new', ['validation' => $this->validator->getErrors(), 'departments' => $departments]);
                }
            }

            try {
                $model->createDepartment(compact('name', 'parent'));
                $this->session->setFlashdata('success', 'Department was created.');
            } catch (\Exception $e) {
                $this->session->setFlashdata('danger', $e->getMessage());
            }

            return redirect()->back();
        } else {
            return view('departments/new', ['validation' => $this->validator->getErrors(), 'departments' => $departments]);
        }
    }

    public function editDepartment($id) {
        $model = new Department();

        $name = $this->request->getPost('name');
        $parent = $this->request->getPost('parent');

        if ($parent == '') {
            $parent = null;
        }

        $department = $model->getById($id);
        $dept_name = $model->getByName($name);

        if (!$department) {
            throw new PageNotFoundException('Department not found');
        }

        if ($dept_name && $dept_name->name !== $department->name) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'A department with this name already exists'
                ]);
            }
            $this->session->setFlashdata('error', 'A department with this name already exists');

            return redirect()->to('/dashboard/departments/' . $id);
        }

        if ($department->id == $parent) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'You can\'t select this department as parent of itself.'
                ]);
            }
            $this->session->setFlashdata('error', 'You can\'t select this department as parent of itself.');

            return redirect()->to('/dashboard/departments/' . $id);
        }


        try {
            $model->editDepartment($id, compact('name', 'parent'));

            if ($this->request->isAJAX()) {
                $department = $model->getById($id);

                return $this->response->setJSON([
                    'status' => true,
                    'department' => $department,
                    'message' => 'The department was changed successfully.'
                ]);
            }

            $this->session->setFlashdata('success', 'The department was changed successfully.');

            return redirect()->to('/dashboard/departments/' . $id);
        } catch (\Exception $e) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Could not edit this department'
                ]);
            }

            $this->session->setFlashdata('danger', 'Could not edit this department');

            return redirect()->to('/dashboard/departments/' . $id);
        }
    }

    public function deleteDepartment($id) {
        $model = new Department();

        $department = $model->getById($id);

        if (!$department) {
            throw new PageNotFoundException('Department not found');
        }

        try {
            $model->deleteDepartment($id);

            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['status' => true, 'message' => 'Department was deleted.']);
            } else {
                $this->session->setFlashdata('success', 'Department was deleted.');
                return redirect()->back();
            }

        } catch (\Exception $e) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['status' => false, 'message' => $e->getMessage()]);
            }else {
                $this->session->setFlashdata('danger', $e->getMessage());
                return redirect()->back();
            }
        }
    }

    public function removeEmployee($id, $user_id) {
        $model = new Department();
        $user_model = new UserModel();

        $department = $model->getById($id);
        $employee = $user_model->getById($user_id);

        if (!$department) {
            throw new PageNotFoundException('Department not found');
        }

        if (!$employee) {
            throw new PageNotFoundException('User not found');
        }

        try {
            $model->removeEmployee($id, $user_id);
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => true,
                    'message' => "Employee {$employee->name} was removed from this department"
                ]);
            }
        } catch (\Exception $e) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => $e->getMessage()
                ]);
            }
        }
    }

    public function moveEmployee($id, $user_id) {
        $model = new Department();
        $user_model = new UserModel();
        $to = $this->request->getPost('to');

        $department = $model->getById($id);
        $new_department = $model->getById($to);
        $employee = $user_model->getById($user_id);

        if (!$department || !$new_department) {
            throw new PageNotFoundException('Department not found');
        }

        if (!$employee) {
            throw new PageNotFoundException('User not found');
        }

        try {
            $model->moveEmployee($id, $to, $user_id);
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => true,
                    'message' => "Employee {$employee->name} was transfered to <strong>{$new_department->name}</strong>"
                ]);
            }
        } catch (\Exception $e) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => $e->getMessage()
                ]);
            }
        }
    }

    private function printDepartmentTree(array $departments) {
        $out = '<ul style="list-style-type: none">';

        foreach ($departments as $dept) {
            if (property_exists($dept, 'children')) {
                $out .= '<li><a href="/dashboard/departments/' . $dept->id . '">' . $dept->name . ' (' . $dept->employees . ')</a>';
                $out .= $this->printDepartmentTree($dept->children) . '</li>';
            } else {
                $out .= '<li><a href="/dashboard/departments/' . $dept->id . '">' . $dept->name . ' (' . $dept->employees . ')</a></li>';
            }
        }
        $out .= '</ul>';
        return  $out;
    }
}