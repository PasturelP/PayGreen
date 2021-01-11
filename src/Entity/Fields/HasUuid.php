<?php

namespace App\Entity\Fields;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Trait HasUuid
 * @package App\Entity\Fields
 */
trait HasUuid
{
	/**
	 * @var string|null
	 *
	 * @ORM\Column(name="uuid", type="string", length=255, unique=true, nullable=false)
	 * @Serializer\Groups("default")
	 */
	private $uuid;

	/**
	 * @return string|null
	 */
	public function getUuid(): ?string
	{
		return $this->uuid;
	}

	/**
	 * @param string $uuid
	 * @return self
	 */
	public function setUuid(string $uuid): self
	{
		$this->uuid = $uuid;

		return $this;
	}

	/**
	 * @return string
	 */
	public function __toString(): string
	{
		return $this->getUuid();
	}
}
