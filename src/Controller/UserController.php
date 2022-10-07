<?php

namespace App\Controller;

use App\Services\UsersImpl;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user', name: 'app_user')]
class UserController extends AbstractController
{

    private $serviceUsers;

    public function __construct(UsersImpl $serviceUsers){
        $this->serviceUsers = $serviceUsers;
    }

    #[Route('/', name: 'find_all_users', methods:['GET'])]
    public function findAllUSers(): JsonResponse
    {
        $result = $this->serviceUsers->findAllUSers();
        
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
        
        $result = $this->serviceUsers->saveUser($params);
        
        return $this->json(
            $result
        );
    }
}

