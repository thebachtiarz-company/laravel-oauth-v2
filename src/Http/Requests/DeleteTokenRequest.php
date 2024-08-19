<?php

namespace TheBachtiarz\OAuth\Http\Requests;

use TheBachtiarz\Base\Http\Requests\AbstractRequest;
use TheBachtiarz\OAuth\Http\Requests\Rules\TokenNameRule;

class DeleteTokenRequest extends AbstractRequest
{
    #[\Override]
    protected function buildValidator(): void
    {
        $this->validatorBuilder
            ->addRules(new TokenNameRule());

        parent::buildValidator();
    }
}
