<?php

namespace App\Components\Attributable\Database\Seeders;

use App\Components\Attributable\Models\Attribute;
use App\Components\Attributable\Models\AttributeValue;
use App\Infrastructure\Abstracts\Database\Seeder;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;

final class AttributeValueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param class-string<Model>[] $attributableModels
     *
     * @throws Exception
     */
    public function run(array $attributableModels = []): void
    {
        $attributes = Attribute::query()->inRandomOrder()->get(['id', 'values_type', 'title']);
        $attributesCount = $attributes->count();

        $attributeValuesRows = [];
        foreach ($attributableModels as $attributableModel) {
            foreach ($attributableModel::query()->whereDoesntHave('attributeValues')->get(['id']) as $attributable) {
                foreach ($attributes->take(app()->runningUnitTests() ? 4 : random_int(3, $attributesCount)) as $attribute) {
                    $attributeValuesRows[] = AttributeValue::factory()->for($attributable, 'attributable')->for($attribute, 'attribute')->make()->getRawAttributes(['id']);
                }
            }
        }

        DB::insertByChunks((new AttributeValue())->getTable(), LazyCollection::make($attributeValuesRows));
    }
}
