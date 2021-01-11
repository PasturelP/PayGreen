<?php

namespace App\Exception;

use App\Validator\Constraints\NotSubmit;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\ConstraintViolation;

/**
 * Class FormException
 * @package App\Exception
 */
class FormException extends HttpException implements ExceptionHasData
{
	/**
	 * @var FormInterface
	 */
	private $form;

	/**
	 * FormException constructor.
	 * @param FormInterface $form
	 * @param int $statusCode
	 */
	public function __construct(FormInterface $form, int $statusCode = 400)
	{
		$this->form = $form;

		parent::__construct($statusCode);
	}

	/**
	 * @return array|string
	 */
	public function getData()
	{
		if (!$this->form->isSubmitted()) {
			$this->form->addError(
				new FormError(
					'', '', [], null,
					new ConstraintViolation(
						'',
						'',
						[],
						null,
						$this->form->getName(),
						'',
						null,
						null,
						new NotSubmit()
					)
				)
			);
		}


		$errors = [];

		/**
		 * @var int $key
		 * @var FormError $error
		 */
		foreach ($this->form->getErrors(true) as $key => $error) {
			/** @var ConstraintViolation $constraint */
			$constraint = $error->getCause();

			$errors[str_replace('data', $this->form->getName(), $constraint->getPropertyPath())] = $constraint->getMessage();
		}

		return $errors;
	}
}
