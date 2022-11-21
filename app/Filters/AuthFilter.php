<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface {

    /**
     * @inheritDoc
     */
    public function before(RequestInterface $request, $arguments = null) {
        if (!session()->has('user')) {
            return redirect()->to('/login');
        }
    }

    /**
     * @inheritDoc
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {
        // TODO: Implement after() method.
    }
}