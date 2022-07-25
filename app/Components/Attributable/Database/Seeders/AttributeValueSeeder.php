<?php

namespace App\Components\Attributable\Database\Seeders;

use App\Components\Attributable\Models\Attribute;
use App\Components\Attributable\Models\AttributeValue;
use App\Infrastructure\Abstracts\Database\Seeder;
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
     * @return void
     *
     * @throws \Exception
     */
    public function run(array $attributableModels = [])
    {
        $attributes = Attribute::query()->inRandomOrder()->get(['id', 'values_type']);

        $attributeValuesRows = [];
        foreach ($attributableModels as $attributableModel) {
            foreach ($attributableModel::query()->whereDoesntHave('attributeValues')->get(['id']) as $attributable) {
                foreach ($attributes->take(app()->runningUnitTests() ? 4 : random_int(3, 8)) as $attribute) {
                    $attributeValuesRows[] = AttributeValue::factory()->for($attributable, 'attributable')->for($attribute, 'attribute')->make()->getRawAttributes(['id']);
                }
            }
        }

        DB::insertByChunks((new AttributeValue())->getTable(), LazyCollection::make($attributeValuesRows));
    }
}
