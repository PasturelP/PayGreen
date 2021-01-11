<?php

namespace App\Tests\Security;

use App\Entity\User;
use App\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Zenstruck\Foundry\Test\Factories;

/**
 * Class HasAuthentication
 * @package App\Tests\Security
 */
trait HasAuthentication
{
	use Factories;

	public static $USER_EMAIL = 'test@test.fr';
	public static $USER_PASSWORD = 'test';

	/**
	 * @var User|null
	 */
	public static $USER = null;

	/**
	 * @param $roles
	 * @return KernelBrowser
	 */
	private function auth($roles): KernelBrowser
	{
		self::$USER = UserFactory::new()->create([
			'email' => self::$USER_EMAIL,
			'password' => self::$USER_PASSWORD,
			'roles' => is_array($roles) ? $roles : [$roles]
		])->object();

		self::ensureKernelShutdown();
		$client = static::createClient();
		$client->request('POST', '/login', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
				'username' => self::$USER_EMAIL,
				'password' => self::$USER_PASSWORD
			])
		);

		$data = json_decode($client->getResponse()->getContent(), true);
		$client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

		return $client;
	}
}
