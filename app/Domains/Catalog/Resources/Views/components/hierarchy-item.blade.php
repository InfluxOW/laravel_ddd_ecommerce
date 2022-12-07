@php use App\Domains\Catalog\Admin\Resources\ProductCategoryResource; @endphp
<li class="dd-item cursor-pointer" data-id="{{ $category->id }}">
    <div class="dd-container">
        <div class="dd-handle {{ $category->is_displayable ? "" : "dd-nondisplayable" }}">{{ $category->title }}</div>
        <a class="dd-url filament-button filament-button-size-md inline-flex items-center justify-center py-1 px-1 gap-1 font-medium rounded-lg border transition-colors focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset dark:focus:ring-offset-0 min-h-[2.25rem] text-sm text-white shadow focus:ring-white border-transparent bg-primary-600 hover:bg-primary-500 focus:bg-primary-700 focus:ring-offset-primary-700 filament-page-button-action"
           href="{{ ProductCategoryResource::getUrl('edit', ['record' => $category]) }}"
        >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                 stroke="currentColor" aria-hidden="true" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>
            </svg>
        </a>
    </div>
    <ol class="dd-list">
        @foreach ($category->children as $child)
            @include("catalog::components.hierarchy-item", ["category" => $child])
        @endforeach
    </ol>
</li>
