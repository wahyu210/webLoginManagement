<?php   
namespace MochamadWahyu\Phpmvc\MiddleWare;


use MochamadWahyu\Phpmvc\Repository\SessionRepository;
use MochamadWahyu\Phpmvc\Repository\UserRepository;
use MochamadWahyu\Phpmvc\Config\Database;
use PHPUnit\Framework\TestCase;

class MustLoginMiddlewareTest extends TestCase
{
    private MustLoginMiddleware $mustLoginMiddleware;
    private UserRepository $userRepository;
    private SessionRepository $sessionRepository;

    protected function setUp(): void
    {
        $this->mustLoginMiddleware = new MustLoginMiddleware();
        putenv("mode=test");

        $this->userRepository = new UserRepository(Database::getConnection());
        $this->sessionRepository = new SessionRepository(Database::getConnection());

    }
    
    public function testBeforeGuess(){
        $this->mustLoginMiddleware->before();
        $this->expectOutputString('[Location : /users/login]');
    }
     public function testBeforeLoginUser(){
     }
}