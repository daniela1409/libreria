<?php

namespace App\Controller;

use App\Entity\Users;
use App\Utils\Utils;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user', name: 'app_user')]
class UserController extends AbstractController
{
    private $em;
    private $required;
    private $util;

    public function __construct(ManagerRegistry $em, Utils $util){
        $this->em = $em;
        $this->required = ["name", "lastname", "address", "phone", "sex", "roles", "birthdayDate"];
        $this->util = $util;
    }

    #[Route('/', name: 'find_all_users', methods:['GET'])]
    public function index(): JsonResponse
    {
        $users = $this->em->getRepository(Users::class)->findAllUsers();
        $result = $this->util->listAll($users);

        if(empty($result)){
            $result = "No hay usuarios disponibles";
        }

        return $this->json(
            $result
        );
    }

    #[Route('/', name: 'save_users', methods:['POST'])]
    public function saveUSers(Request $request): JsonResponse
    {
        $content = $request->getContent();
        $result = "";

        if (!empty($content)) {
            $params = json_decode($content, true);
        }
        
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
        
        return $this->json(
            $result
        );
    }
}

