<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ContainsFacebookProfileValidator extends ConstraintValidator
{

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ContainsFacebookProfile)
            throw new UnexpectedTypeException($constraint, ContainsFacebookProfile::class);

        if (null === $value || '' === $value) return;

        $matches = [
            preg_match(
                "/(?:https?:)?\/\/(?:www\.)?(?:facebook|fb)\.com\/(?P<profile>(?![A-z]+\.php)(?!marketplace|gaming|watch|me|messages|help|search|groups)[A-z0-9_\-\.]+)\/?/i",
                $value
            ),
            preg_match("/(?:https?:)?\/\/(?:www\.)facebook.com\/(?:profile.php\?id=)?(?P<id>[0-9]+)/i", $value)
        ];

        if (!in_array(1, $matches))
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
    }
}
