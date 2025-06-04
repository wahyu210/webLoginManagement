<?php
namespace MochamadWahyu\Phpmvc\Service;

use Cassandra\Exception\ValidationException;
use MochamadWahyu\Phpmvc\Config\Database;
use MochamadWahyu\Phpmvc\Model\UserRegisterRequest;
use MochamadWahyu\Phpmvc\Domain\User;
use MochamadWahyu\Phpmvc\Exception\ValidationException;
use MochamadWahyu\Phpmvc\Model\UserLoginRequest;
use MochamadWahyu\Phpmvc\Model\UserLoginResponse;
use MochamadWahyu\Phpmvc\Model\UserProfileUpdateRequest;
use MochamadWahyu\Phpmvc\Model\UserProfileUpdateResponse;
use MochamadWahyu\Phpmvc\Repository\UserRepository;
use MochamadWahyu\Phpmvc\Model\UserRegisterResponse;

class UserService
{
    private UserRepository $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function register(UserRegisterRequest $request): UserRegisterResponse
    {
        $this->validateRegisrationRequest($request);
        try {
            Database::beginTransaction();
            $user = $this->userRepository->findById($request->id);
            if ($user != null) {
                throw new ValidationException('User already exists');
            }
            $user = new User();
            $user->id = $request->id;
            $user->name = $request->name;
            $user->password = password_hash($request->password, PASSWORD_BCRYPT);
            $this->userRepository->save($user);
            $response = new UserRegisterResponse;
            $response->user = $user;
            Database::commitTransaction();
            return $response;

        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }
    public function validateRegisrationRequest(UserRegisterRequest $request)
    {
        if ($request->id === null || $request->name === null || $request->password === null || trim($request->id === '') || trim($request->name === '') || trim($request->password === '')) {
            throw new ValidationException('Id, Name , Password can not blank');
        }
    }
    public function login(UserLoginRequest $request): UserLoginResponse
    {
        $this->validateUserLoginRequest($request);
        $user = $this->userRepository->findById($request->id);
        if ($user == null) {
            throw new ValidationException('id or password is wrong');
        }
        if (password_verify($request->password, $user->password)) {
            $response = new UserLoginResponse();
            $response->user = $user;
            return $response;
        } else {
            throw new ValidationException('id or password is wrong');
        }
    }

    private function validateUserLoginRequest(UserLoginRequest $request)
    {
        if ($request->id === null || $request->password === null || trim($request->id === '') || trim($request->password === '')) {
            throw new ValidationException('Id, Name , Password can not blank');
        }
    }
    public function updateProfile(UserProfileUpdateRequest $request): UserProfileUpdateResponse
    {
        $this->validateUserProfileRequest($request);

        try{
            database::beginTransaction();

            $user = $this->userRepository->findById($request->id);
            if($user==null){
                throw new ValidationException('user is not found');
            }

            $user->name = $request->name;
            $this->userRepository->update($user);


            database::commitTransaction();
            $response = new UserProfileUpdateResponse;
            $response->user = $user;
            return $response;
        }
        catch(\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }

    }
    private function validateUserProfileRequest(UserProfileUpdateRequest $request) {
        if ($request->id === null || $request->name === null || trim($request->id === '') || trim($request->name === '')) {
            throw new ValidationException('Id, Name , Password can not blank');
        }
    }
    public function updatePassword(UserPasswordUpdateRequest $request): UserPasswordUpdateResponse {
        $this->validateUserPasswordRequest($request);

        try {
            Database::beginTransaction();
            $user= $this->userRepository->findById($request->id);
            if($user==null){
                throw new ValidationException('user is not found');
            }
            if(!password_verify($request->password, $user->password)){
                throw new ValidationException('password is wrong');
            }
            $user->password = password_hash($request->password, PASSWORD_BCRYPT);
            $this->userRepository->update($user);
            database::commit();
            $response= new UserPasswordUpdateResponse();
            $response->user = $user;
            return $response;
        }catch(\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }

    }


public function validateUpdatePasswordRequest(UserPasswordUpdateRequest $request){
    if ($request->id === null || $request->oldPassword === null || $request->newPassword === null ||trim($request->id === '') || trim($request->oldPassword === ''|| trim($request->newPassword === '') {
        throw new ValidationException('Id, oldPassword , newPassword can not blank');
    }
}
}
