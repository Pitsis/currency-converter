<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api/currencies")
 */
class CurrencyController extends AbstractController
{
    /**
     * @Route("/", methods={"POST"})
     */
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        
        // Create a new Currency object
        $currency = new Currency();
        $currency->setName($data['name']);
        $currency->setSymbol($data['symbol']);
        
        // Save the new Currency object to the database
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($currency);
        $entityManager->flush();
        
        // Return a JSON response with the new Currency object
        return $this->json($currency, Response::HTTP_CREATED);
    }
}
?>