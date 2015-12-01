<?php

namespace Gamify\Gamification\Command;

use Gamify\Gamification\DomainModel\User\User;
use Gamify\Gamification\DomainModel\User\UserId;
use Gamify\Gamification\DomainModel\User\UserRepository;

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