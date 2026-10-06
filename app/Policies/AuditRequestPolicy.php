<?php

namespace App\Policies;

class AuditRequestPolicy extends ResourcePolicy
{
    protected string $area = 'audits';
}
