<?php   
namespace MochamadWahyu\Phpmvc\Controller;

use MochamadWahyu\Phpmvc\Config\Database;
use MochamadWahyu\Phpmvc\Domain\Session;
use MochamadWahyu\Phpmvc\Domain\User;
use MochamadWahyu\Phpmvc\Repository\SessionRepository;
use MochamadWahyu\Phpmvc\Repository\UserRepository;
use MochamadWahyu\Phpmvc\Service\SessionService;
use PHPUnit\Framework\TestCase;

class HomeControllerTest extends TestCase
{
    private HomeController $homeController;
    private UserRepository $userRepository;
    private SessionRepository $sessionRepository;   

    protected function setUp(): void
    {
        $this->homeController = new HomeController();
   
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->sessionRepository = new SessionRepository(Database::getConnection());
        $this->sessionRepository->deleteAll();
        $this->userRepository->deleteAll();
    }
    
   public function testGuest()
   {

        $this->homeController->index();
        $this->expectOutputRegex('[Login Management]');

    } 
public function testUserLogin(){

        $user = new User();
        $user->id = 'wahyu';
        $user->name = 'Wahyu';
        $user->password = '123';
        $this->userRepository->save($user);

        $session = new Session();
        $session->id = uniqid();
        $session->userId = $user->id;
        $this->sessionRepository->save($session);
        $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

        $this->homeController->index();
        $this->expectOutputRegex('[Hello Wahyu]');
}          
}