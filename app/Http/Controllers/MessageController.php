<?php

namespace App\Http\Controllers;

use App\Events\ChatMessageEvent;
use App\Events\SubscriptionSucceededEvent;
use App\Events\TypingEvent;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Pusher\Pusher;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function index($code)
     {
        $user = auth()->user()->name;

        // Trigger the subscription succeeded event and pass the user information

        // event(new SubscriptionSucceededEvent($user));

        // get started should take you to a page where you create a chat link

        // after which you can copy and send the link (code) to someone

        // then the person has to login to use the code and chat with you


        $fetch_chat = Chat::where('code',$code)->get();

        // return $fetch_chat;

        if(count($fetch_chat) == 0){
            return redirect('/dashboard')->with('error', 'Sorry chat doesnt exist. Try again later');
        }

        // get all the messages for the chat

        $fetch_all_chat_messages = Message::where('chat_code',$fetch_chat[0]->code)
            ->join('users', 'messages.user_id', '=', 'users.id')
            ->select('messages.*', 'users.email', 'users.name')->orderby('messages.id', 'desc')->get();

        // return $fetch_all_chat_messages;

        $data = [
            'chat' => $fetch_chat[0],
            'messages' => $fetch_all_chat_messages
        ];

        return view('pages.chat')->with($data);
    }

    public function join(){

        return view('pages.join');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //

    }

    public function sendMessage(Request $request)
    {
        $options = [
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'useTLS' => true
        ];

        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );

        $content = $request->input('message');

        $data = [
            'user' => Auth::user()->name,
            'message' => $content,
        ];

        // save the message for the chat code

        $chat_code = $request->input('code');

        $user_id = Auth::user()->id;


        // `user_id`, `chat_code`, `content`, `is_read`,

        $save_message = Message::create([
            'user_id' => $user_id,
            'chat_code' => $chat_code,
            'content' => $content,
            'is_read' => true,
        ]);


        $pusher->trigger('chat-channel', 'chat-event', $data);

        // event(new ChatMessageEvent($data['user'], $data['message']));

        return response()->json(['status' => 'Message sent']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
