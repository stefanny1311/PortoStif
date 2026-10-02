<?php
class User extends Model
{
    protected string $table = 'users';

    public function findByEmail(string $email): array|false
    {
        return $this->findBy('email', $email);
    }
}
