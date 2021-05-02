<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    /**
     * @Route("/Produit/liste", name="produit_liste")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        //creation de formulaire
        $p = new Produit();
        $form = $this->createForm(ProduitType::class, $p,[
            'action' => $this->generateUrl('produit_add'),
            'method' => 'POST']
        );
        $data['form'] = $form->createView();
        $data['produits'] = $em->getRepository(Produit::class)->findAll();
        return $this->render('produit/liste.html.twig', $data);
    }


    /**
     * @Route("/produit/get{id}", name="produit_get")
     */
    public function getProduit($id)
    {
        return $this->render('produit/liste.html.twig');
    }


    /**
     * @Route("/Produit/add", name="produit_add")
     */
    public function new(Request $request)
    {
        // just setup a fresh $task object (remove the example data)
        $p = new Produit();

        $form = $this->createForm(ProduitType::class, $p,[
        'action' => $this->generateUrl('produit_add'),
            'method' => 'POST']
        );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $p = $form->getData();
             $em = $this->getDoctrine()->getManager();
             $em->persist($p);
             $em->flush();
        }
        return $this->redirectToRoute('produit_liste');

    }


}
