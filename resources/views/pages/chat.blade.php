<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Chat Page') }}
        </h2>
    </x-slot>

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

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-black">
                    <form action="{{ route('chat.process.leave') }}" method="POST">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                        <input type="hidden" name="code" value="{{ $chat->code }}">

                        <button type="submit"
                            class="bg-red-500  py-3 rounded-lg my-1.5 text-white px-3 bg-violet-500 mx-3">Leave Chat
                        </button>
                    </form>
                </div>
                @if ($chat && $chat->host_id == Auth::user()->id)
                    <div class="p-6 text-gray-900 dark:text-black">
                        <form action="{{ route('chat.process.end') }}" method="POST">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                            <input type="hidden" name="code" value="{{ $chat->code }}">

                            <button type="submit"
                                class="bg-red-800 py-3 rounded-lg my-1.5 text-white px-3 bg-violet-500 mx-3">End
                                Chat</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-black">
                    <div id="chat">
                        <div id="messages" class="text-white my-1"></div>
                    </div>

                    <form id="message-form">
                        <textarea type="text" id="message" placeholder="Type your message" class="rounded-lg" required> </textarea>
                        <input type="hidden" id="typing" value="{{ Auth::user()->name }}" class="rounded-lg text-">
                        <input type="hidden" id="chat_code" value="{{ $chat->code }}">

                        <button type="submit"
                            class="bg-red-500  py-3 rounded-lg my-1.5 text-white px-3 bg-violet-500 mx-3">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-black">
                    <div class="text-white my-1">
                        <h1 class="text-center p-3 my-2" style="padding: 10px">Message Archive</h1>
                        <hr />
                        <br />
                        @if (count($messages) > 0)
                            @foreach ($messages as $message)
                                <p class="my-2 bg-grey-600 text-white rounded-lg">
                                    <strong class="my-2">{{ $message['name'] }} <br/>
                                        <span class="text-sm float-right">{{ \Carbon\Carbon::parse($message['created_at'])->format('jS F Y H:i:s A') }}</span>
                                    </strong>:
                                    {{ $message['content'] }}
                                </p>
                                <br/>
                            @endforeach
                        @else
                            <p class="my-1 bg-grey-600 text-white rounded-lg">No Message available</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-black">
                    <form action="{{ route('chat.process.leave') }}" method="POST">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                        <input type="hidden" name="code" value="{{ $chat->code }}">

                        <button type="submit"
                            class="bg-red-500  py-3 rounded-lg my-1.5 text-white px-3 bg-violet-500 mx-3">Leave Chat
                        </button>
                    </form>
                </div>
                @if ($chat && $chat->host_id == Auth::user()->id)
                    <div class="p-6 text-gray-900 dark:text-black">
                        <form action="{{ route('chat.process.end') }}" method="POST">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                            <input type="hidden" name="code" value="{{ $chat->code }}">

                            <button type="submit"
                                class="bg-red-800 py-3 rounded-lg my-1.5 text-white px-3 bg-violet-500 mx-3">End
                                Chat</button>
                        </form>
                    </div>
                @endif

            </div>
        </div>
    </div>

    <script>
        // Pusher initialization
        const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            useTLS: true
        });

        // Subscribe to the chat channel
        const channel = pusher.subscribe('chat-channel');

        const messagesDiv = document.getElementById('messages');

        // Handle new chat messages
        channel.bind('chat-event', function(data) {
            console.log("data", {
                data
            })
            messagesDiv.innerHTML +=
                `<p class="my-2 bg-grey-600 text-white rounded-lg "><strong class="my-2">${data.user}:</strong> ${data.message}</p> <br/>`;
        });

        // Handle form submission
        document.getElementById('message-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const message = document.getElementById('message').value;
            const chat_code = document.getElementById('chat_code').value;

            // Send message to the server
            axios.post('/send-message', {
                    user: "{{ Auth()->user()->name }}",
                    message: message,
                    code: chat_code,
                    user_id: "{{ Auth()->user()->id }}"
                })
                .then(response => {
                    console.log(response.data);
                })
                .catch(error => {
                    console.error(error);
                });

            // Clear input fields
            document.getElementById('message').value = '';
        });

        // check if the user is typing
    </script>

</x-app-layout>
