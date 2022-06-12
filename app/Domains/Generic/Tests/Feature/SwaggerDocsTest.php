<?php

namespace App\Domains\Generic\Tests\Feature;

use App\Application\Tests\TestCase;
use App\Domains\Generic\Utils\PathUtils;

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
