<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class UsersController extends BaseController
{
    public function showUsers()
    {
        $page = $this->request->getGet('page') ?? 1;
        $term = $this->request->getGet('term') ?? '';
        $model = new UserModel();
        $num_pages = $model->numPages($term);

        $links = [];

        if ($this->request->isAJAX()) {
            $users = $model->getAll($term);

            return $this->response->setJSON([
                'status' => true,
                'users' => $users
            ]);
        }

        for ($i = 1; $i <= $num_pages; $i++) {
            $base = base_url() . '/dashboard/users';

            $params = ['page' => $i];

            if (strlen($term) >= 3) {
                $params['term'] = $term;
            }
            $query = http_build_query($params);

            $links[$i] = $base . '?' . $query;
        }

        $users = $model->listUsers($term, $page);
        return view('users/index', compact('users', 'num_pages', 'page', 'term', 'links'));
    }

    public function getUser($id) {
        $model = new UserModel();
        $json = $this->request->getGet('json') ?? 0;

        $user = $model->getById($id);

        if (!$user) {
            throw new PageNotFoundException('User not found');
        } else {
            unset($user->verification_key);
            unset($user->email_verified);

            if ($this->request->isAJAX()) {
                if ($json) {
                    return $this->response->setJSON(['user' => $user]);
                }
                return view('users/edit', ['user' => $user]);
            } else {
                return view('users/show', ['user' => $user]);
            }
        }
    }

    /**
     * @throws \Exception
     */
    public function editUser($id) {
        $model = new UserModel();
        $user = $model->getById($id);
        $profile = $user->profile_image;

        $name = $this->request->getPost('name');
        $tel = $this->request->getPost('tel');
        $web = $this->request->getPost('web');
        $address = $this->request->getPost('address');
        $city = $this->request->getPost('city');
        $country = $this->request->getPost('country');
        $zip = $this->request->getPost('zip');
        $bio = $this->request->getPost('bio');

        if (!$user) {
            throw new PageNotFoundException('User not found');
        }

        try {
            $model->editUser($id, compact('name', 'profile', 'tel', 'web', 'address', 'city', 'country', 'zip', 'bio'));
            $user = $model->getById($id);

            unset($user->verification_key);
            unset($user->email_verified);

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => true,
                    'user' => $user
                ]);
            } else {
                $this->session->setFlashdata('success', 'Employee was updated.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Employee was not updated.' . $e->getMessage()
                ]);
            } else {
                $this->session->setFlashdata('danger', 'Employee was not updated.');
                return redirect()->back();
            }
        }
    }

    public function deleteUser($id) {
        $model = new UserModel();

        $user = $model->getById($id);

        if ($user->id == $this->session->get('user')->id) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'You can\'t delete yourself.'
            ]);
        }

        try {
            $model->deleteUser($id);

            return $this->response->setJSON([
                'status' => true,
                'message' => 'The user was deleted'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Deleting the user failed.'
            ]);
        }
    }
}