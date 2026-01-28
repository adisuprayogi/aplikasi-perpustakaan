<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    /**
     * Get all notifications for the authenticated user.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        // Try to get notifications from member first, fallback to user
        $notifiable = $user->member ?? $user;

        $notifications = $notifiable->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate($request->input('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => NotificationResource::collection($notifications),
            'meta' => [
                'total' => $notifications->total(),
                'per_page' => $notifications->perPage(),
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
            ],
        ]);
    }

    /**
     * Get unread notifications for the authenticated user.
     */
    public function unread(Request $request): JsonResponse
    {
        $user = $request->user();

        // Try to get notifications from member first, fallback to user
        $notifiable = $user->member ?? $user;

        $notifications = $notifiable->unreadNotifications()
            ->orderBy('created_at', 'desc')
            ->paginate($request->input('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => NotificationResource::collection($notifications),
            'meta' => [
                'total' => $notifications->total(),
                'per_page' => $notifications->perPage(),
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
            ],
        ]);
    }

    /**
     * Get count of unread notifications.
     */
    public function unreadCount(Request $request): JsonResponse
    {
        $user = $request->user();

        // Try to get notifications from member first, fallback to user
        $notifiable = $user->member ?? $user;

        return response()->json([
            'success' => true,
            'data' => [
                'unread_count' => $notifiable->unreadNotifications()->count(),
            ],
        ]);
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead(Request $request, string $id): JsonResponse
    {
        $user = $request->user();

        // Try to get notifications from member first, fallback to user
        $notifiable = $user->member ?? $user;

        $notification = $notifiable->notifications()->where('id', $id)->first();

        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found',
            ], 404);
        }

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read',
            'data' => new NotificationResource($notification),
        ]);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        $user = $request->user();

        // Try to get notifications from member first, fallback to user
        $notifiable = $user->member ?? $user;

        $notifiable->unreadNotifications->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read',
        ]);
    }

    /**
     * Delete a specific notification.
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        $user = $request->user();

        // Try to get notifications from member first, fallback to user
        $notifiable = $user->member ?? $user;

        $notification = $notifiable->notifications()->where('id', $id)->first();

        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found',
            ], 404);
        }

        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted',
        ]);
    }

    /**
     * Clear all read notifications.
     */
    public function clearRead(Request $request): JsonResponse
    {
        $user = $request->user();

        // Try to get notifications from member first, fallback to user
        $notifiable = $user->member ?? $user;

        $notifiable->readNotifications()->delete();

        return response()->json([
            'success' => true,
            'message' => 'All read notifications cleared',
        ]);
    }
}
