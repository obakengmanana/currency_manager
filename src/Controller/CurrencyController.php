<?php

namespace App\Controller;

use App\Entity\Currency;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Entity\Conversion;
use SebastianBergmann\Environment\Console;
use Symfony\Component\HttpFoundation\JsonResponse;

class CurrencyController extends AbstractController
{

    private $httpClient;
    
    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @Route("/", name="currency_list")
     */

    public function list(PersistenceManagerRegistry $doctrine): Response
    {
        // Fetch currency data from an API 
        $apiUrl = 'https://cdn.jsdelivr.net/gh/fawazahmed0/currency-api@1/latest/currencies.json';
        $response = $this->httpClient->request('GET', $apiUrl);
        
        // Decode the JSON response
        $currencies = $response->toArray();

        // Fetch all currencies from the database
        $entityManager = $doctrine->getManager();
        $selectedcurrencies = $entityManager->getRepository(Currency::class)->findAll();

        // Fetch conversion currency data from an API (replace the URL with your actual API endpoint)
        $apiUrl1 = 'https://cdn.jsdelivr.net/gh/fawazahmed0/currency-api@1/latest/currencies/eur/zar.json';
        $res = $this->httpClient->request('GET', $apiUrl1);
               
        // Decode the JSON response
        $currencyex = $res->toArray();

        $conversionData = array();
        foreach ($selectedcurrencies as $value) {
            $curr = $value->getCode();
            // Fetch conversion currency data from an API (replace the URL with your actual API endpoint)
            $apiUrl1 = "https://cdn.jsdelivr.net/gh/fawazahmed0/currency-api@1/latest/currencies/$curr/zar.json";
            $res = $this->httpClient->request('GET', $apiUrl1);
        
            // Decode the JSON response
            $currencyex = $res->toArray();

            $conversion = new Conversion();
            $conversion->setZarName('South African Rand');
            $conversion->setZarCode('zar');
            $conversion->setInZar(round(floatval($currencyex['zar']),2));
            $conversion->setCompName($value->getName());
            $conversion->setCompCode($value->getCode());
            $conversion->setInComp(floatval($value->getAmount()));
            $conversion->setcurrMultiByzar( round(floatval($currencyex['zar']) * floatval($value->getAmount()),2) );

            array_push($conversionData, $conversion);
            
          }

        return $this->render('currency/list.html.twig', ['currencies' => $currencies, 'selectedcurrencies' => $selectedcurrencies, 'conversionData' => $conversionData]);
    }


    /**
     * @Route("/add", name="currency_add")
     */
    public function add(Request $request, PersistenceManagerRegistry $doctrine): Response
    {
        try {
            // Saving data to the db upon user click
            $data = json_decode($request->getContent(), true);

            // Validate data (you may want to add more robust validation)
            if (!isset($data['name']) || !isset($data['code'])) {
                throw new \InvalidArgumentException('Invalid data format');
            }

            // Check if the currency already exists in the database
            $entityManager = $doctrine->getManager();
            $existingCurrency = $entityManager
                ->getRepository(Currency::class)
                ->findOneBy(['name' => $data['name'], 'code' => $data['code']]);

            if (!$existingCurrency) {
                // If the currency doesn't exist, persist the new record
                $currency = new Currency();
                $currency->setName($data['name']);
                $currency->setCode($data['code']);

                $entityManager->persist($currency);
                $entityManager->flush();

                return new JsonResponse(['message' => 'Currency added successfully'], Response::HTTP_OK);
            } else {
                return new JsonResponse(['message' => 'Currency already exists'], Response::HTTP_OK);
            }
        } catch (\Exception $e) {
            // Handle exceptions (log, return an error response, etc.)
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/edit/{code}", name="currency_edit")
     */
    public function edit(Currency $currency, Request $request, PersistenceManagerRegistry $doctrine): Response
    {
        // Retrieve the form data from the request
        $editedAmount = $request->request->get('amount');

        // Validate and sanitize the form data as needed

        // Update the amount property of the $currency entity
        $currency->setAmount($editedAmount);

        // Get the entity manager
        $entityManager = $doctrine->getManager();

        // Persist the changes to the database
        $entityManager->persist($currency);
        $entityManager->flush();

        // Redirect the user to the currency list page
        return $this->redirectToRoute('currency_list');
    }

    /**
     * @Route("/delete/{id}", name="currency_delete")
     */
    public function delete(Currency $currency, PersistenceManagerRegistry $doctrine): Response
    {

        //$entityManager = $this->getDoctrine()->getManager();
        $entityManager = $doctrine->getManager();
        $entityManager->remove($currency);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Currency deleted successfully'], Response::HTTP_OK);
        //return $this->redirectToRoute('currency_list');
    }
}