{{-- resources/views/errors/404.blade.php --}}
<x-layouts.error title="404 - {{ __('Page Not Found') }}">
    <div class="flex flex-col items-center justify-center text-center px-4">
        <img src="{{ asset('favicon.svg') }}" alt="404 Not Found" class="w-34 mb-8">
        <flux:heading size="xxl">404</flux:heading>
        <flux:text class="mt-4 text-xl text-gray-600 dark:text-gray-300">
            {{ __('Page Not Found') }}
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
        <flux:button href="{{ route('dashboard') }}" variant="primary" class="mt-6 cursor-pointer">
            {{ __('Back') }}
        </flux:button>
        
    </div>
</x-layouts.error>
