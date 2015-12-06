<?php

namespace Lw\Gamification\Command;

use Lw\Gamification\DomainModel\User\UserId;
use Lw\Gamification\DomainModel\User\UserRepository;

class RewardUserCommandHandler
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(RewardUserCommand $command)
    {
        $user = $this->userRepository->byId(
            UserId::fromString($command->userId())
        );

        $user->earnPoints($command->points());

        $this->userRepository->save($user);
    }
}