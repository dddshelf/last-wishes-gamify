<?php

namespace Lw\Gamification\Command;

use Lw\Gamification\DomainModel\User\User;
use Lw\Gamification\DomainModel\User\UserId;
use Lw\Gamification\DomainModel\User\UserRepository;

class SignupCommandHandler
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(SignupCommand $command)
    {
        $user = User::signup(
            UserId::fromString($command->id())
        );

        $this->userRepository->save($user);
    }
}