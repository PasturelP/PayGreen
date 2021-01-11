<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class NotSubmit
 *
 * @package App\Validator\Constraints
 * @Annotation
 * @Target({"CLASS", "ANNOTATION"})
 */
class NotSubmit extends Constraint
{
    /**
     * @var string
     */
    public $message = "Form isn't submitted";
}
