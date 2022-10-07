<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\BookImpl;
use Symfony\Component\HttpFoundation\Request;

#[Route('/book', name: 'app_book')]
class BookController extends AbstractController
{
    private $serviceBook;

    public function __construct(BookImpl $serviceBook){
        $this->serviceBook = $serviceBook;
    }

    #[Route('/', name: 'find_all_books', methods:['GET'])]
    public function findAllBooks(): JsonResponse
    {
        $result = $this->serviceBook->findAllBooks();

        return $this->json(
            $result
        );
    }

    #[Route('/{bookId}', name: 'find_one_book', methods:['GET'])]
    public function findOneBook($bookId): JsonResponse
    {
       
        $result = $this->serviceBook->findOneBook($bookId);

        return $this->json(
            $result
        );
    }

    #[Route('/', name: 'save_book', methods:['POST'])]
    public function saveBook(Request $request): JsonResponse
    {
        $content = $request->getContent();
        $result = "";

        if (!empty($content)) {
            $params = json_decode($content, true);
        }
        
        $result = $this->serviceBook->saveBook($params);
        
        return $this->json(
            $result
        );
    }

    #[Route('/{bookId}', name: 'edit_book', methods:['PUT'])]
    public function editBook(Request $request, $bookId): JsonResponse
    {
        $content = $request->getContent();
        $result = "";

        if (!empty($content)) {
            $params = json_decode($content, true);
        }
        
       $result = $this->serviceBook->editBook($bookId, $params);
        
        return $this->json(
            $result
        );
    }
    
}
