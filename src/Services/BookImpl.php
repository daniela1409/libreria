<?php

namespace App\Services;

use App\Entity\Book;
use App\Services\IBook;
use App\Utils\Utils;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use LDAP\Result;

class BookImpl implements IBook
{
    private $em;
    private $util;
    private $required;

    public function __construct(ManagerRegistry $em, Utils $util){
        $this->em = $em;
        $this->util = $util;
        $this->required = ["name", "author", "edition", "quantity", "price"];
    }

    public function findAllBooks(){
        $books = $this->em->getRepository(Book::class)->findAllBooks();
        
        $result = $this->util->listAll($books);

        if(empty($result)){
            $result = "No hay libros disponibles";
        }
        return $result;
    }

    public function findOneBook($bookId){

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
        return $result;
    }

    public function saveBook($params){
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
        return $result;
    }

    public function editBook($bookId, $params){

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

        return $result;
    }
}