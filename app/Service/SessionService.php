<?php
namespace MochamadWahyu\Phpmvc\Service;

use MochamadWahyu\Phpmvc\Domain\User;

use MochamadWahyu\Phpmvc\Domain\Session;
use MochamadWahyu\Phpmvc\Repository\SessionRepository;
use MochamadWahyu\Phpmvc\Repository\UserRepository;

class SessionService
{
    public static string $COOKIE_NAME = "X-MCD-SESSION";
    private UserRepository $userRepository;
    private SessionRepository $sessionRepository;
    public function __construct(SessionRepository $sessionRepository,UserRepository $userRepository){
        $this->sessionRepository=$sessionRepository;
        $this->userRepository = $userRepository;
    }
    
public function create(string $userId):Session{
        $session = new Session();
        $session->id = uniqid();
        $session->userId = $userId;
        $this->sessionRepository->save($session);
         setcookie(self::$COOKIE_NAME, $session->id, time() + (60 * 60 * 24 * 30), "/");
//        setcookie(self::$COOKIE_NAME, $session->id);
             return $session;

}
public function destroy(){
        $sessionId = $_COOKIE[self::$COOKIE_NAME] ?? '';
        $this->sessionRepository->deleteById($sessionId);
        setcookie(self::$COOKIE_NAME, '', 1, "/");

    }
public function current():?User{
        $sessionId = $_COOKIE[self::$COOKIE_NAME] ?? '';
        $session=$this->sessionRepository->findByID($sessionId);
        if($session==null){
            return null;
        }
        return $this->userRepository->findById($session->userId);

}
}