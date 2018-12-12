<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UsersVPSRepository")
 */
class UsersVPS
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, name="name")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, name="email")
     */
    private $email;


    /**
     * @ORM\Column(type="string", length=255, name="company_id")
     * @ORM\ManyToOne(targetEntity="App\Entity\CompaniesVPN")
     */
    private $companyId;

}
