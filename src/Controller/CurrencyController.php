<?php

namespace App\Controller;

use App\Entity\Currency;
use App\Entity\ExchangeRate;
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

    //#[Route('/api/currencies', name: 'get_currencies', methods: ['GET'])]
    public function getCurrencies(): JsonResponse
    {
        $currencies = $this->em->getRepository(Currency::class)->findAll();
    
        return $this->json($currencies);
    }
    
    //#[Route('/api/currencies/{id}', name: 'get_currency', methods: ['GET'])]
    public function getCurrency(int $id): JsonResponse
    {
        $currency = $this->em->getRepository(Currency::class)->find($id);
    
        if (!$currency) {
            throw $this->createNotFoundException('Currency not found');
        }
    
        return $this->json($currency);
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

    #[Route('/api/exchangerate', name: 'get_exchange_rate', methods: 'GET')]
    public function getExchangeRate(Request $request): Response
    {
        $sourceCode = $request->query->get('sourceCode');
        $targetCode = $request->query->get('targetCode');

        $currencyRepository = $this->em->getRepository(Currency::class);
        $sourceCurrency = $currencyRepository->findOneBy(['code' => $sourceCode]);
        $targetCurrency = $currencyRepository->findOneBy(['code' => $targetCode]);

        // If either currency is not found, return null
        if (!$sourceCurrency || !$targetCurrency) {
            return $this->json(['error' => 'Please provide both sourceCode and targetCode'], Response::HTTP_BAD_REQUEST);
        }

        // Find the exchange rate between the two currencies
        $exchangeRate = $this->em->getRepository(ExchangeRate::class)->findOneBy([
            'source_currency' => $sourceCurrency->getId(),
            'target_currency' => $targetCurrency->getId(),
        ]); 

        // If exchange rate is not found, return null
        if (!$exchangeRate) {
            return $this->json(['error' => 'No exchange rate found for the provided currencies'], Response::HTTP_NOT_FOUND);
        }

        $response = [
            'success' => true,
            'message' => 'Exchange Rate converted successfully',
            'data' => [
                'fromCurrency' => $sourceCurrency->getCode(),
                'toCurrency' => $targetCurrency->getCode(),
                'rate' => $exchangeRate->getRate(),
                'symbol' => $targetCurrency->getSymbol(),
            ],
        ];
        
        return new JsonResponse($response, Response::HTTP_CREATED);
    }
}

?>