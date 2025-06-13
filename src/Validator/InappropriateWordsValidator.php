<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class InappropriateWordsValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        /* @var InappropriateWords $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        // on met d'abord le mot introduit dans le formulaire en minuscule
        // on fait ensuite une boucle pour vérifié que le mot introduit n'est pas dans notre liste
        // si il est dans notre liste, il envoi une violation avec notre message définit dans InappropriateWords.php
        $value = strtolower($value);
        foreach($constraint->listWords as $inappropriateWord){
            if(str_contains($value, $inappropriateWord)){
                $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
            }
        }

    }
}
