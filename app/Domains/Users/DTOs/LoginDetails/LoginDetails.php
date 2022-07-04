<?php

namespace App\Domains\Users\DTOs\LoginDetails;

final class LoginDetails
{
    public LoginDetailsUserAgent $agent;

    public ?LoginDetailsLocation $location;
}
