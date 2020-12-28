<?php

namespace App\Controller;

use App\Entity\Curso;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class CursoController extends Controller
{
    /**
     * @Route("/cursos", methods={"GET"})
     */
    public function listCursos(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $cursos = $em->getRepository(Curso::class)->findAll();

        $cursosArray = [];
        foreach ($cursos as $curso)
            array_push($cursosArray, $curso->toArray());

        $em->flush();
        $em->clear();

        return $this->json($cursosArray);
    }
}
