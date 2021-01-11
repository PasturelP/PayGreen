<?php

namespace App\Entity;

use App\Entity\Fields\HasId;
use App\Entity\Fields\HasUuid;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Transaction
 * @package App\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="transaction")
 */
class Transaction
{
	use HasId;
	use HasUuid;

	/**
	 * @var User
	 *
	 * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="sender_transactions")
	 * @ORM\JoinColumn(name="sender_id", nullable=false, referencedColumnName="id")
	 * @Serializer\Groups("transaction:create")
	 * @Serializer\Type("string")
	 * @Assert\NotNull()
	 */
	private $sender;

	/**
	 * @var User
	 *
	 * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="receiver_transactions")
	 * @ORM\JoinColumn(name="receiver_id", nullable=false, referencedColumnName="id")
	 * @Serializer\Groups("transaction:create")
	 * @Serializer\Type("string")
	 * @Assert\NotNull()
	 */
	private $receiver;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="amount", type="float", nullable=false)
	 * @Serializer\Groups("transaction:create")
	 * @Assert\NotNull
	 * @Assert\GreaterThan(0)
	 */
	private $amount;

	/**
	 * @return User|null
	 */
	public function getSender(): ?User
	{
		return $this->sender;
	}

	/**
	 * @param User|UserInterface $sender
	 * @return Transaction
	 */
	public function setSender(User $sender): Transaction
	{
		$this->sender = $sender;
		return $this;
	}

	/**
	 * @return User|null
	 */
	public function getReceiver(): ?User
	{
		return $this->receiver;
	}

	/**
	 * @param User $receiver
	 * @return Transaction
	 */
	public function setReceiver(User $receiver): Transaction
	{
		$this->receiver = $receiver;
		return $this;
	}

	/**
	 * @return float
	 */
	public function getAmount(): float
	{
		return $this->amount;
	}

	/**
	 * @param float $amount
	 * @return Transaction
	 */
	public function setAmount(float $amount): Transaction
	{
		$this->amount = $amount;

		return $this;
	}
}
