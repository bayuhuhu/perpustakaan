<?php

namespace App\Controllers\Users;

use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\Shield\Authentication\Passwords;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;

class UsersController extends ResourceController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel;
    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $itemPerPage = 20;

        $users = $this->userModel->withIdentities()->paginate($itemPerPage, 'users');

        $data = [
            'users'             => $users,
            'pager'             => $this->userModel->pager,
            'currentPage'       => $this->request->getVar('page_users') ?? 1,
            'itemPerPage'       => $itemPerPage,
        ];

        return view('users/index', $data);
    }


    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        $user = $this->userModel->withIdentities()->find($id);

        if (empty($user)) {
            throw new PageNotFoundException('User not found');
        }

        $data = [
            'user'           => $user,
            'validation'     => \Config\Services::validation(),
        ];

        return view('users/edit', $data);
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $user = $this->userModel->withIdentities()->find($id);

        if (empty($user)) {
            throw new PageNotFoundException('User not found');
        }

        $username = $user->toArray()['username'];

        $usernameChanged = $username != $this->request->getVar('username');

        if (!$this->validate([
            'username'      => $usernameChanged ? 'required|string|is_unique[users.username]' : 'required|string',
            'email'         => 'required|valid_email|max_length[255]',
            'password' => [
                'label'  => 'Kata Sandi',
                'rules'  => 'permit_empty|min_length[12]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/]',
                'errors' => [
                    'min_length' => 'Kata Sandi harus memiliki minimal 12 karakter.',
                    'regex_match' => 'Kata Sandi harus mengandung setidaknya 1 huruf kapital, 1 huruf kecil, 1 angka, dan 1 karakter khusus.',
                ],
            ],
            'password_confirm' => [
                'label' => 'Konfirmasi Kata Sandi',
                'rules' => 'permit_empty|matches[password]',
                'errors' => [
                    'matches' => 'Konfirmasi Kata Sandi harus sama dengan Kata Sandi.',
                ],
            ],
        ])) {
            $data = [
                'user'       => $user,
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
            ];

            return view('users/edit', $data);
        }

        if (!$this->userModel->save(new User([
            'id'       => $id,
            'username' => $this->request->getVar('username'),
            'email'    => $this->request->getVar('email'),
            'password' => $this->request->getVar('password') ?? null,
        ]))) {
            $data = [
                'user'     => $user,
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
            ];

            session()->setFlashdata(['msg' => 'Insert failed']);
            return view('users/create', $data);
        }

        session()->setFlashdata(['msg' => 'Update user successful']);
        return redirect()->to('admin/users');
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        $user = $this->userModel->where('id', $id)->first();

        if (empty($user)) {
            throw new PageNotFoundException('User not found');
        }

        if (!$this->userModel->delete($id)) {
            session()->setFlashdata(['msg' => 'Failed to delete user', 'error' => true]);
            return redirect()->back();
        }

        session()->setFlashdata(['msg' => 'User deleted successfully']);
        return redirect()->to('admin/users');
    }
    public function ubah_password()
    {
        // if (!setting('Auth.allowRegistration')) {
        //     return redirect()->back()->withInput()
        //         ->with('error', lang('Auth.registerDisabled'));
        // }

        // /** @var Session $authenticator */
        // $authenticator = auth('session')->getAuthenticator();

        // // If an action has been defined, start it up.
        // if ($authenticator->hasAction()) {
        //     return redirect()->route('auth-action-show');
        // }

        return view('users/ganti_pasword');
    }
}
