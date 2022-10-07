<?php

namespace App\Services;

interface IUsers
{
    public function findAllUsers();
    public function saveUser($params);
}
