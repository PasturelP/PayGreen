<?php

namespace App\Entity;

use App\Entity\Fields\HasId;
use App\Entity\Fields\HasUuid;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use JMS\Serializer\Annotation as Serializer;

/**
 * Class User
 * @package App\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="user")
 */
class User implements UserInterface
{
	public const SALT = '';

	use HasId;
	use HasUuid;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="email", type="string", length=255, nullable=false)
	 * @Serializer\Groups("user:list")
	 */
	private $email;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="password", type="text", nullable=false)
	 */
	private $password;

	/**
	 * @var array
	 *
	 * @ORM\Column(name="roles", type="array", nullable=false)
	 */
	private $roles;

	/**
	 * @var Collection
	 *
	 * @ORM\OneToMany(targetEntity="App\Entity\Transaction", mappedBy="sender")
	 */
	private $sender_transactions;

	/**
	 * @var Collection
	 *
	 * @ORM\OneToMany(targetEntity="App\Entity\Transaction", mappedBy="receiver")
	 */
	private $receiver_transactions;

	/**
	 * User constructor.
	 */
	public function __construct()
	{
		$this->sender_transactions = new ArrayCollection();
		$this->receiver_transactions = new ArrayCollection();
	}

	/**
	 * @return string
	 */
	public function getEmail(): string
	{
		return $this->email;
	}

	/**
	 * @param string $email
	 * @return User
	 */
	public function setEmail(string $email): User
	{
		$this->email = $email;

		return $this;
	}

	/**
	 * @return Collection
	 */
	public function getSenderTransactions(): Collection
	{
		return $this->sender_transactions;
	}

	/**
	 * @param Collection $sender_transactions
	 * @return User
	 */
	public function setSenderTransactions(Collection $sender_transactions): User
	{
		$this->sender_transactions = $sender_transactions;
		return $this;
	}

	/**
	 * @return Collection
	 */
	public function getReceiverTransactions(): Collection
	{
		return $this->receiver_transactions;
	}

	/**
	 * @param Collection $receiver_transactions
	 * @return User
	 */
	public function setReceiverTransactions(Collection $receiver_transactions): User
	{
		$this->receiver_transactions = $receiver_transactions;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getRoles(): array
	{
		return $this->roles;
	}

	/**
	 * @param array $roles
	 * @return User
	 */
	public function setRoles(array $roles): User
	{
		$this->roles = $roles;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getPassword(): string
	{
		return $this->password;
	}

	/**
	 * @param string $password
	 * @return User
	 */
	public function setPassword(string $password): User
	{
		$this->password = $password;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getSalt(): string
	{
		return self::SALT;
	}

	/**
	 * @return string
	 */
	public function getUsername(): string
	{
		return $this->email;
	}


	public function eraseCredentials(): void
	{
	}
}
