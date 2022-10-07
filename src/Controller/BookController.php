<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Book;
use App\Entity\Sale;
use App\Entity\Users;
use App\Utils\Utils;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

#[Route('/book', name: 'app_book')]
class BookController extends AbstractController
{
    private $em;
    private $required;
    private $util;

    public function __construct(ManagerRegistry $em, Utils $util){
        $this->em = $em;
        $this->required = ["name", "author", "edition", "quantity", "price"];
        $this->util = $util;
    }

    #[Route('/', name: 'find_all_books', methods:['GET'])]
    public function findAllBooks(): JsonResponse
    {
       
        $books = $this->em->getRepository(Book::class)->findAllBooks();
        
        $result = $this->util->listAll($books);

        if(empty($result)){
            $result = "No hay libros disponibles";
        }

        return $this->json(
            $result
        );
    }

    #[Route('/{bookId}', name: 'find_one_book', methods:['GET'])]
    public function findOneBook($bookId): JsonResponse
    {
       
        $book = $this->em->getRepository(Book::class)->findOneBy(['id' => $bookId]);
       
        if(empty($book)){
            $result = "Libro no disponible";
        }
        else{
            $result = ['name' => $book->getName(),
                    'author' => $book->getAuthor(),
                    'edition' => $book->getEdition(),
                    'quantity' => $book->getQuantity(),
                    'price' => $book->getPrice() ];
        }

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
        
        $isRequired = $this->util->validateRequiredAttributes($this->required, $params);

        if (!empty($isRequired)){
            $result = $isRequired . " son requeridos";
        }
        else{
            $book = new Book();
            $book->setName($params['name']);
            $book->setAuthor($params['author']);
            $book->setEdition($params['edition']);
            $book->setCreateAt(new DateTime());
            $book->setQuantity($params['quantity']);
            $book->setPrice($params['price']);

            $this->em->getRepository(Book::class)->save($book, true);

            $result = "libro guardado con exito";
        }
        
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
        
        $isRequired = $this->util->validateRequiredAttributes($this->required, $params);
        $book = $this->em->getRepository(Book::class)->findOneBy(['id' => $bookId]);

        if (!empty($isRequired)){
            $result = $isRequired . " son requeridos";
        }
        else if(empty($book)){
            $result = "Libro no disponible";
        }
        else{
            $book->setName($params['name']);
            $book->setAuthor($params['author']);
            $book->setEdition($params['edition']);
            $book->setCreateAt(new DateTime());
            $book->setQuantity($params['quantity']);
            $book->setPrice($params['price']);

            $this->em->getRepository(Book::class)->save($book, true);

            $result = "libro editado con exito";
        }
        
        return $this->json(
            $result
        );
    }
    
}
