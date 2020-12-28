<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsInstagramProfile extends Constraint
{
    public $message = "The value is not valid.";
}
