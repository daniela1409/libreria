<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\ItemSale;
use App\Entity\Sale;
use App\Entity\Users;
use App\Services\service;
use App\Utils\Utils;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

#[Route('/sale', name: 'app_sale')]
class SaleController extends AbstractController
{
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

    #[Route('/', name: 'find_all_sales', methods:['GET'])]
    public function findAllSales(): JsonResponse
    {
        
        $sales = $this->em->getRepository(Sale::class)->findAllSales();
        
        $result = $this->util->listAll($sales);

        if(empty($result)){
            $result = "No hay ventas disponibles";
        }

        return $this->json(
            $result
        );
    }

    #[Route('/{saleId}', name: 'find_one_sale', methods:['GET'])]
    public function findOneSale($saleId): JsonResponse
    {
        $sale = $this->em->getRepository(Sale::class)->findOneBy(['id' => $saleId]);

        if(!empty($sale)){
            $itemSales = $this->em->getRepository(ItemSale::class)->findItemSale($sale);
            $result = ['fullname' => $sale->getUser()->getName() . ' ' . $sale->getUser()->getLastname(), 'items' => $itemSales];
        }
        else{
            $result = "Venta no encontrada";
        }
       
        return $this->json(
            $result
        );
    }

    #[Route('/', name: 'save_sale', methods:['POST'])]
    
    public function saveSale(Request $request): JsonResponse
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
            $sale = new Sale();
            $user = $this->em->getRepository(Users::class)->findOneBy(['id' => $params['userId']]);
            
            $sale->setUser($user);
            $sale->setCreateAt(new DateTime());

            $this->em->getRepository(Sale::class)->save($sale, true);

            $this->service->createItemBook($params['books'], $this->em, $params['quantity'], $sale);

            $result = "Venta guardado con exito";
        }
        
        return $this->json(
            $result
        );
    }

}
