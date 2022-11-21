<?php

namespace App\Controllers;

use App\Models\UserModel;

class LoginController extends BaseController {

    public function index() {
        return view('auth/login', ['validation' => []]);
    }

    public function login() {
        $email = $this->request->getVar('user_email');
        $password = $this->request->getVar('user_password');

        $rules = [
            'user_email' => 'required|valid_email',
            'user_password' => 'required|min_length[8]',
        ];

        if ($this->validate($rules)) {
            $model = new UserModel();
            $result = $model->canLogin($email, $password);

            if ($result === true) {
                return redirect()->to('dashboard');
            } else {
                $this->session->setFlashdata('error', $result);
                return view('auth/login', ['validation' => []]);
            }
        } else {
            return view('auth/login', ['validation' => $this->validator->getErrors()]);
        }
    }

    public function logout() {
        $this->session->destroy();
        return redirect()->to('/login');
    }

    /**
     * @return string
     */
    public function showPasswordForgot() {
        return view('auth/password_forgot', ['validation' => []]);
    }

    /**
     * @throws \Exception
     */
    public function passwordForgot() {
        $email = $this->request->getPost('email');

        $rules = [
            'email' => 'required|valid_email'
        ];

        if ($this->validate($rules)) {
            $model = new UserModel();
            $user = $model->getByEmail($email);

            if (!$user) {
                $this->validator->setError('email', 'That account does not exist!');
                return view('auth/password_forgot', ['validation' => $this->validator->getErrors()]);
            }

            $token = $model->newPasswordReset($email);
            $this->resetEmail($token, $email);

            if (!$this->email->send()) {
                $this->session->setFlashdata('error', 'Something went wrong. Could not send the email.');
            } else {
                $this->session->setFlashdata('success', 'An email was sent to you with the reset instructions.');
            }

            return redirect()->to('login');
        } else {
            return view('auth/password_forgot', ['validation' => $this->validator->getErrors()]);
        }
    }

    public function showPasswordReset($token) {
        $email = $this->request->getGet('email');
        $validation = [];

        return view('auth/password_reset', compact('token', 'email', 'validation'));
    }

    public function passwordReset($token) {
        $email = $this->request->getGet('email');
        $password = $this->request->getPost('password');

        $rules = [
            'user_email' => 'required|valid_email',
            'password' => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]'
        ];

        try {
            if ($this->validate($rules)) {
                $model = new UserModel();
                $model->resetPassword($email, $token, $password);

                $this->session->setFlashdata('success', 'The password was changed successfully.');
                return redirect()->to('/login');
            } else {
                $validation = $this->validator->getErrors();
                return view('auth/password_reset', compact('token', 'email', 'validation'));
            }
        } catch (\Exception $e) {
            $validation = [];
            $this->session->setFlashdata('error', $e->getMessage());
            return view('auth/password_reset', compact('token', 'email', 'validation'));
        }
    }

    private function resetEmail($token, $recipient) {
        $subject = "Igniter account password reset request";
        $message = "<h3>Hi</h3>
        <p>This email comes from the Igniter system because you sent a password reset request. To complate this
         request you can click on this <a href=\"" . base_url() . "/password/reset/$token?email=$recipient" . "\">link</a>.</p>
        <p>Please follow the instructions to finish reseeting your password.</p>
        <p>Thanks,<br>Igniter Team</p>";

        $this->email->setFrom("noreply@igniter.web");
        $this->email->setTo($recipient);

        $this->email->setSubject($subject);
        $this->email->setMessage($message);
    }
}
