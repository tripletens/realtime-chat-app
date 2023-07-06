<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                @if (session('success'))
                    <div class="bg-white text-green-500 p-4 mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-white text-red-800 p-4 mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="p-6 text-red-600 dark:text-gray-100 border-red-800">

                    <x-nav-link :href="route('chat.join')"
                        class="mx-auto text-center border-red-800 hover:bg-violet-600 active:bg-violet-700 focus:outline-none focus:ring focus:ring-violet-300">

                        {{ __('Join Chat') }}

                    </x-nav-link>

                    <span class="text-white"> || </span>

                    <x-nav-link :href="route('chat.create')"
                        class="mx-auto text-center border-red-800 hover:bg-violet-600 active:bg-violet-700 focus:outline-none focus:ring focus:ring-violet-300">

                        {{ __('Create Chat') }}

                    </x-nav-link>
                </div>

            </div>
        </div>
    </div>

</x-app-layout>
