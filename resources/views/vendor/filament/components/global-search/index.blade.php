<div class="flex items-center">
    <x-filament::global-search.start />

    @if ($this->isEnabled())
        <div class="relative">
            <x-filament::global-search.input />

            @if ($results !== null)
                <x-filament::global-search.results-container :results="$results" />
            @endif
        </div>
    @endif

    <x-filament::global-search.end />
</div>
