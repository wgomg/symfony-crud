<?php

namespace App\Entity;

use App\Repository\CursoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionClass;

/**
 * @ORM\Entity(repositoryClass=CursoRepository::class)
 * @UniqueEntity("nombre")
 * @UniqueEntity(
 *      fields={"grado", "identificador"},
 *      errorPath="identificador",
 *      message="This entry already exists."     
 * )
 */
class Curso
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25)
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Length(max=25)
     */
    private $grado;

    /**
     * @ORM\Column(type="string", length=3, nullable=true)
     * @Assert\Type("string")
     * @Assert\Length(max=3)
     */
    private $identificador;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Type("string")
     * @Assert\Length(max=255)
     */
    private $observaciones;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_on;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_on;

    /**
     * @ORM\ManyToOne(targetEntity="Nivel", inversedBy="cursos")
     * @ORM\JoinColumn(name="nivel_id", referencedColumnName="id", nullable=false)
     * @Assert\NotBlank
     */
    private $nivel;

    /**
     * @ORM\OneToMany(targetEntity="Alumno", mappedBy="curso")
     */
    private $alumnos;

    public function __construct(object $curso)
    {
        $reader = new AnnotationReader();
        $reflect = new ReflectionClass($this);

        foreach ($curso as $key => $value)
            if (property_exists($this, $key)) {
                $column = $reader->getPropertyAnnotation(
                    $reflect->getProperty($key),
                    @ORM\Column::class
                );

                $this->{$key} = ($column->type === 'string') ? strtoupper($value) : $value;
            } else
                continue;

        $this->created_on = new \DateTime();
        $this->updated_on = new \DateTime();
        $this->alumnos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdentificador(): ?string
    {
        return $this->identificador;
    }

    public function setIdentificador(string $identificador): self
    {
        $this->identificador = $identificador;

        return $this;
    }

    public function getObservaciones(): ?string
    {
        return $this->observaciones;
    }

    public function setObservaciones(?string $observaciones): self
    {
        $this->observaciones = $observaciones;

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

    public function getGrado(): ?string
    {
        return $this->grado;
    }

    public function setGrado(string $grado): self
    {
        $this->grado = $grado;

        return $this;
    }

    /**
     * @return Collection|Alumno[]
     */
    public function getAlumnos(): Collection
    {
        return $this->alumnos;
    }

    public function addAlumno(Alumno $alumno): self
    {
        if (!$this->alumnos->contains($alumno)) {
            $this->alumnos[] = $alumno;
            $alumno->setCurso($this);
        }

        return $this;
    }

    public function removeAlumno(Alumno $alumno): self
    {
        if ($this->alumnos->removeElement($alumno)) {
            // set the owning side to null (unless already changed)
            if ($alumno->getCurso() === $this) {
                $alumno->setCurso(null);
            }
        }

        return $this;
    }

    public function getNivel(): ?Nivel
    {
        return $this->nivel;
    }

    public function setNivel(?Nivel $nivel): self
    {
        $this->nivel = $nivel;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'grado' => $this->getGrado(),
            'identificador' => $this->getIdentificador(),
            'observaciones' => $this->getObservaciones(),
            'nivel' => $this->getNivel()->toArray()
        ];
    }
}
