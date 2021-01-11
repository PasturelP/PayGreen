<?php

namespace App\DataFixtures;

use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Class UserFixtures
 * @package App\DataFixtures
 */
class UserFixtures extends Fixture
{
	const USERS = [
		'ROLE_USER' => 'user@paygreen.fr',
		'ROLE_ADMIN' => 'admin@paygreen.fr',
	];

	/**
	 * @param ObjectManager $manager
	 */
	public function load(ObjectManager $manager)
	{
		$factory = UserFactory::new();

		foreach (self::USERS as $role => $email) {

			$factory->create([
				'email' => $email,
				'roles' => [$role],
				'password' => strtok($email, '@')
			]);

		}
	}
}
