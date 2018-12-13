<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TransferLogsVPSRepository")
 */
class TransferLogsVPS
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @ORM\ManyToOne(targetEntity="App\Entity\UsersVPS")
     */
    private $user_id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_time;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $resource;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $transfered;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getDateTime(): ?\DateTimeInterface
    {
        return $this->date_time;
    }

    public function setDateTime(\DateTimeInterface $date_time): self
    {
        $this->date_time = $date_time;

        return $this;
    }

    public function getResource(): ?string
    {
        return $this->resource;
    }

    public function setResource(string $resource): self
    {
        $this->resource = $resource;

        return $this;
    }

    public function getTransfered(): ?string
    {
        return $this->transfered;
    }

    public function setTransfered(string $transfered): self
    {
        $this->transfered = $transfered;

        return $this;
    }
}
