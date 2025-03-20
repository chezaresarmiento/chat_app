<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Log;
use Pusher\Pusher;


class MessageController extends Controller
{
    //
    public function store(Request $request)
    {
        $user=auth()->user();
        $data = $request->validate([
            'content' => 'required|string',
            'room'    => 'required|string', // This holds the channel name (e.g., "room.1")
        ]);

        $message = Message::create([
            'user_id' => auth()->id(),
            'content' => $data['content'],
            'roomid'    => $data['room'],  // Save the channel/room identifier
        ]);


        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            ['cluster' => env('PUSHER_APP_CLUSTER')]
        );

        $data=['name'=>$user->name,'content'=>$message->content,'room'=>$data['room']];
        $pusher->trigger($data["room"], 'MessageSent', $data);
        
        return response()->json($message, 201);
    }
}