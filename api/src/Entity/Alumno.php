<?php

namespace App\Entity;

use App\Repository\AlumnoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as CustomAssert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionClass;

/**
 * @ORM\Entity(repositoryClass=AlumnoRepository::class)
 * @UniqueEntity("rut")
 * @UniqueEntity("celular")
 * @UniqueEntity("facebook")
 * @UniqueEntity("instagram")
 */
class Alumno
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\GreaterThanOrEqual(value=1000000, message="Invalid rut.")
     * @Assert\LessThanOrEqual(value=30000000, message="Invalid rut.")
     * @Assert\NotBlank
     */
    private $rut;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     * @Assert\GreaterThanOrEqual(0)
     * @Assert\LessThanOrEqual(10)
     */
    private $dv;

    /**
     * @ORM\Column(type="string", length=45)
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Length(max=45)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=45)
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Length(max=45)
     */
    private $ap_paterno;

    /**
     * @ORM\Column(type="string", length=45)
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Length(max=45)
     */
    private $ap_materno;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\GreaterThanOrEqual(
     *      value=800000000,
     *      message="Invalid number."
     * )
     * @Assert\LessThan(
     *      value=1000000000,
     *      message="Invalid number."
     * )
     */
    private $celular;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Type("string")
     * @Assert\Length(max=255)
     */
    private $direccion;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Type("string")
     * @Assert\Length(max=255)
     * @CustomAssert\ContainsFacebookProfile
     */
    private $facebook;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Type("string")
     * @Assert\Length(max=255)
     * @CustomAssert\ContainsInstagramProfile
     */
    private $instagram;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_on;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_on;

    /**
     * @ORM\ManyToOne(targetEntity="Curso", inversedBy="alumnos")
     * @ORM\JoinColumn(name="curso_id", referencedColumnName="id", nullable=false)
     * @Assert\NotBlank
     */
    private $curso;

    public function __construct(object $alumno)
    {
        $reader = new AnnotationReader();
        $reflect = new ReflectionClass($this);

        foreach ($alumno as $key => $value)
            if (property_exists($this, $key)) {
                $column = $reader->getPropertyAnnotation(
                    $reflect->getProperty($key),
                    @ORM\Column::class
                );

                $this->{$key} = ($column->type === 'string' && ($key !==  "instagram" && $key !== "facebook")) ? strtoupper($value) : $value;

                if ($key === 'dv' && ($value === 'k' || $value === 'K'))
                    $this->dv = 10;
            } else
                continue;

        $this->created_on = new \DateTime();
        $this->updated_on = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRut(): ?int
    {
        return $this->rut === 10 ? 'K' : $this->rut;
    }

    public function setRut(int $rut): self
    {
        $this->rut = $rut === 10 ? 'K' : $rut;

        return $this;
    }

    public function getDv(): ?int
    {
        return $this->dv;
    }

    public function setDv(int $dv): self
    {
        $this->dv = $dv;

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getApPaterno(): ?string
    {
        return $this->ap_paterno;
    }

    public function setApPaterno(string $ap_paterno): self
    {
        $this->ap_paterno = $ap_paterno;

        return $this;
    }

    public function getApMaterno(): ?string
    {
        return $this->ap_materno;
    }

    public function setApMaterno(string $ap_materno): self
    {
        $this->ap_materno = $ap_materno;

        return $this;
    }

    public function getCelular(): ?int
    {
        return $this->celular;
    }

    public function setCelular(?int $celular): self
    {
        $this->celular = $celular;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(?string $direccion): self
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(?string $facebook): self
    {
        $this->facebook = $facebook;

        return $this;
    }

    public function getInstagram(): ?string
    {
        return $this->instagram;
    }

    public function setInstagram(?string $instagram): self
    {
        $this->instagram = $instagram;

        return $this;
    }

    public function getCreatedOn(): ?\DateTimeInterface
    {
        return $this->created_on;
    }

    public function setCreatedOn(\DateTimeInterface $created_on): self
    {
        $this->created_on = $created_on;

        return $this;
    }

    public function getUpdatedOn(): ?\DateTimeInterface
    {
        return $this->updated_on;
    }

    public function setUpdatedOn(\DateTimeInterface $updated_on): self
    {
        $this->updated_on = $updated_on;

        return $this;
    }

    public function getCurso(): ?Curso
    {
        return $this->curso;
    }

    public function setCurso(?Curso $curso): self
    {
        $this->curso = $curso;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'nombre' => $this->getNombre(),
            'ap_paterno' => $this->getApPaterno(),
            'ap_materno' => $this->getApMaterno(),
            'rut' => $this->getRut(),
            'dv' => $this->getDv(),
            'direccion' => $this->getDireccion(),
            'celular' => $this->getCelular(),
            'facebook' => $this->getFacebook(),
            'instagram' => $this->getInstagram(),
            'curso' => $this->getCurso()->toArray()
        ];
    }

    public function validate($validator): ?array
    {
        $errors = $validator->validate($this);

        if (count($errors) === 0) return null;

        $errorsArray =  explode('. ', (string) $errors);
        $errors = [];
        foreach ($errorsArray as $error) {
            $msgArr = array_pad(explode(':', $error), 2, null);
            $field = array_pad(explode('.', $msgArr[0]), 2, null)[1];
            $msg = trim($msgArr[1]);

            if (strlen($field) > 0 && strlen($msg) > 0)
                $errors[$field] = $msg;
        }

        return $errors;
    }
}
