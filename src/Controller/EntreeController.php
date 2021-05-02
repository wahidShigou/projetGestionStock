<?php

namespace App\Controller;

use App\Entity\Entree;
use App\Entity\Produit;
use App\Form\EntreeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EntreeController extends AbstractController
{
    /**
     * @Route("/Entree/liste", name="entree_liste")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $e = new Entree();
        $form = $this->createForm(EntreeType::class, $e,[
                'action' => $this->generateUrl('entree_add'),
                'method' => 'POST']
            );

        $data['form'] = $form->createView();
        $data['entrees'] = $em->getRepository(Entree::class)->findAll();
        return $this->render('entree/liste.html.twig', $data);
    }

    /**
     * @Route("/Entree/add", name="entree_add")
     */
    public function new(Request $request)
    {
        // just setup a fresh $task object (remove the example data)
        $e = new Entree();
        $form = $this->createForm(EntreeType::class, $e,[
                'action' => $this->generateUrl('entree_add'),
                'method' => 'POST']
        );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $e = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($e);
            $em->flush();
            // mise a jour des produits
            $p = $em->getRepository(Produit::class)->find($e->getProduit()->getId());
            $stock = $p->getQtStock() + $e->getQtE();
            $p->setQtStock($stock);
            $em->flush();
        }
        return $this->redirectToRoute('entree_liste');

    }
}
