<?php
namespace MochamadWahyu\Phpmvc\Repository;


// use MochamadWahyu\Phpmvc\Repository\UserRepository;
use MochamadWahyu\Phpmvc\Config\Database;
use MochamadWahyu\Phpmvc\Domain\User;
use PHPUnit\Framework\TestCase;


class UserRepositoryTest extends TestCase
{
    private UserRepository $userRepository;
    private SessionRepository $sessionRepository;
    protected function setUp(): void
    {
        $this->sessionRepository = new SessionRepository(database::getConnection());
        $this->sessionRepository->deleteAll();
        $this->userRepository = new UserRepository(Database::getConnection());

        $this->userRepository->deleteAll();

    }
    public function testSaveSuccess()
    {
        // var_dump($this->userRepository);

        $user = new User();
        $user->id = 'wahyu';
        $user->name = 'Wahyu';
        $user->password = 'rahasia';
        $this->userRepository->save($user);
        $result = $this->userRepository->findById($user->id);
        self::assertEquals($user->id, $result->id);
        self::assertEquals($user->name, $result->name);
        self::assertEquals($user->password, $result->password);
    }

    public function testFindByIdNotFound()
    {
        $user = $this->userRepository->findById('notfound');
        self::assertNull($user);
    }
    public function testUpdate()
    {

        $user = new User();
        $user->id = 'wahyu';
        $user->name = 'Wahyu';
        $user->password = 'rahasia';
        $this->userRepository->save($user);

        $user->name = 'budi';
        $this->userRepository->update($user);

        $result = $this->userRepository->findById($user->id);

        self::assertEquals($user->id, $result->id);
        self::assertEquals($user->name, $result->name);
        self::assertEquals($user->password, $result->password);
    }
}

