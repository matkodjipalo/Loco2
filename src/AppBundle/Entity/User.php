<?php

namespace AppBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="UserRepository")
 * @UniqueEntity(fields="email", message="Email already taken")
 * @ORM\Table(name="user")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=175, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=155)
     * @Assert\NotBlank()
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=155)
     * @Assert\NotBlank()
     */
    private $lastName;

    /**
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="datetime")
     */
    private $registrationDt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastLoginDt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive = false;

    /**
     * @ORM\Column(type="json_array")
     */
    private $roles = array('ROLE_USER');

    /**
     * @ORM\Column(type="string")
     */
    private $confirmationCode;

    public function getRoles()
    {
        return $this->roles;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {
        return;
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    public function __toString()
    {
        return $this->getUsername();
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set firstName.
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName.
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set registrationDt.
     *
     * @param \DateTime $registrationDt
     *
     * @return User
     */
    public function setRegistrationDt($registrationDt)
    {
        $this->registrationDt = $registrationDt;

        return $this;
    }

    /**
     * Get registrationDt.
     *
     * @return \DateTime
     */
    public function getRegistrationDt()
    {
        return $this->registrationDt;
    }

    /**
     * Set lastLoginDt.
     *
     * @param \DateTime $lastLoginDt
     *
     * @return User
     */
    public function setLastLoginDt($lastLoginDt)
    {
        $this->lastLoginDt = $lastLoginDt;

        return $this;
    }

    /**
     * Get lastLoginDt.
     *
     * @return \DateTime
     */
    public function getLastLoginDt()
    {
        return $this->lastLoginDt;
    }

    /**
     * Set isActive.
     *
     * @param bool $isActive
     *
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive.
     *
     * @return bool
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set confirmationCode.
     *
     * @param string $confirmationCode
     *
     * @return User
     */
    public function setConfirmationCode($confirmationCode)
    {
        $this->confirmationCode = $confirmationCode;

        return $this;
    }

    /**
     * Get confirmationCode.
     *
     * @return string
     */
    public function getConfirmationCode()
    {
        return $this->confirmationCode;
    }
}
