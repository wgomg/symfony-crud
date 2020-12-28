<?php

namespace App\Entity;

use App\Repository\NivelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionClass;

/**
 * @ORM\Entity(repositoryClass=NivelRepository::class)
 * @UniqueEntity("nombre")
 * @UniqueEntity("codigo")
 */
class Nivel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45)
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Length(max=45)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Length(max=10)
     */
    private $codigo;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Length(max=255)
     */
    private $grados;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_on;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_on;

    /**
     * @ORM\OneToMany(targetEntity="Curso", mappedBy="nivel")
     */
    private $cursos;

    public function __construct(object $nivel)
    {
        $reader = new AnnotationReader();
        $reflect = new ReflectionClass($this);

        foreach ($nivel as $key => $value)
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
        $this->cursos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCodigo(): ?string
    {
        return $this->codigo;
    }

    public function setCodigo(string $codigo): self
    {
        $this->codigo = $codigo;

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

    public function getGrados(): ?string
    {
        return $this->grados;
    }

    public function setGrados(string $grados): self
    {
        $this->grados = $grados;

        return $this;
    }

    /**
     * @return Collection|Curso[]
     */
    public function getCursos(): Collection
    {
        return $this->cursos;
    }

    public function addCurso(Curso $curso): self
    {
        if (!$this->cursos->contains($curso)) {
            $this->cursos[] = $curso;
            $curso->setNivel($this);
        }

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'nombre' => $this->getNombre(),
            'codigo' => $this->getCodigo(),
            'grados' => $this->getGrados()
        ];
    }

    public function removeCurso(Curso $curso): self
    {
        if ($this->cursos->removeElement($curso)) {
            // set the owning side to null (unless already changed)
            if ($curso->getNivel() === $this) {
                $curso->setNivel(null);
            }
        }

        return $this;
    }
}
