<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notificacion;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getUnreadNotificationsCount()
    {
        // Obtener las notificaciones no leídas del usuario autenticado
        $unreadCount = Auth::user()->unreadNotifications->count();
        
        return response()->json([
            'unread_count' => $unreadCount,
        ]);
    }
    
}

