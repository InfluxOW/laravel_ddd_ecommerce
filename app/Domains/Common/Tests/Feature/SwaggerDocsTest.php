<?php

namespace App\Domains\Common\Tests\Feature;

use App\Domains\Common\Tests\TestCase;
use App\Domains\Common\Utils\PathUtils;

final class SwaggerDocsTest extends TestCase
{
    /** @test */
    public function it_can_generate_swagger_docs(): void
    {
        if ((bool) env('PARATEST', false)) {
            $this->markTestSkipped('For some reason this test breaks other tests in parallel run.'); // TODO: Fix
        }

        $this->artisan('l5-swagger:generate');

        $this->assertFileExists(PathUtils::join([config('l5-swagger.defaults.paths.docs'), config('l5-swagger.documentations.default.paths.docs_json')]));

        $this->get(config('l5-swagger.documentations.default.routes.api'))->assertOk();
    }
}
