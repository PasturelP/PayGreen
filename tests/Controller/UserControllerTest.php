<?php

namespace App\Tests\Controller;

use App\Tests\Security\HasAuthentication;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class UserControllerTest
 * @package App\Tests\Controller
 */
class UserControllerTest extends WebTestCase
{
	use HasAuthentication;

	public function testAdminList(): void
	{
		$client = $this->auth('ROLE_ADMIN');

		$client->request('GET', '/users');

		$this->assertTrue($client->getResponse()->isSuccessful());

		$data = json_decode($client->getResponse()->getContent(), true);

		$this->assertCount(1, $data);
		$this->assertArrayNotHasKey('password', $data[0]);
	}

	public function testUserDeniedList(): void
	{
		$client = $this->auth('ROLE_USER');

		$client->request('GET', '/users');

		$this->assertTrue($client->getResponse()->isClientError());
		$this->assertEquals(403, $client->getResponse()->getStatusCode());

		$data = json_decode($client->getResponse()->getContent(), true);
		$this->assertEquals(403, $data['code']);
	}
}
