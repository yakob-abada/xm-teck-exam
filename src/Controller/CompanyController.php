<?php

namespace App\Controller;

use App\Entity\Company;
use App\Service\HistoricalQuotesService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/company')]
class CompanyController extends AbstractController
{
    #[Route('/', name: 'create_company')]
    public function index(EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $company = new Company();
        $company
            ->setSymbol('ZSPH')
            ->setEmail('test@test.com')
            ->setStartDate(new \DateTime('2023-04-17'))
            ->setEndDate(new \DateTime('2023-04-17'));

        $errors = $validator->validate($company);

        if ($errors->count() > 0) {
            $errorsString = (string) $errors;

            return new Response($errorsString);
        }

        $entityManager->persist($company);
        $entityManager->flush($company);

        return $this->render('company/index.html.twig', [
            'controller_name' => 'CompanyController',
        ]);
    }

    #[Route('/historical_quotes', name: 'get_company_historical_quotes')]
    public function getHistoricalQuotes(HistoricalQuotesService $historicalQuotesService): Response
    {
        return $this->render('company/get.html.twig', [
            'controller_name' => 'CompanyController',
        ]);
    }
}
