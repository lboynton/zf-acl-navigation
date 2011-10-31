<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Entity for user
 *
 * @author Lee Boynton <lee@lboynton.com>
 * @Entity (repositoryClass="App\Entity\Repository\UserRepository")
 * @Table(name="user",
 * 	uniqueConstraints={@UniqueConstraint(name="user_unique",columns={"username"})})
 */
class User
{
	/**
	 * @var int
	 * @id @Column(type="integer")
	 * @GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	 * Username of the user
	 * @var string
	 * @Column(type="string", length="255")
	 */
	protected $username;
	
	/**
	 * Password of the user
	 * @var string
	 * @Column(type="string", length="255")
	 */
	protected $password;

	/**
	 * User level
	 * @var string
	 * @Column(type="string", length="20")
	 */
	protected $user_level;

	public function __construct()
	{
		// set defaults for new users
		$this->user_level = 'client';
	}

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getUsername()
	{
		return $this->username;
	}

	public function setUsername($username)
	{
		$this->username = $username;
	}

	public function getPassword()
	{
		return $this->password;
	}

	public function setPassword($password)
	{
		$this->password = $password;
	}

	public function getUserLevel()
	{
		return $this->user_level;
	}

	public function setUserLevel($userLevel)
	{
		$this->user_level = $userLevel;
	}
}
