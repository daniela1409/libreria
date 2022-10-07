<?php

namespace App\Services;

use App\Entity\Book;
use App\Entity\ItemSale;
use App\Entity\Sale;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;

class service
{
    
    public function createItemBook($books, ManagerRegistry $em, $quantity, Sale $sale)
    {

        foreach($books as $book){
            $book = $em->getRepository(Book::class)->findOneBy(['id' => $book]);
            $itemSale = new ItemSale();
            $itemSale->setBook($book);
            $itemSale->setQuantity($quantity);
            $itemSale->setSale($sale);
            $em->getRepository(ItemSale::class)->save($itemSale, true);
        }
        
    }
}