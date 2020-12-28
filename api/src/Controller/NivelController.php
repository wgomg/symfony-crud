<?php

namespace App\Controller;

use App\Entity\Nivel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class NivelController extends Controller
{
    /**
     * @Route("/niveles", methods={"GET"})
     */
    public function listNiveles(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $niveles = $em->getRepository(Nivel::class)->findAll();

        $nivelesArray = [];
        foreach ($niveles as $nivel)
            array_push($nivelesArray, $nivel->toArray());

        $em->flush();
        $em->clear();

        return $this->json($nivelesArray);
    }
}
