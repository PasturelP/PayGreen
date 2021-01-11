<?php

namespace App\Form\Transaction;

use App\Entity\Transaction;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;

/**
 * Class CreateTransactionType
 * @package App\Form\Transaction
 */
class CreateTransactionType extends AbstractType
{
	/**
	 * @var Security
	 */
	private $security;

	/**
	 * CreateTransactionType constructor.
	 * @param Security $security
	 */
	public function __construct(Security $security)
	{
		$this->security = $security;
	}

	/**
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('amount', NumberType::class, [
				'scale' => 2,
				'required' => true
			])
			->add('receiver', EntityType::class, [
				'class' => User::class,
				'required' => true,
				'choice_value' => 'uuid',
				'multiple' => false,
				'query_builder' => function (EntityRepository $repository) {
					$qb = $repository->createQueryBuilder('u');

					if ($this->security->getUser() === null) {
						return $qb;
					}

					return $qb->where(
						$qb->expr()->neq('u.id', $this->security->getUser()->getId())
					);
				}
			]);
	}

	/**
	 * @return string
	 */
	public function getBlockPrefix(): string
	{
		return 'transaction';
	}

	/**
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => Transaction::class,
			'csrf_protection' => false
		]);
	}
}
