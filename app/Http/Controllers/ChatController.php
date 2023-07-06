<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\ChatMembers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class ChatController extends Controller
{
    // create a chat

    public function create_chat()
    {
        return view('pages.create');
    }

    public function save_chat(Request $request)
    {

        $validated = $request->validate([
            'title' => 'required',
            'max_no' => 'required',
        ]);

        $title = $request->input('title');

        $max_no = $request->input('max_no');

        $code = Str::random(8);

        $save_chat = Chat::create(
            [
                "title" => $title,
                "max_no" => $max_no,
                "code" => $code,
                "host_id" => Auth()->user()->id
            ]
        );

        // return $save_chat;

        if ($save_chat) {
            // Success message or action
            return redirect()->route('chat.create')->with('success', 'Chat code created successfully and here is your code - ' .  $code);
        } else {
            // Error message or action
            return redirect(400)->back()->with('error', 'Failed to save chat.');
        }
    }

    public function join_chat(Request $request)
    {
        //
        $validated = $request->validate([
            'code' => 'required',
        ]);

        $code = $request->input('code');

        // check if the code exist

        $check_code = Chat::where('code', $code)->get();

        if (!$check_code || count($check_code) == 0) {
            return redirect()->back()->with('error', 'Chat code doesnt exist. Try again later');
        }

        if ($check_code && ($check_code[0]->status == false)) {
            return redirect()->back()->with('error', 'Chat has already been ended by the host.');
        }

        // check the number of people that have joined with the code

        // `user_id`, `chat_code`, `chat_id`, `status`, `created_at`, `updated_at`

        $code = $check_code[0]->code;

        $max_no = $check_code[0]->max_no;

        $chat_id = $check_code[0]->id;

        $no_of_users = ChatMembers::where('chat_code', $code)->get();

        $user_id = Auth()->user()->id;

        // check if the user is already a member of the chat
        $check_user_membership = ChatMembers::where('user_id', $user_id)->where('chat_code',$code)->get();

        if ((count($no_of_users) >= $max_no)) {
            // the we have reached the max number of users for the chat
            return redirect()->back()->with('error', 'The chat is full. Try again later');
        } else {
            // now we can join the chat
            if (count($check_user_membership) > 0) {

                // check if the user is still active in the chat

                $check_user_status = $check_user_membership[0]->status;

                if ($check_user_status == false) {
                    // users already a member of the chat
                    return redirect()->route('dashboard')->with('error', 'Sorry, you cannot join the chat.');
                } else {
                    return redirect()->route('chat.index', $code)->with('success', 'Welcome back to the chat');
                }
            } else {
                $save_chat = ChatMembers::create([
                    'user_id' => $user_id,
                    'chat_code' => $code,
                    'chat_id' => $chat_id,
                    'status' => true
                ]);

                if (!$save_chat) {
                    return redirect()->back()->with('error', 'Failed to join chat. Try again later');
                }

                // now we can redirect to the chat page
                return redirect()->route('chat.index', $code)->with('success', 'Joined Chat with code - ' . $code . ' Successfully');
            }
        }
    }

    public function leave_chat(Request $request)
    {

        $user_id = Auth()->user()->id;

        $chat_code = $request->input('code');

        $check_user_member = ChatMembers::where('user_id', $user_id)
            ->where('chat_code', $chat_code)->get();

        if (count($check_user_member) == 0) {

            return redirect()->back()->with('error', 'You are not a member of this chat');

        } else {

            // update the user status to false in the chat member table
            $update_chat_member_status = ChatMembers::where('user_id', $user_id)
                ->where('chat_code', $chat_code)->update([
                    "status" => false
            ]);

            if (!$update_chat_member_status) {
                return redirect()->back()->with('error', 'Sorry, we could not process it at the moment. Try again later.');
            }

            return redirect()->route('dashboard')->with('success', 'You have successfully left the chat');
        }
    }

    public function end_chat(Request $request)
    {

        $user_id = Auth()->user()->id;

        $chat_code = $request->input('code');

        // check if the chat is ended already

        $chat_status = Chat::where('code',$chat_code)->where('status', false)->get();

        if (count($chat_status) > 0) {

            return redirect()->back()->with('error', 'Chat has already ended by the host.');

        } else {

            // update the user status to false in the chat table
            $update_chat_status = Chat::where('code', $chat_code)->update([
                    "status" => false
            ]);

            if (!$update_chat_status) {
                return redirect()->back()->with('error', 'Sorry, we could not process it at the moment. Try again later.');
            }

            return redirect()->route('dashboard')->with('success', 'You have successfully ended the chat');
        }
    }
}
