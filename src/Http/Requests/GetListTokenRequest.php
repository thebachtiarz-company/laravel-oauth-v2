<?php

namespace TheBachtiarz\OAuth\Http\Requests;

use TheBachtiarz\Base\Http\Requests\AbstractRequest;
use TheBachtiarz\Base\Http\Requests\Rules\PaginateRule;

class GetListTokenRequest extends AbstractRequest
{
    #[\Override]
    protected function buildValidator(): void
    {
        $this->validatorBuilder
            ->addRules(new PaginateRule());

        parent::buildValidator();
    }
}
