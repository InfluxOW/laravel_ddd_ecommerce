<?php

namespace App\Domains\Admin\Tests\Feature\Admin;

use App\Application\Tests\Feature\Admin\AdminTestCase;

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
