<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MessageController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Message;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/getMessages/{roomId}', function($roomId)
    {
        $user = auth()->user();
        $lastLogin = session('last_login');

        $messages = Message::where('created_at', '>', $lastLogin)
            ->where('roomid', $roomId)
            ->with('user:id,name')
            ->orderBy('created_at', 'asc')
            ->get()->map(function ($message) {
                // Merge user properties into top-level array
                return [
                    'id' => $message->id,
                    'name' => $message->user->name ?? 'Unknown',
                    'content' => $message->content,
                    'roomid' => $message->roomid,
                    'created_at' => $message->created_at->format('M d, Y h:i'),
                    'updated_at' => $message->updated_at,
                ];
            });


        return response()->json($messages);
    });

//use MessageController to post messages
Route::post('/messages', [MessageController::class, 'store'])->middleware('auth')->name('messages.store');


require __DIR__.'/auth.php';
