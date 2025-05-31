<?php
namespace MochamadWahyu\Phpmvc\Controller;
use MochamadWahyu\Phpmvc\App\View;
use MochamadWahyu\Phpmvc\Config\Database;
use MochamadWahyu\Phpmvc\Repository\SessionRepository;
use MochamadWahyu\Phpmvc\Repository\UserRepository;
use MochamadWahyu\Phpmvc\Service\SessionService;

class HomeController{

    private SessionService $sessionService;

    public function __construct(){
        $connection = Database::getConnection();
        $sessionRepository = new SessionRepository($connection);
        $userRepository = new UserRepository($connection);
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
    }
    function index(){
        $user = $this->sessionService->current();
        if($user==null){
            View::render('Home/index',[
            'title'=>'Belajar PHP MVC',
            'content']);
        }else {
            View::render('Home/Dashboard', model: [
                'title' => 'Dashboard',
                'user'=>['name' => $user->name]]);

        }
        // echo "HomeController.index()";
        
    }
   
}