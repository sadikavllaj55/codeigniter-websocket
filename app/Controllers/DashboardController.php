<?php

namespace App\Controllers;

use App\Models\UserModel;

class DashboardController extends BaseController
{
    public function index()
    {
        return view('dashboard/index');
    }

    public function showProfile()
    {
        return view('dashboard/profile', ['validation' => []]);
    }

    /**
     */
    public function editProfile()
    {
        $name = $this->request->getPost('name');
        $tel = $this->request->getPost('tel');
        $web = $this->request->getPost('web');
        $address = $this->request->getPost('address');
        $city = $this->request->getPost('city');
        $country = $this->request->getPost('country');
        $zip = $this->request->getPost('zip');
        $bio = htmlspecialchars($this->request->getPost('bio'));

        $profile = $this->session->get('user')->profile_image;

        $img = $this->request->getFile('profile');
        $validation = [];

        $rules = [
            'profile' => [
                'label' => 'profile picture',
                'rules' => 'is_image[profile]|max_size[profile,1024]|max_dims[profile,2000,2000]'
            ]
        ];

        if (!$this->validate($rules)) {
            $validation = $this->validator->getErrors();
            return view('dashboard/profile', compact('validation'));
        }

        if (!$img->hasMoved()) {
            if ($img->getError() === UPLOAD_ERR_OK) {
                $profile = 'uploads/' . $img->store();
                $old_profile = WRITEPATH . $this->session->get('user')->profile_image;

                if (file_exists($old_profile) && is_file($old_profile)) {
                    unlink($old_profile);
                }
            }
        } else {
            $this->session->setFlashdata('error', 'There was an error processing the profile image.');
            return view('dashboard/profile', compact('validation'));
        }

        $model = new UserModel();

        try {
            $model->editUser($this->session->get('user')->id, compact('name', 'profile', 'tel', 'web', 'address', 'city', 'country', 'zip', 'bio'));
            $user = $model->getByEmail($this->session->get('user')->email);
            $this->session->set('user', $user);
            $this->session->setFlashdata('success', 'Profile was changed');
        } catch (\Exception $e) {
            $this->session->setFlashdata('error', 'Could not edit the profile.');
        }

        return view('dashboard/profile', compact('validation'));
    }
}