<?php

namespace App\Controller;

use App\Entity\Currency;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class CurrencyController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/api/currencies", name="get_currencies", methods={"GET"})
     */
    public function getCurrencies(): Response
    {
        $currencies = $this->em->getRepository(Currency::class)->findAll();

        return $this->json($currencies);
    }
    
    /**
     * @Route("/api/currencies", name="add_currency", methods={"POST"})
     */
    public function addCurrency(Request $request): Response
    {        
        $data = json_decode($request->getContent(), true);
        
        $currency = new Currency();
        $currency->setName($data['name']);
        $currency->setCode($data['code']);
        $currency->setSymbol($data['symbol']);
        
        $this->em->persist($currency);
        $this->em->flush();

        $response = [
            'success' => true,
            'message' => 'Currency created successfully!',
            'data' => [
                'id' => $currency->getId(),
                'name' => $currency->getName(),
                'code' => $currency->getCode(),
                'symbol' => $currency->getSymbol(),
            ],
        ];
        
        return new JsonResponse($response, Response::HTTP_CREATED);
    }    
}

?>