<?php
namespace MochamadWahyu\Phpmvc\App{
    function header(string $value)
    {
        echo $value;
    }

}
namespace MochamadWahyu\Phpmvc\Controller{

use MochamadWahyu\Phpmvc\Config\Database;
use MochamadWahyu\Phpmvc\Domain\User;
use MochamadWahyu\Phpmvc\Repository\UserRepository;
use PHPUnit\Framework\TestCase;
use MochamadWahyu\Phpmvc\app\View;

class UserControllerTest extends TestCase
{
    private UserController $userController;
    private UserRepository $userRepository;
    protected function setUp() : void
    {
        $this->userController = new UserController();
        $this->userRepository = new UserRepository(Database::getConnection());
        // $this->userController = new UserController($this->userRepository);

        $this->userRepository->deleteAll();
            putenv('mode=test');

        }
    public function testRegister()
    {
        $this->userController->register();
        $this->expectOutputRegex('[Register]');
        $this->expectOutputRegex('[id]');
        $this->expectOutputRegex('[name]');
        $this->expectOutputRegex('[Register new User]');

    }
    public function testPostRegisterSuccess()
    {
        $_POST['id']='wahyu1';
        $_POST['name']='wahyu';
            $_POST['password'] = '123';

            $this->userController->postRegister();
            $this->expectOutputRegex('[Location: /users/login]');
    }
    public function testRegisterValidationError()
    {
        $_POST['id'] = '';
        $_POST['name'] = 'wahyu';
        $_POST['password'] = '';
        $this->userController->postRegister();
        $this->expectOutputRegex('[Register]');
        $this->expectOutputRegex('[id]');
        $this->expectOutputRegex('[name]');
        $this->expectOutputRegex('[Register new User]');
        $this->expectOutputRegex('[Id, Name , Password can not blank]');



    }
    public function testRegisterDuplicate()
    {
        $user = new User();
        $user->id = 'wahyu';
        $user->name = 'wahyu';
        $user->password = '123';
        $this->userRepository->save($user);
        $_POST['id'] = 'wahyu';
        $_POST['name'] = 'wahyu';
        $_POST['password'] = '123';
        $this->userController->postRegister();
        $this->expectOutputRegex('[Register]');
        $this->expectOutputRegex('[id]');
        $this->expectOutputRegex('[name]');
        $this->expectOutputRegex('[Register new User]');
        $this->expectOutputRegex('[User already exists]');
    }
    public function testLogin()
    {
        $this->userController->login();
        $this->expectOutputRegex('[Login User]');
        $this->expectOutputRegex('[Id]');
        $this->expectOutputRegex('[Password]');

    }

    public function testLoginSuccess()
    {
        // echo "Starting testLoginSuccess\n";

        $user = new User();
        $user->id = 'wahyu1';
        $user->name = 'wahyu';
        $user->password = password_hash('123', PASSWORD_BCRYPT);
        $this->userRepository->save($user);

        $_POST['id'] = 'wahyu1';
        $_POST['password'] = '123';

        $this->userController->postLogin();
        $this->expectOutputRegex('[Location: /]');

        // $this->expectOutputRegex('');
        // $this->assertEquals('/', View::$lastRedirectUrl);


    }
    public function testLoginValidationError()
    {
            $_POST['id'] = '';
            
            $_POST['password'] = '';
            $this->userController->postLogin();
            $this->expectOutputRegex('[Login user]');
            $this->expectOutputRegex('[id]');
            $this->expectOutputRegex('[Password]');
            $this->expectOutputRegex('[Id, Name , Password can not blank]');
    }
    public function testLoginUserNotFound()
    {
            $_POST['id'] = 'wewds';

            $_POST['password'] = '2211';
            $this->userController->postLogin();
            $this->expectOutputRegex('[Login user]');
            $this->expectOutputRegex('[id]');
            $this->expectOutputRegex('[Password]');
            $this->expectOutputRegex('[id or password is wrong]');
    }

    public function testLoginUserWrongPassword()
    {
            $user = new User();
            $user->id = 'wahyu1';
            $user->name = 'wahyu';
            $user->password = password_hash('123', PASSWORD_BCRYPT);
            $this->userRepository->save($user);
            $_POST['id'] = 'wahyu';

            $_POST['password'] = '2211';
            $this->userController->postLogin();
            $this->expectOutputRegex('[Login user]');
            $this->expectOutputRegex('[id]');
            $this->expectOutputRegex('[Password]');
            $this->expectOutputRegex('[id or password is wrong]');
        }
}}