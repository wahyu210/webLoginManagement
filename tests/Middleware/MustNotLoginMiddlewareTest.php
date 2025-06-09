<?php
namespace MochamadWahyu\Phpmvc\MiddleWare{

    use MochamadWahyu\Phpmvc\Config\Database;
    use PHPUnit\Framework\TestCase;
    use MochamadWahyu\Phpmvc\Domain\User;
    use MochamadWahyu\Phpmvc\Repository\UserRepository;
    use MochamadWahyu\Phpmvc\Repository\SessionRepository;
    use MochamadWahyu\Phpmvc\Domain\Session;
    use MochamadWahyu\Phpmvc\Service\SessionService;
    require_once __DIR__."/../Helper\helper.php";

class MustNotLoginMiddlewareTest extends TestCase
{
    private MustNotLoginMiddleware $middleware;
        private UserRepository $userRepository;
        private SessionRepository $sessionRepository;

    protected function setUp(): void
    {
        $this->middleware = new MustNotLoginMiddleware();
            putenv('mode=test');
            $this->userRepository= new UserRepository(Database::getConnection());
            $this->sessionRepository = new SessionRepository(Database::getConnection());

            $this->sessionRepository->deleteAll();
            $this->userRepository->deleteAll();
            
    }
    
    public function testBeforeGuest(){
         $this->middleware->before();
            $this->expectOutputString("");

    }
        public function testBeforeLoginUser()
        {
            $user = new User();
            $user->id = 'wahyu';
            $user->name = 'wahyu';
            $user->password = 'rahasia';

            $this->userRepository->save($user);
            $session = new Session();
            $session->id = uniqid();
            $session->userId = $user->id;
            $this->sessionRepository->save($session);
            $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

            $this->middleware->before();
            $this->expectOutputRegex("[Location: /]");

        }
    
}
}

