<?php
namespace MochamadWahyu\Phpmvc\Repository;

use MochamadWahyu\Phpmvc\Config\Database;
use MochamadWahyu\Phpmvc\Domain\Session;
use PHPUnit\Framework\TestCase;
use MochamadWahyu\Phpmvc\Domain\User;

class SessionRepositoryTest extends TestCase{

    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->sessionRepository = new SessionRepository(Database::getConnection());

        $this->sessionRepository->deleteAll();
        $this->userRepository->deleteAll();


        $user = new User();
        $user->id = 'wahyu';
        $user->name = 'Wahyu';
        $user->password = 'rahasia';
        $this->userRepository->save($user);
    }
    public function testSaveSuccess(){


        $session = new Session;
        $session->id = uniqid();
        $session->userId = 'wahyu';
        $this->sessionRepository->save($session);
        $result = $this->sessionRepository->findByID($session->id);

        self::assertEquals($session->id, $result->id);
        self::assertEquals($session->userId, $result->userId);
    }
    public function testDeleteByIdSuccess(){
        $session = new Session;
        $session->id = uniqid();
        $session->userId = 'wahyu';
        $this->sessionRepository->save($session);
        $result = $this->sessionRepository->findByID($session->id);

        self::assertEquals($session->id, $result->id);
        self::assertEquals($session->userId, $result->userId);

        $this->sessionRepository->deleteById($session->id);
        $result = $this->sessionRepository->findByID($session->id);

        self::assertNull($result);

    }
    public function testFindByIdNotFound(){

        $result = $this->sessionRepository->findByID('NotFound');

        self::assertNull($result);
    }
}