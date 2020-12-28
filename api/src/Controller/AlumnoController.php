<?php

namespace App\Controller;

use App\Entity\Alumno;
use App\Entity\Curso;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class AlumnoController extends Controller
{
    /**
     * @Route("/alumnos", methods={"GET"})
     */
    public function listAlumnos(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $alumnos = $em->getRepository(Alumno::class)->findAll();
        $alumnosArray = [];
        foreach ($alumnos as $alumno)
            array_push($alumnosArray, $alumno->toArray());

        $em->flush();
        $em->clear();

        return $this->json($alumnosArray);
    }
    /**
     * @Route("/alumnos/{id}", methods={"GET"})
     */
    public function getAlumno($id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $alumno = $em->getRepository(Alumno::class)->find($id);

        return $this->json($alumno ? $alumno->toArray() : []);
    }

    /**
     * @Route("/alumnos", methods={"POST"})
     */
    public function newAlumno(Request $request): Response
    {
        $body = json_decode($request->getContent());

        if (count((array)$body) === 0)
            return $this->json(['error' => 'Empty body.'], Response::HTTP_BAD_REQUEST);
        if (!property_exists($body, 'curso_id'))
            return $this->json(['curso' => 'A value must be selected.'], Response::HTTP_BAD_REQUEST);

        $em = $this->getDoctrine()->getManager();

        $curso = $em->getRepository(Curso::class)->find($body->curso_id);
        if (is_null($curso))
            return $this->json(['curso' => 'Invalid value.'], Response::HTTP_BAD_REQUEST);

        unset($body->curso_id);

        $alumno = new Alumno($body);
        $alumno->setCurso($curso);

        $errors = $alumno->validate($this->get('validator'));
        if ($errors) return $this->json($errors, Response::HTTP_BAD_REQUEST);

        if ($this->validateRut($alumno->getRut()) !== $alumno->getDv())
            return $this->json(['rut' => 'Rut or dv invalid.'], Response::HTTP_BAD_REQUEST);

        $em->persist($alumno);
        $em->flush();

        return $this->json($alumno->toArray());
    }

    // TODO: mejorar esta fealdad
    /**
     * @Route("/alumnos", methods={"PUT"})
     */
    public function updateAlumno(Request $request): Response
    {
        $body = json_decode($request->getContent());

        if (count((array)$body) === 0)
            return $this->json(['error' => 'Empty body.'], Response::HTTP_BAD_REQUEST);
        if (!property_exists($body, 'curso_id'))
            return $this->json(['curso' => 'A value must be selected.'], Response::HTTP_BAD_REQUEST);

        $em = $this->getDoctrine()->getManager();

        $curso = $em->getRepository(Curso::class)->find($body->curso_id);
        if (is_null($curso))
            return $this->json(['curso' => 'Invalid value.'], Response::HTTP_BAD_REQUEST);

        unset($body->curso_id);

        $updatedAlumno = new Alumno($body);
        $updatedAlumno->setCurso($curso);

        $alumno = $em->getRepository(Alumno::class)->find($updatedAlumno->getId());
        if (is_null($alumno))
            return $this->json(['alumno' => 'No alumno found.'], Response::HTTP_BAD_REQUEST);

        $errors = $updatedAlumno->validate($this->get('validator'));
        if ($errors) {
            if ($alumno->getRut() === $updatedAlumno->getRut())
                unset($errors['rut']);

            if ($alumno->getDv() === $updatedAlumno->getDv())
                unset($errors['dv']);

            if ($alumno->getCelular() === $updatedAlumno->getCelular())
                unset($errors['celular']);

            if ($alumno->getFacebook() === $updatedAlumno->getFacebook())
                unset($errors['facebook']);

            if ($alumno->getInstagram() === $updatedAlumno->getInstagram())
                unset($errors['instagram']);

            if (count($errors) > 0)
                return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        if ($this->validateRut($updatedAlumno->getRut()) !== $updatedAlumno->getDv())
            return $this->json(['rut' => 'Rut or dv invalid.'], Response::HTTP_BAD_REQUEST);

        $alumno->setRut($updatedAlumno->getRut());
        $alumno->setDv($updatedAlumno->getDv());
        $alumno->setNombre($updatedAlumno->getNombre());
        $alumno->setApPaterno($updatedAlumno->getApPaterno());
        $alumno->setApMaterno($updatedAlumno->getApMaterno());
        $alumno->setCelular($updatedAlumno->getCelular());
        $alumno->setFacebook($updatedAlumno->getFacebook());
        $alumno->setInstagram($updatedAlumno->getInstagram());
        $alumno->setUpdatedOn($updatedAlumno->getCreatedOn());


        $em->persist($alumno);
        $em->flush();

        return $this->json($alumno->toArray());
    }

    /**
     * @Route("/alumnos/{id}", methods={"DELETE"})
     */
    public function removeAlumno($id): Response
    {
        if (trim($id) === "")
            return $this->json(['alumno' => 'No id provided'], Response::HTTP_BAD_REQUEST);

        $em = $this->getDoctrine()->getManager();
        $alumno = $em->getRepository(Alumno::class)->find($id);
        if (is_null($alumno))
            return $this->json(['alumno' => 'No alumno found.'], Response::HTTP_BAD_REQUEST);

        $em->remove($alumno);
        $em->flush();

        return $this->json(['alumno' => $id]);
    }

    /************************************************************************************************/
    private function validateRut($rut): int
    {
        $rutStrArr = str_split(strrev(strval($rut)));
        $s = 2;
        $sum = 0;

        foreach ($rutStrArr as $digit) {
            if ($s === 8) $s = 2;

            $sum += ($digit * $s);

            ++$s;
        }

        $res = 11 - ($sum % 11);
        $res = $res === 11 ? 0 : $res;

        return $res;
    }
}
