<?php

namespace TheBachtiarz\OAuth\Http\Requests;

use TheBachtiarz\Base\Http\Requests\AbstractRequest;
use TheBachtiarz\OAuth\Http\Requests\Rules\AuthIdentifierRule;
use TheBachtiarz\OAuth\Http\Requests\Rules\AuthPasswordRule;

class CreateTokenRequest extends AbstractRequest
{
    #[\Override]
    protected function buildValidator(): void
    {
        $this->validatorBuilder
            ->addRules(new AuthIdentifierRule())
            ->addRules(new AuthPasswordRule());

        parent::buildValidator();
    }
}
