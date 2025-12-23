<?php


namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\UserCreatedNotification;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function store(Request $request)
    {
        $user = User::create($request->all());
        $this->notificationService->send($user, new UserCreatedNotification($user));
        return response()->json(['message' => 'User created and notified']);
    }

    public function login(Request $request)
    {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'بيانات الدخول غير صحيحة'], 401);
    }
    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'access_token' => $token,
        'token_type' => 'Bearer',
    ]);
    }

    public function markAsRead($id)
    {
    //$user = auth()->user();
    // $notification = $user->notifications()->where('id', $id)->firstOrFail();
    // $user = \App\Models\User::first();
    $notification = auth()->user()->notifications()->findOrFail($id);
    $notification->markAsRead();

    return response()->json(['message' => 'تم تحديد الإشعار كمقروء']);
    }
    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['message' => 'تم تحديد جميع الإشعارات كمقروءة']);
    }

    public function getNotifications()
    {
    $user = auth()->user();
    return response()->json([
        'unread' => $user->unreadNotifications,
        'all' => $user->notifications,
        'count_unread' => $user->unreadNotifications->count()
    ]);
    }
}
