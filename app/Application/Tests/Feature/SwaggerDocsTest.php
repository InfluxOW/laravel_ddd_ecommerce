<?php

namespace App\Application\Tests\Feature;

use App\Application\Tests\TestCase;
use App\Components\Generic\Utils\PathUtils;

final class SwaggerDocsTest extends TestCase
{
    /** @test */
    public function it_can_generate_swagger_docs(): void
    {
        $this->artisan('l5-swagger:generate');

        $this->assertFileExists(PathUtils::join([config('l5-swagger.defaults.paths.docs'), config('l5-swagger.documentations.default.paths.docs_json')]));

        $this->get(config('l5-swagger.documentations.default.routes.api'))->assertOk();
    }
}
