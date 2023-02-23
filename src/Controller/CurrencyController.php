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

    public function getCurrencies(): JsonResponse
    {
        $currencies = $this->em->getRepository(Currency::class)->findAll();
    
        return $this->json($currencies);
    }
    
    public function getCurrency(int $id): JsonResponse
    {
        $currency = $this->em->getRepository(Currency::class)->find($id);
    
        if (!$currency) {
            throw $this->createNotFoundException('Currency not found');
        }
    
        return $this->json($currency);
    }

    #[Route('/api/exchangerate', name: 'get_exchange_rate', methods: 'GET')]
    public function getExchangeRate(Request $request): Response
    {
        $sourceCode = $request->query->get('sourceCode');
        $targetCode = $request->query->get('targetCode');

        $currencyRepository = $this->em->getRepository(Currency::class);
        $sourceCurrency = $currencyRepository->findOneBy(['code' => $sourceCode]);
        $targetCurrency = $currencyRepository->findOneBy(['code' => $targetCode]);

        // If either currency is not found, return an error
        if (!$sourceCurrency || !$targetCurrency) {
            return $this->json(['error' => 'Please provide both sourceCode and targetCode'], Response::HTTP_BAD_REQUEST);
        }

        // Find the exchange rate between the two currencies
        $exchangeRate = $this->em->getRepository(ExchangeRate::class)->findOneBy([
            'source_currency' => $sourceCurrency->getId(),
            'target_currency' => $targetCurrency->getId(),
        ]); 

        // If exchange rate is not found, return an error
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

    #[Route('/api/currencies', name: 'create_currency', methods: ['POST'])]
    public function create(Request $request): Response
    {

        $currency = new Currency();
        $currency->setName($request->request->get('name'));
        $currency->setSymbol($request->request->get('symbol'));
        $currency->setCode($request->request->get('code'));

        $this->em->persist($currency);
        $this->em->flush();

        return $this->json($currency);
    }

    #[Route('/api/currencies/{id}', name: 'update_currency', methods: ['PUT', 'PATCH'])]
    public function update(Currency $currency, Request $request): Response
    {
        $currency->setName($request->request->get('name'));
        $currency->setSymbol($request->request->get('symbol'));
        $currency->setCode($request->request->get('code'));

        $this->em->persist($currency);
        $this->em->flush();

        return $this->json($currency);
    }

    #[Route('/api/currencies/{id}', name: 'delete_currency', methods: ['DELETE'])]
    public function delete(Currency $currency): Response
    {
        $this->em->remove($currency);
        $this->em->flush();

        return $this->json(['message' => 'Currency deleted']);
    }
}

?>