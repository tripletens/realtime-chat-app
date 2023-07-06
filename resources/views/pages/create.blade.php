<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Chat Page') }}
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
                    <div class="bg-red-200 text-red-800 p-4 mb-4">
                        {{ session('error') }}
                    </div>
                @endif
                <div class="p-6 text-gray-900 light:text-green">
                    <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Enter Chat Details
                    </h3>
                    <br />

                    <div id="chat">
                        <form id="create-chat-form" method="POST" action="{{ route('chat.save') }}">
                            @csrf
                            <div class="mb-4">
                                <label for="title" class="block text-gray-700 dark:text-gray-300">Chat Title:</label>
                                <input type="text" id="title" name="title" placeholder="Enter your chat title"
                                    class="rounded-lg w-full @error('title') is-invalid @enderror" required>
                                @error('title')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="max_no" class="block text-gray-700 dark:text-gray-300">Max Number of
                                    Recipients:</label>
                                <input type="number" id="max_no" min="2" name="max_no"
                                    placeholder="Enter max number of recipients"
                                    class="rounded-lg w-full @error('max_no') is-invalid @enderror" required>
                                @error('max_no')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit"
                                class="bg-red-500 hover:bg-red-600 focus:bg-red-600 button py-3 rounded-lg my-1.5 text-white px-3">Enter</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>
