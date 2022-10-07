<?php

namespace App\Utils;

use App\Entity\Book;
use App\Entity\ItemSale;
use App\Entity\Sale;
use Doctrine\Persistence\ManagerRegistry;

class Utils
{
    public function validateRequiredAttributes($arrayRequired, $params){
        
        $result = "";
        foreach($arrayRequired as $required){
            if(!array_key_exists($required, $params)){
                $result = $result . $required . ", ";
            }
        }
        return $result;
    }

    public function listAll($array){
        $result = [];
        foreach($array as $value){
            array_push($result, $value);
        }
        return $result;
    }

}