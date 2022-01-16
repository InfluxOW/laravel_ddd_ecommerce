<?php

namespace App\Domains\Admin\Tests\Admin;

use App\Application\Tests\Admin\AdminTestCase;

class AdminPagesTest extends AdminTestCase
{
    /** @test */
    public function it_has_pages(): void
    {
        foreach (config('filament.pages.register') as $page) {
            $this->getResourceActionUrl($page)->assertOk();
        }
    }
}
