<?php

namespace App\Domains\Admin\Tests\Feature\Admin;

use App\Domains\Admin\Tests\AdminTestCase;

final class AdminPagesTest extends AdminTestCase
{
    /** @test */
    public function it_has_pages(): void
    {
        foreach (config('filament.pages.register') as $page) {
            $this->getResourceActionUrl($page)->assertOk();
        }
    }
}
