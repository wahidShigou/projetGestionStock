<?php

namespace App\Controller;


use App\Entity\Produit;
use App\Entity\Sortie;
use App\Form\SortieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    /**
     * @Route("/Sortie/liste", name="sortie_liste")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $s = new Sortie();
        $form = $this->createForm(SortieType::class, $s,[
                'action' => $this->generateUrl('sortie_add'),
                'method' => 'POST']
        );
        $data['form'] = $form->createView();
        $data['sorties'] = $em->getRepository(Sortie::class)->findAll();
        return $this->render('sortie/liste.html.twig', $data);
    }

    /**
     * @Route("/Sortie/add", name="sortie_add")
     */
    public function new(Request $request)
    {
        // just setup a fresh $task object (remove the example data)
        $s = new Sortie();
        $form = $this->createForm(SortieType::class, $s,[
                'action' => $this->generateUrl('produit_add'),
                'method' => 'POST']
        );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $s = $form->getData();
            $qsortie = $s->getQtS();
            $p = $em->getRepository(Produit::class)->find($s->getProduit()->getId());
            if ($p->getQtStock() < $s->getQtS()){
                $s = new Sortie();
                $form = $this->createForm(SortieType::class, $s,[
                        'action' => $this->generateUrl('sortie_add'),
                        'method' => 'POST']
                );
                $data['form'] = $form->createView();
                $data['sorties'] = $em->getRepository(Sortie::class)->findAll();
                $data['error_message'] = 'le stock disponible est inferieur a'.$qsortie;
                return $this->render('sortie/liste.html.twig', $data);
            }else{
                $em->persist($s);
                $em->flush();
                // mise a jour du produit
                $stock = $p->getQtStock() - $s->getQtS();
                $p->setQtStock($stock);
                $em->flush();
                return $this->redirectToRoute('sortie_liste');
            }
        }
    }
}
