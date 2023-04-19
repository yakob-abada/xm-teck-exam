<?php

namespace App\Controller;

use App\Model\Company;
use App\Form\Type\CompanyType;
use App\Service\NewCompanySubmissionMediator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompanyController extends AbstractController
{
    #[Route('/', name: 'company_index')]
    public function index(Request $request, NewCompanySubmissionMediator $submissionMediator): Response
    {
        $company = new Company();

        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $historicalQuotes = $submissionMediator->mediate($company);

            return $this->render('company/get.html.twig', [
                'historical_quotes' => $historicalQuotes,
                'company' => $company,
            ]);
        }

        return $this->render('company/index.html.twig', [
            'form' => $form,
        ]);
    }
}
