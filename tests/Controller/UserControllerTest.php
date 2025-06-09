<?php



namespace MochamadWahyu\Phpmvc\Controller {

    use MochamadWahyu\Phpmvc\Config\Database;
    use MochamadWahyu\Phpmvc\Domain\Session;
    use MochamadWahyu\Phpmvc\Domain\User;
    use MochamadWahyu\Phpmvc\Repository\UserRepository;
    use PHPUnit\Framework\TestCase;
    use MochamadWahyu\Phpmvc\app\View;
    use MochamadWahyu\Phpmvc\Repository\SessionRepository;
    use MochamadWahyu\Phpmvc\Service\SessionService;
    require_once __DIR__."/../Helper\helper.php";

    class UserControllerTest extends TestCase
    {
        private UserController $userController;
        private UserRepository $userRepository;
        private SessionRepository $sessionRepository;
        protected function setUp() : void
        {
            $this->userController = new UserController();
            $this->userRepository = new UserRepository(Database::getConnection());
            $this->sessionRepository = new SessionRepository(Database::getConnection());
            // $this->userController = new UserController($this->userRepository);
            $this->sessionRepository->deleteAll();
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
            $_POST['id'] = 'wahyu1';
            $_POST['name'] = 'wahyu';
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
            $this->expectOutputRegex('[X-MCD-SESSION]');
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
        public function testLogout()
        {
            $user = new User();
            $user->id = 'wahyu1';
            $user->name = 'wahyu';
            $user->password = password_hash('123', PASSWORD_BCRYPT);
            $this->userRepository->save($user);

            $session = new Session();
            $session->id = uniqid();
            $session->userId = $user->id;
            $this->sessionRepository->save($session);
            $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

            $this->userController->logout();
            $this->expectOutputRegex('[Location: /]');
            $this->expectOutputRegex('[X-MCD-SESSION]');
        }
         public  function testUpdateProfile()   {

             $user = new User();
             $user->id = 'wahyu1';
             $user->name = 'wahyu';
             $user->password = password_hash('123', PASSWORD_BCRYPT);
             $this->userRepository->save($user);

             $session = new Session();
             $session->id = uniqid();
             $session->userId = $user->id;
             $this->sessionRepository->save($session);
             $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

             $_POST['id'] = 'wahyu21';
             $this->userController->postUpdateProfile();
             $this->expectOutputRegex('[Profile');
             $this->expectOutputRegex('[Name]');
             $this->expectOutputRegex('[Id]');
             $this->expectOutputRegex('[wahyu1]');


         }
             public function testUpdateProfileSuccess(){
                 $user = new User();
                 $user->id = 'wahyu1';
                 $user->name = 'wahyu';
                 $user->password = password_hash('123', PASSWORD_BCRYPT);
                 $this->userRepository->save($user);

                 $session = new Session();
                 $session->id = uniqid();
                 $session->userId = $user->id;
                 $this->sessionRepository->save($session);
                 $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

                 $_POST['id'] = 'wahyu21';
                 $this->userController->postUpdateProfile();
                 $this->expectOutputRegex('[Location: /]');

                 $result=$this->userRepository->findById('wahyu1');
                 self::assertEquals('wahyu21', $result->name);
             }

             public function testUpdateProfileValidationError(){
                 $user = new User();
                 $user->id = 'wahyu1';
                 $user->name = 'wahyu';
                 $user->password = password_hash('123', PASSWORD_BCRYPT);
                 $this->userRepository->save($user);

                 $session = new Session();
                 $session->id = uniqid();
                 $session->userId = $user->id;
                 $this->sessionRepository->save($session);
                 $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

                 $_POST['id'] = '';
                 $this->userController->postUpdateProfile();
                 $this->expectOutputRegex('[Profile');
                 $this->expectOutputRegex('[Name]');
                 $this->expectOutputRegex('[Id]');
                 $this->expectOutputRegex('[]');
                 $this->expectOutputRegex('[Id, Name , Password can not blank]');
             }
        public function testUpdatePassword(){
            $user = new User();
            $user->id = 'wahyu1';
            $user->name = 'wahyu';
            $user->password = password_hash('123', PASSWORD_BCRYPT);
            $this->userRepository->save($user);

            $session = new Session();
            $session->id = uniqid();
            $session->userId = $user->id;
            $this->sessionRepository->save($session);
            $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

            $this->userRepository->updatePassword();

            $this->expectOutputRegex('[Password]');
            $this->expectOutputRegex('[id]');
            $this->expectOutputRegex('[wahyu]');

        }

             public function testPostUpdatePasswordSuccess(){
                 $user = new User();
                 $user->id = 'wahyu1';
                 $user->name = 'wahyu';
                 $user->password = password_hash('123', PASSWORD_BCRYPT);
                 $this->userRepository->save($user);

                 $session = new Session();
                 $session->id = uniqid();
                 $session->userId = $user->id;
                 $this->sessionRepository->save($session);
                 $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

                 $_POST['oldPassword'] = '123';
                 $_POST['newPassword'] = '1234';

                 $this->userController->postUpdatePassword();

                 $this->expectOutputRegex('[Location: /]');

                 $result=$this->userRepository->findById($user->id);
                 self::assertEquals(password_verify('1234', $result->password));
             }
        public function testPostUpdatePasswordValidationError(){
            $user = new User();
            $user->id = 'wahyu1';
            $user->name = 'wahyu';
            $user->password = password_hash('123', PASSWORD_BCRYPT);
            $this->userRepository->save($user);

            $session = new Session();
            $session->id = uniqid();
            $session->userId = $user->id;
            $this->sessionRepository->save($session);
            $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

            $_POST['oldPassword'] = '';
            $_POST['newPassword'] = '';

            $this->userController->postUpdatePassword();

            $this->expectOutputRegex('[Password]');
            $this->expectOutputRegex('[id]');
            $this->expectOutputRegex('[wahyu]');
            $this->expectOutputRegex('[Id, Name , Password can not blank]');

        }

        public function testPostUpdatePasswordWrongOldPassword(){
            $user = new User();
            $user->id = 'wahyu1';
            $user->name = 'wahyu';
            $user->password = password_hash('123', PASSWORD_BCRYPT);
            $this->userRepository->save($user);

            $session = new Session();
            $session->id = uniqid();
            $session->userId = $user->id;
            $this->sessionRepository->save($session);
            $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

            $_POST['oldPassword'] = '321';
            $_POST['newPassword'] = '2112';

            $this->userController->postUpdatePassword();

            $this->expectOutputRegex('[Password]');
            $this->expectOutputRegex('[id]');
            $this->expectOutputRegex('[wahyu]');
            $this->expectOutputRegex('[Id, Name , Password can not blank]');
            $this->expectOutputRegex('[old password is wrong]');
        }



    }

}