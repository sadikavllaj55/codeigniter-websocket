<?php

namespace App\Controllers;

use App\Models\UserModel;

class RegisterController extends BaseController {
    public function index() {
        return view('auth/register', ['validation' => []]);
    }

    public function register() {
        $user_name = $this->request->getPost('user_name');
        $email = $this->request->getPost('email_address');
        $rules = [
            'user_name' => 'required|min_length[3]',
            'email_address' => 'required|valid_email',
            'user_password' => 'required|min_length[8]',
            'passconf' => 'required|matches[user_password]'
        ];

        if ($this->validate($rules)) {
            helper('text');
            $model = new UserModel();
            $verification_key = random_string('alnum', 32);
            $data = [
                $user_name,
                $email,
                password_hash($this->request->getPost('user_password'), PASSWORD_DEFAULT),
                $verification_key
            ];
            $id = $model->new($data);
            if ($id > 0) {
                $this->verificationEmail($user_name, $verification_key, $email); // Make the email ready
                if ($this->email->send()) {
                    $this->session->setFlashdata('success', 'Check in your email for email verification.');
                }

                return redirect()->to('/login');
            } else if ($id === -1) { // -1 means there was an error but it was handled
                return view('auth/register', ['validation' => []]);
            } else {
                $this->session->setFlashdata('error', 'Could not finish the registration.');
                return view('auth/register', ['validation' => []]);
            }
        } else {
            return view('auth/register', ['validation' => $this->validator->getErrors()]);
        }
    }

    /**
     * @throws \Exception
     */
    public function verifyEmail($token) {
        $email = $this->request->getGet('email');
        $model = new UserModel();
        if ($model->verifyEmail($email, $token)) {
            $this->session->setFlashdata('success', 'Your email has been succesfully verified.<br>Now you can login from <a href="' . base_url() . '/login">here</a>');
        } else {
            $this->session->setFlashdata('danger', 'The activation link is invalid.');
        }
        return redirect()->to('/register');
    }

    public function sendVerification() {
        $email = $this->request->getGet('email');

        if ($this->validate(['email' => 'required|valid_email'])) {
            $model = new UserModel();
            $user = $model->getByEmail($email);

            if (!$user) {
                $this->session->setFlashdata('message', 'incorrect email. Account does not exist.');
            }

            $this->verificationEmail($user->name, $user->verification_key, $email);

            if ($this->email->send()) {
                $this->session->setFlashdata('message', 'Check in your email for email verification');
            }

            return redirect()->to('/login');

        } else {
            return view('auth/register', ['validation' => $this->validator->getErrors()]);
        }
    }

    private function verificationEmail($name, $key, $recipient) {
        $subject = "Igniter account email activation";
        $message = "<h3>Hi $name</h3>
        <p>This is an email verification from the Igniter Register system. To complate the register process
         you need to click this <a href=\"" . base_url() . "/register/verify/$key?email=$recipient" . "\">link</a>.</p>
        <p>Once you click this link your email will be verified and you can login.php into system.</p>
        <p>Thanks,<br>Igniter Team</p>";

        $this->email->setFrom("noreply@igniter.web");
        $this->email->setTo($recipient);

        $this->email->setSubject($subject);
        $this->email->setMessage($message);
    }
}
