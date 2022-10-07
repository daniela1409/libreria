<?php

namespace App\Controller;

use App\Services\SaleImpl;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/sale', name: 'app_sale')]
class SaleController extends AbstractController
{
    private $serviceSale;

    public function __construct(SaleImpl $serviceSale){
        $this->serviceSale = $serviceSale;
    }

    #[Route('/', name: 'find_all_sales', methods:['GET'])]
    public function findAllSales(): JsonResponse
    {
        $result = $this->serviceSale->findAllSales();

        return $this->json(
            $result
        );
    }

    #[Route('/{saleId}', name: 'find_one_sale', methods:['GET'])]
    public function findOneSale($saleId): JsonResponse
    {
        $result = $this->serviceSale->findOneSale($saleId);
       
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
        
        $result = $this->serviceSale->saveSale($params);
        
        return $this->json(
            $result
        );
    }

}
