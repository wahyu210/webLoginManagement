<?php
namespace MochamadWahyu\Phpmvc\MiddleWare;

use MochamadWahyu\Phpmvc\Config\Database;
use MochamadWahyu\Phpmvc\Repository\SessionRepository;
use MochamadWahyu\Phpmvc\Repository\UserRepository;
use MochamadWahyu\Phpmvc\Service\SessionService;
use MochamadWahyu\Phpmvc\App\View;

class MustNotLoginMiddleware implements Middleware
{

    private SessionService $sessionService;
    public function __construct()
    {
        $connection = Database::getConnection();
        $sessionRepository = new SessionRepository($connection);
        $userRepository = new UserRepository($connection);
        $this->sessionService = new SessionService($sessionRepository, $userRepository);

    }
    function before() : void
    {
        $user = $this->sessionService->current();
        if ($user != null) {
            View::redirect('/');
        }
    }
}