<?php

namespace app\src\model\Form\rules;


use app\src\model\Form\FormInputValue;
use app\src\model\Form\FormValidationException;

class RuleDateAfter extends FormAttributeRule
{

    /**
     * @inheritDoc
     */
    public function process(FormInputValue $value): void
    {
        $date = $value->getDate();
        if (is_null($date))
            return;

        $toCompare = $this->getOption('after');

        if ($date <= $toCompare)
            throw new FormValidationException('Veuillez saisir une date après le ' . $toCompare->format('d-m-Y'));
    }
}