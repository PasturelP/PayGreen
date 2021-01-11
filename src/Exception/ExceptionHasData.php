<?php

namespace App\Exception;

/**
 * Interface ExceptionHasData
 * @package App\Exception
 */
interface ExceptionHasData
{
	/**
	 * @return array|string
	 */
	public function getData();
}
