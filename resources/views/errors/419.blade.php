{{-- resources/views/errors/419.blade.php --}}
<x-layouts.error title="419 - {{ __('Page Expired') }}">
    <div class="flex flex-col items-center justify-center text-center px-4">
        <img src="{{ asset('favicon.svg') }}" alt="419 Page Expired" class="w-34 mb-8">
        <flux:heading size="xxl">419</flux:heading>
        <flux:text class="mt-4 text-xl text-gray-600 dark:text-gray-300">
            {{ __('Page Expired') }}
        </flux:text>

        {{-- @if (auth()->check())
            aösldfj
            <flux:button href="{{ route('dashboard') }}" variant="primary" class="mt-6">
                {{ __('Go Home') }}
            </flux:button>
        @else
            <flux:button href="{{ route('home') }}" variant="primary" class="mt-6">
                {{ __('Go Home') }}
            </flux:button>
        @endif --}}
        <flux:button onclick="window.location.reload()" variant="primary" class="mt-6 cursor-pointer">
            {{ __('Reload Page') }}
        </flux:button>
        
    </div>
</x-layouts.error>
