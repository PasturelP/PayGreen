<?php

namespace App\Entity\Fields;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait HasId
 * @package App\Entity\Fields
 */
trait HasId
{
	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 * @ORM\Column(name="id", type="integer")
	 */
	private $id;

	/**
	 * @return mixed
	 */
	public function getId(): int
	{
		return $this->id;
	}
}
