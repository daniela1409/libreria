<?php

namespace App\Services;

use App\Entity\ItemSale;
use App\Entity\Sale;
use App\Entity\Users;
use App\Utils\Utils;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Config\Framework\HttpClient\DefaultOptions\RetryFailedConfig;

class SaleImpl implements ISale{
    private $em;
    private $required;
    private $util;
    private $service;

    public function __construct(ManagerRegistry $em, Utils $util, service $service){
        $this->em = $em;
        $this->required = ['userId', 'books', 'quantity'];
        $this->util = $util;
        $this->service = $service;
    }

    public function findAllSales(){
        $sales = $this->em->getRepository(Sale::class)->findAllSales();
        
        $result = $this->util->listAll($sales);

        if(empty($result)){
            $result = "No hay ventas disponibles";
        }
        return $result;
    }
    public function findOneSale($saleId){
        $sale = $this->em->getRepository(Sale::class)->findOneBy(['id' => $saleId]);

        if(!empty($sale)){
            $itemSales = $this->em->getRepository(ItemSale::class)->findItemSale($sale);
            $result = ['fullname' => $sale->getUser()->getName() . ' ' . $sale->getUser()->getLastname(), 'items' => $itemSales];
        }
        else{
            $result = "Venta no encontrada";
        }
        return $result;
    }
    public function saveSale($params){
        $isRequired = $this->util->validateRequiredAttributes($this->required, $params);
        if (!empty($isRequired)){
            $result = $isRequired . " son requeridos";
        }
        else{
            $sale = new Sale();
            $user = $this->em->getRepository(Users::class)->findOneBy(['id' => $params['userId']]);
            
            $sale->setUser($user);
            $sale->setCreateAt(new DateTime());

            $this->em->getRepository(Sale::class)->save($sale, true);

            $this->service->createItemBook($params['books'], $this->em, $params['quantity'], $sale);

            $result = "Venta guardado con exito";
        }
        return $result;
    }
}