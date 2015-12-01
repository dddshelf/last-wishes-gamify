<?php

namespace Gamify\Gamification\DomainModel\User;

interface UserRepository
{
    /**
     * Finds a User by ID
     *
     * @param UserId $id
     *
     * @return User
     */
    public function byId(UserId $id);

    /**
     * Saves a user to the underlying datastore
     *
     * @param User $user
     *
     * @return void
     */
    public function save(User $user);

    /**
     * Generates a new UserId
     *
     * @return UserId
     */
    public function nextIdentity();
}