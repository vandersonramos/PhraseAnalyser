<?php

namespace App\Controller;

use App\Form\BasicForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Utils\Graph;

class IndexController extends AbstractController
{

    /**
     * @Route("/", name="index")
     */
    public function index(Request $request)
    {
        $form = $this->createForm(BasicForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('result', ['data' => $form->getData()]);
        }

        return $this->render('form.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/result", name="result")
     */
    public function result(Request $request)
    {
        $message = $request->get('data');
        $message = strtolower($message['user_text']); //football vs soccer

        $graph = new Graph($message);
        $graph->prepareData();

        return $this->render('result.html.twig', [
            'infoData' => $graph->generateData(),
            'text_message' => $message
        ]);
    }
}