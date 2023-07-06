<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Join Chat Page') }}
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

                <div class="p-6 text-gray-900 dark:text-black">
                    <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Enter Chat Code
                    </h3> <br />
                    <div id="chat">
                        <form id="join-chat-form" method="POST" action="{{ route('chat.process.join') }}">
                            @csrf
                            <input type="text" id="code" name="code" placeholder="Type your code"
                                class="rounded-lg text- @error('title') is-invalid @enderror" required>
                            @error('code')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <button type="submit"
                                class="bg-red-500 button py-3 rounded-lg my-1.5 text-white px-3 bg-violet-500 mx-3">Enter</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
