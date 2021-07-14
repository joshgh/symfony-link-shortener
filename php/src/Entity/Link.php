<?php

namespace App\Entity;

use App\Repository\LinkRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=LinkRepository::class)
 * @UniqueEntity(
 *      fields="identifier",
 *      message="This shortlink is already in use, please choose another"
 * )
 */
class Link
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Url
     * @Assert\NotBlank
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\Regex(
     *      pattern="/^[a-zA-Z0-9]{5,9}$/",
     *      message="Identifier must only contain alphanumeric characters and be from 5 to 9 characters long"
     * )
     * @Assert\NotBlank
     */
    private $identifier;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }
}
