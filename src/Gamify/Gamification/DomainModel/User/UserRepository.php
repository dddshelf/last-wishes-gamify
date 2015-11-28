<?php

namespace Gamify\Gamification\DomainModel\User;

interface UserRepository
{
    public function save(User $user);
}