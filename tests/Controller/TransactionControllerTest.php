<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Factory\UserFactory;
use App\Tests\Security\HasAuthentication;
use Faker;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class TransactionControllerTest
 * @package App\Tests\Controller
 */
class TransactionControllerTest extends WebTestCase
{
	use HasAuthentication;

	public function testCreate(): void
	{
		$faker = Faker\Factory::create();

		$client = $this->auth('ROLE_USER');

		/** @var User $user */
		$user = UserFactory::new()->create()->object();

		$amount = $faker->randomFloat(1, 1);

		$client->request('POST', '/transactions', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
				'transaction' => [
					'amount' => $amount,
					'receiver' => $user->getUuid()
				]
			])
		);


		$this->assertTrue($client->getResponse()->isSuccessful());

		$data = json_decode($client->getResponse()->getContent(), true);

		$this->assertEquals($amount, $data['amount']);
		$this->assertEquals($user->getUuid(), $data['receiver']);
		$this->assertEquals(self::$USER->getUuid(), $data['sender']);
	}

	public function testCreateBadAmount(): void
	{
		$faker = Faker\Factory::create();

		$client = $this->auth('ROLE_USER');

		/** @var User $user */
		$user = UserFactory::new()->create()->object();

		$amount = $faker->randomFloat(1, -100, 0);

		$client->request('POST', '/transactions', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
				'transaction' => [
					'amount' => $amount,
					'receiver' => $user->getUuid()
				]
			])
		);

		$this->assertTrue($client->getResponse()->isClientError());

		$data = json_decode($client->getResponse()->getContent(), true);

		$this->assertArrayHasKey('errors', $data);
		$this->assertArrayHasKey('transaction.amount', $data['errors']);
	}

	public function testCreateBadUser(): void
	{
		$faker = Faker\Factory::create();

		$client = $this->auth('ROLE_USER');

		$client->request('POST', '/transactions', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
				'transaction' => [
					'amount' => $faker->randomFloat(1, 1),
					'receiver' => self::$USER->getUuid()
				]
			])
		);

		$this->assertTrue($client->getResponse()->isClientError());

		$data = json_decode($client->getResponse()->getContent(), true);

		$this->assertArrayHasKey('errors', $data);
		$this->assertArrayHasKey('children[receiver]', $data['errors']);
	}
}
