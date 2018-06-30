<?php

namespace Someline\Component\Category\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

class SomelineCategoryValidatorBase extends LaravelValidator
{

    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'category_name' => 'required|string|max:15',
        ],
        ValidatorInterface::RULE_UPDATE => [
            'category_name' => 'required|string|max:15',
        ],
    ];
}
