<?php

namespace App\Controller;

use App\Model\Company;
use App\Form\Type\CompanyType;
use App\Service\HistoricalQuotesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/company')]
class CompanyController extends AbstractController
{
    #[Route('/', name: 'create_company')]
    public function index(Request $request): Response
    {
        $company = new Company();

        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // will do something here.
        }

        return $this->render('company/index.html.twig', [
            'controller_name' => 'CompanyController',
            'form' => $form,
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
