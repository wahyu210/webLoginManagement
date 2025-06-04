<?php
namespace MochamadWahyu\Phpmvc\Service;

use Cassandra\Exception\ValidationException;
use MochamadWahyu\Phpmvc\Config\Database;
use MochamadWahyu\Phpmvc\Domain\User;
use MochamadWahyu\Phpmvc\Model\UserLoginRequest;
use MochamadWahyu\Phpmvc\Model\UserProfileUpdateRequest;
use MochamadWahyu\Phpmvc\Model\UserRegisterRequest;
use MochamadWahyu\Phpmvc\Repository\SessionRepository;
use MochamadWahyu\Phpmvc\Repository\UserRepository;
// use MochamadWahyu\Phpmvc\Repository\UserRepositoryTest;

use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertTrue;

class UserServiceTest extends TestCase
{
    private UserService $userService;
    private UserRepository $userRepository;
    private SessionRepository $sessionRepository;
    protected function setUp() : void
    {
        $connection = Database::getConnection();
        $this->userRepository = new UserRepository($connection);
        $this->userService = new UserService($this->userRepository);
        $this->sessionRepository = new SessionRepository($connection);

        $this->sessionRepository->deleteAll();
        $this->userRepository->deleteAll();


    }

    public function testRegisterSuccess()
    {
        $request = new UserRegisterRequest();
        $request->id = 'wahyu';
        $request->name = 'wahyu';
        $request->password = 'rahasia';
        $response = $this->userService->register($request);

        self::assertEquals($request->id, $response->user->id);
        self::assertEquals($request->name, $response->user->name);
        self::assertNotEquals($request->password, $response->user->password);
        self::assertTrue(password_verify($request->password, $response->user->password));
    }
    public function testRegisterFailed()
    {
        $this->expectException(ValidationException::class);

        $request = new UserRegisterRequest();
        $request->id = "";
        $request->name = "";
        $request->password = "";
        $this->userService->register($request);

    }
    public function testRegisterduplicate()
    {
        $user = new User();
        $user->id = "wahyu";
        $user->name ="1234";
        $user->password = 'rahasia';
        $this->userRepository->save($user);
        
        $this->expectException(ValidationException::class);
        
        $request = new UserRegisterRequest();
        $request->id = "wahyu";
        $request->name = "1234";
        $request->password = 'rahasia';
        $this->userService->register($request);
    }
    public function testLoginNotFound(){
        $this->expectException(ValidationException::class);
        $request = new UserLoginRequest();
        $request->id = 'wahyu';
        $request->password = 'wahyu';
        $this->userService->login($request);
    }
    public function testLoginWrongPassword(){
        $user = new User();
        $user->id = 'wahyu';
        $user->name = 'Wahyu';
        $user->password = password_hash('wahyu', PASSWORD_BCRYPT);
     
     
        $this->expectException(ValidationException::class);
        $request = new UserLoginRequest();
        $request->id = 'wahyu';
        $request->password = 'salah';
        $this->userService->login($request);
    }
    public function testLoginSuccess(){
        $user = new User();
        $user->id = 'wahyu';
        $user->name = 'Wahyu';
        $user->password = password_hash('wahyu', PASSWORD_BCRYPT);


        $this->expectException(ValidationException::class);
        $request = new UserLoginRequest();
        $request->id = 'wahyu';
        $request->password = 'wahyu';
       $response= $this->userService->login($request);

        self::assertEquals($request->id, $user->id);
        // self::assertEquals($request->password, $user->password);
        self::assertTrue(password_verify($request->password, $user->password));
    }


    public function testUpdateSuccess()
{
        $user = new User();
        $user->id = 'wahyu';
        $user->name = 'Wahyu';
        $user->password = password_hash('wahyu', PASSWORD_BCRYPT);
        $this->userRepository->save($user);


        $request = new UserProfileUpdateRequest();
        $request->id = 'wahyu';
        $request->name = 'budi';
        $this->userService->updateProfile($request);
        $result = $this->userRepository->findById($user->id);
        self::assertEquals($request->name, $request->name);

    }

public function testUpdateValidationError(){

        $this->expectException(ValidationException::class);
    $request = new UserProfileUpdateRequest();
    $request->id = '';
    $request->name = '';
    $this->userService->updateProfile($request);


}

public function testUpdateNotFound(){

    $this->expectException(ValidationException::class);
    $request = new UserProfileUpdateRequest();
    $request->id = 'p21';
    $request->name = 'budi';
    $this->userService->updateProfile($request);
}

public function testUpdatepasswordSuccess(){
    $user = new User();
    $user->id = 'wahyu';
    $user->name = 'Wahyu';
    $user->password = password_hash('wahyu', PASSWORD_BCRYPT);
    $this->userRepository->save($user);

    $request= new UserPasswordUpdateRequest();
    $request->id= 'wahyu';
    $request->oldPassword='wahyu';
    $request->newPassword='1234';
    $this->userRepository->updatePasssword($request);
    $result=$this->userRepository->findById($user->id);
    self::assertEquals(password_verify($request->newPassword, $result->password));
}
    public function testUpdatepasswordValidationError(){

        $this->expectException(ValidationException::class);
        $request= new UserPasswordUpdateRequest();
        $request->id= 'wahyu';
        $request->oldPassword='wahyu';
        $request->newPassword='1234';
        $this->userRepository->updatePasssword($request);

    }
    public function testUpdatePasswordWrongOldPassword(){

        $this->expectException(ValidationException::class);
        $user = new User();
        $user->id = 'wahyu';
        $user->name = 'Wahyu';
        $user->password = password_hash('wahyu', PASSWORD_BCRYPT);
        $this->userRepository->save($user);

        $request= new UserPasswordUpdateRequest();
        $request->id= 'wahyu';
        $request->oldPassword='salah';
        $request->newPassword='1234';
        $this->userRepository->updatePasssword($request);
        $result=$this->userRepository->findById($user->id);
    }
    public function testUpdatepasswordNotFound(){
        $this->expectException(ValidationException::class);

        $request= new UserPasswordUpdateRequest();
        $request->id= 'wahyu';
        $request->oldPassword='salah';
        $request->newPassword='1234';
        $this->userRepository->updatePasssword($request);
        $result=$this->userRepository->findById($user->id);
    }


}