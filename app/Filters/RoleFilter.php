<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    /**
     * @inheritDoc
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $user_role = strtolower(session()->get('user')->role);
        $allowed_roles = $arguments;

        if (!in_array($user_role, $allowed_roles)) {
            session()->setFlashdata('warning', 'You are not authorized to view this page');
            return redirect()->back();
        }
    }

    /**
     * @inheritDoc
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // TODO: Implement after() method.
    }
}