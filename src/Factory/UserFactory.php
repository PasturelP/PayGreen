<?php

namespace App\Factory;

use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Zenstruck\Foundry\ModelFactory;

/**
 * Class UserFactory
 * @package App\Factory
 */
class UserFactory extends ModelFactory
{
	/**
	 * @var EncoderFactoryInterface
	 */
	private $encoder_factory;

	/**
	 * UserFactory constructor.
	 * @param EncoderFactoryInterface $encoder_factory
	 */
	public function __construct(EncoderFactoryInterface $encoder_factory)
	{
		parent::__construct();
		$this->encoder_factory = $encoder_factory;
	}

	/**
	 * @return $this
	 */
	protected function initialize(): self
	{
		return $this
			->afterInstantiate(function(User $user) {
				$user->setPassword($this->encoder_factory->getEncoder(self::getClass())->encodePassword($user->getPassword(), $user->getSalt()));
			});
	}

	/**
	 * @return string
	 */
	protected static function getClass(): string
	{
		return User::class;
	}

	/**
	 * @return array
	 */
	protected function getDefaults(): array
	{
		return [
			'password' => 'password',
			'email' => self::faker()->email
		];
	}
}
