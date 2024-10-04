<?php
namespace MochamadWahyu\Phpmvc\Service;

use MochamadWahyu\Phpmvc\Config\Database;
use MochamadWahyu\Phpmvc\Domain\Session;
use MochamadWahyu\Phpmvc\Domain\User;
use MochamadWahyu\Phpmvc\Repository\SessionRepository;
use MochamadWahyu\Phpmvc\Repository\UserRepository;
use PHPUnit\Framework\TestCase;
// use MochamadWahyu\Phpmvc\Service\SessionService;

function setcookie(string $name, string $value)
{
    echo "$name: $value";
}

class SessionServiceTest extends TestCase
{

    private SessionService $sessionService;
    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;

    public function setUp() : void
    {
        $this->sessionRepository = new SessionRepository(Database::getConnection());
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->sessionService = new SessionService($this->sessionRepository, $this->userRepository);
        $this->sessionRepository->deleteAll();
        $this->userRepository->deleteAll();


        $user = new User();
        $user->id = 'wahyu';
        $user->name = 'Wahyu';
        $user->password = 'rahasia';
        $this->userRepository->save($user);
    }
    public function testCreate()
    {
        $user = new User();
        $session = $this->sessionService->create('wahyu');
        $this->expectOutputRegex("[X-MCD-SESSION: $session->id]");

        $result = $this->sessionRepository->findByID($session->id);
        self::assertEquals('wahyu', $result->userId);

    }
    public function testDestroy()
    {
        $session = new Session();
        $session->id = uniqid();
        $session->userId = 'wahyu';
        $this->sessionRepository->save($session);
        $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;
        $this->sessionService->destroy();
        self::expectOutputRegex("[X-MCD-SESSION: ]");
        $result = $this->sessionRepository->findByID($session->id);
        self::assertNull($result);
    }

    public function testCurrent()
    {
        $session = new Session();
        $session->id = uniqid();
        $session->userId = 'wahyu';
        $this->sessionRepository->save($session);
        $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;
        $user=$this->sessionService->current();
        self::assertEquals($session->userId, $user->id);
    }
   
}