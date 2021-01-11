<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;

/**
 * Class SecurityControllerTest
 * @package App\Tests\Controller
 */
class SecurityControllerTest extends WebTestCase
{
	use Factories;

	public function testWrongPassword(): void
	{
		/** @var User $user */
		$user = UserFactory::new()->create()->object();

		self::ensureKernelShutdown();
		$client = static::createClient();
		$client->request('POST', '/login', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
				'username' => $user->getEmail(),
				'password' => 'test'
			])
		);

		$this->assertTrue($client->getResponse()->isClientError());
		$this->assertEquals(401, $client->getResponse()->getStatusCode());
	}
}
