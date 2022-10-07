<?php

namespace App\Services;

use App\Entity\Users;
use App\Utils\Utils;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;

class UsersImpl implements IUsers{

    private $em;
    private $required;
    private $util;

    public function __construct(ManagerRegistry $em, Utils $util){
        $this->em = $em;
        $this->required = ["name", "lastname", "address", "phone", "sex", "roles", "birthdayDate"];
        $this->util = $util;
    }
    public function findAllUsers(){

        $users = $this->em->getRepository(Users::class)->findAllUsers();
        $result = $this->util->listAll($users);

        if(empty($result)){
            $result = "No hay usuarios disponibles";
        }
        return $result;
    }

    public function saveUser($params){
        $isRequired = $this->util->validateRequiredAttributes($this->required, $params);
        if (!empty($isRequired)){
            $result = $isRequired . " son requeridos";
        }
        else{
            $user = new Users();
            $user->setName($params['name']);
            $user->setLastname($params['lastname']);
            $user->setAddress($params['address']);
            $user->setPhone($params['phone']);
            $user->setSex($params['sex']);
            $user->setRoles($params['roles']);
            $user->setBirthdayDate(date_create($params['birthdayDate']));
            $user->setCreateAt(new DateTime());

            $this->em->getRepository(Users::class)->save($user, true);
            $result = "Usuario guardado con exito";
        }

        return $result;
    }
}