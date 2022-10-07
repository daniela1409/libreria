<?php

namespace App\Services;

interface IBook
{
    public function findAllBooks();
    public function findOneBook($bookId);
    public function saveBook($params);
    public function editBook($bookId, $params);
}