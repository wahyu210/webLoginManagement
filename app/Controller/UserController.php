<?php
namespace MochamadWahyu\Phpmvc\Controller;

use MochamadWahyu\Phpmvc\App\View;
use MochamadWahyu\Phpmvc\Config\Database;
use MochamadWahyu\Phpmvc\Exception\ValidationException;
use MochamadWahyu\Phpmvc\Model\UserLoginRequest;
use MochamadWahyu\Phpmvc\Model\UserProfileUpdateRequest;
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

    public function updateProfile()
    {
       $user= $this->sessionService->current();

        View::render('User/profile', [
            'title' => 'Update user profile',
            'userId'=> $user->id,
            'name'=> $user->name
        ]);
    }
    public function postUpdateProfile(){
        $user= $this->sessionService->current();
        $request=new UserProfileUpdateRequest();
        $request->id=$user->id;
        $request->name=$_POST['name'];
        try {
            $response= $this->userService->updateProfile($request);
            View::redirect('/');
        }catch (ValidationException $exception) {
            View::render('User/profile', [
                'title'=> 'Update user profile',
                'error'=>$exception->getMessage(),
                'user'=>[
                    'id'=> $user->id,
                    'name'=>$_POST['name'],
                ]
            ]);
        }

    }

    public function updatePassword(){
        $user= $this->sessionService->current();

        View::render('User/Password', [
            'title' => 'Update Password user profile',
            'userId'=> $user->id,
            'oldPassword'=> $user->password,

        ]);
    }
}