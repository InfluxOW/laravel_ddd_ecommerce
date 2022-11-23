<?php

namespace App\Domains\Common\Mixins;

use Closure;
use Illuminate\Http\Request;

/**
 * @mixin Request
 * */
final class RequestMixin
{
    public function getIp(): Closure
    {
        return function (): ?string {
            $remotesKeys = [
                'HTTP_X_FORWARDED_FOR',
                'HTTP_CLIENT_IP',
                'HTTP_X_REAL_IP',
                'HTTP_X_FORWARDED',
                'X-FORWARDED-FOR',
                'HTTP_FORWARDED_FOR',
                'HTTP_FORWARDED',
                'REMOTE_ADDR',
                'HTTP_X_CLUSTER_CLIENT_IP',
            ];

            foreach ($remotesKeys as $key) {
                $address = $this->header($key) ?? getenv($key);

                if (is_string($address)) {
                    foreach (explode(',', $address) as $ip) {
                        $ip = trim($ip);

                        if (
                            filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false &&
                            filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 | FILTER_FLAG_NO_PRIV_RANGE) === false
                        ) {
                            continue;
                        }

                        return $ip;
                    }
                }
            }

            return $this->ip();
        };
    }
}
