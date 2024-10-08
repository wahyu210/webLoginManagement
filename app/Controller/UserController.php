<?php
namespace MochamadWahyu\Phpmvc\Controller;

use MochamadWahyu\Phpmvc\App\View;
use MochamadWahyu\Phpmvc\Config\Database;
use MochamadWahyu\Phpmvc\Exception\ValidationException;
use MochamadWahyu\Phpmvc\Model\UserLoginRequest;
use MochamadWahyu\Phpmvc\Model\UserRegisterRequest;
use MochamadWahyu\Phpmvc\Repository\SessionRepository;
use MochamadWahyu\Phpmvc\Repository\UserRepository;
use MochamadWahyu\Phpmvc\Service\SessionService;
use MochamadWahyu\Phpmvc\Service\UserService;

class UserController
{
    private UserService $userService;
    private SessionService $sessionService;
    public function __construct()
    {
        $connection = Database::getConnection();
        $userRepository = new UserRepository($connection);
        $this->userService = new UserService($userRepository);
        $sessionRepository = new SessionRepository($connection);
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
    }
    public function register(
    ) {
        View::render('User/register', [
            'title' => 'Register new User',
        ]);
    }

    public function postRegister()
    {

        $request = new UserRegisterRequest();
        $request->id = $_POST['id'];
        $request->name = $_POST['name'];
        $request->password = $_POST['password'];
        try {
            $this->userService->register($request);
            View::redirect('/users/login');
        } catch (ValidationException $exception) {
            View::render('User/register', [
                'title' => 'Register new User',
                'error' => $exception->getMessage(),
            ]);
        }

    }
    public function login()
    {
        View::render('User/login', [
            'title' => 'Login user',
        ]);

    }
    public function postLogin()
    {
        $request = new UserLoginRequest();
        $request->id = $_POST['id'];
        $request->password = $_POST['password'];

        try {
            $response = $this->userService->login($request);

            $this->sessionService->create($response->user->id);
            View::redirect('/');

        } catch (ValidationException $exception) {
            View::render('User/login', [
                'title' => 'Login User',
                'error' => $exception->getMessage(),
            ]);
        }

    }
    public function logout(){
        $this->sessionService->destroy();
        View::redirect('/');
    }
}