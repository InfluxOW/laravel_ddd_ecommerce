@php
    $tree_id = "tree_view_id";
@endphp

@include("catalog::hierarchy-assets")

<script>
    $(document).ready(function () {
        $('#{{ $tree_id }}').nestable({
            group: {{ $tree_id }},
            maxDepth: {{ $this->getMaxDepth()}},
        });

        $('#save').on('click', async function (e) {
            $("#loading").show();
            await @this.updateHierarchy($('#{{ $tree_id }}').nestable('serialize'));
            $("#loading").hide();
        });

        $('#expand').on('click', function (e) {
            $('#{{ $tree_id }}').nestable('expandAll');
        });

        $('#collapse').on('click', function (e) {
            $('#{{ $tree_id }}').nestable('collapseAll');
        });

    });
</script>

<x-filament::page class="col-span-6">
    <menu id="nestable-menu">
        <button id="expand" type="button"
                class="filament-button filament-button-size-md inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset dark:focus:ring-offset-0 min-h-[2.25rem] px-4 text-sm text-white shadow focus:ring-white border-transparent bg-primary-600 hover:bg-primary-500 focus:bg-primary-700 focus:ring-offset-primary-700 filament-page-button-action">
            <span class="flex items-center gap-1">Expand All</span>
        </button>
        <button id="collapse" type="button"
                class="filament-button filament-button-size-md inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset dark:focus:ring-offset-0 min-h-[2.25rem] px-4 text-sm text-white shadow focus:ring-white border-transparent bg-primary-600 hover:bg-primary-500 focus:bg-primary-700 focus:ring-offset-primary-700 filament-page-button-action">
            <span class="flex items-center gap-1">Collapse All</span>
        </button>
    </menu>

    <div class="dd" id="{{ $tree_id }}">
        <ol class="flex-row flex-wrap">
            @foreach ($hierarchy as $category)
                @include("catalog::components.hierarchy-item", ["category"=> $category])
            @endforeach
        </ol>
    </div>

    <button id="save" wire:loading.attr="disabled" wire:loading.class.delay="opacity-70 cursor-wait"
            class="filament-button filament-button-size-md inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset dark:focus:ring-offset-0 min-h-[2.25rem] px-4 text-sm text-white shadow focus:ring-white border-transparent bg-primary-600 hover:bg-primary-500 focus:bg-primary-700 focus:ring-offset-primary-700 filament-page-button-action">
        <x-filament-support::loading-indicator id="loading" style="display:none" class="w-4 h-4"/>
        <span class="flex items-center gap-1">Save</span>
    </button>
</x-filament::page>
