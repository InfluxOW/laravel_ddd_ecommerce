<?php

namespace App\Domains\Admin\Admin\Components\Forms;

use Filament\Forms\Components\RichEditor as BaseRichEditor;

final class RichEditor extends BaseRichEditor
{
    public static function make(string $name): static
    {
        return parent::make($name)
            ->disableToolbarButtons([
                'attachFiles',
            ]);
    }
}
