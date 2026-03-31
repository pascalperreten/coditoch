<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body
    class="bg-[#FDFDFC] dark:bg-[#0a0a0a] flex flex-col text-[#1b1b18] p-6 lg:p-8 min-h-screen">
    <header class="w-full lg:max-w-4xl max-w-[335px] mx-auto text-sm mb-6 not-has-[nav]:hidden flex justify-between">
        <div>
            <img src="{{ asset('favicon-96x96.png') }}" alt="Coditoch Logo" class="h-8 w-auto">
        </div>
        @if (Route::has('login'))
            <nav class="flex items-center justify-end gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" wire:navigate
                        class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" wire:navigate
                        class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal">
                        {{ __('Log in') }}
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" wire:navigate
                            class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                            {{ __('Register') }}
                        </a>
                    @endif
                @endauth
            </nav>
        @endif
    </header>
        <main class="flex justify-center flex-col space-y-6">
            <div class="bg-[#2d333b] p-10 rounded-lg w-4xl mx-auto">
                <div class="max-w-100 mx-auto">
                    <img src="{{ asset('storage/images/coditoch_logo_1024.png') }}" alt="Welcome Illustration" class="w-full max-w-md">
                </div>
            </div>

            {{-- <div>
                <flux:heading size="xl" class="text-center">{{ __('Welcome to Coditoch') }}</flux:heading>
                <flux:text class="text-center text-lg mt-4">{{ __('Your tool for managing your ministry\'s contacts and events.') }}</flux:text>
            </div> --}}
            
        </main>
    </div>

    @if (Route::has('login'))
        <div class="h-14.5 hidden lg:block"></div>
    @endif
</body>

</html>
