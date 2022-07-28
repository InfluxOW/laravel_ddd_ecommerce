<?php

namespace App\Components\LoginHistoryable\DTOs\LoginDetails;

final class LoginDetails
{
    public LoginDetailsUserAgent $agent;

    public ?LoginDetailsLocation $location;
}
