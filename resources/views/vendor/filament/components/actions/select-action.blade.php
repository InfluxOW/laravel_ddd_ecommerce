<div>
    <label for="{{ $getId() }}" class="sr-only">
        {{ $getLabel() }}
    </label>

    <select
        id="{{ $getId() }}"
        wire:model="{{ $getName() }}"
        {{ $attributes->class([
            'text-gray-900 border-gray-300 invalid:text-gray-400 block w-full h-9 py-1 transition duration-75 rounded-lg shadow-sm focus:border-primary-600 focus:ring-1 focus:ring-inset focus:ring-primary-600',
        ]) }}
    >
        @if (($placeholder = $getPlaceholder()) !== null)
            <option value="">{{ $placeholder }}</option>
        @endif

        @foreach ($getOptions() as $value => $label)
            <option value="{{ $value }}">
                {{ $label }}
            </option>
        @endforeach
    </select>
</div>
