@props(['unreadCount' => 0])

<div x-data="{ open: false, unreadCount: {{ $unreadCount } }, notifications: [] }"
     x-init="fetchUnreadCount(); setInterval(fetchUnreadCount, 60000);"
     class="relative">
    <!-- Notification Bell Button -->
    <button @click="open = ! open; fetchNotifications();"
            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 transition ease-in-out duration-150 relative">
        <!-- Bell Icon -->
        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>

        <!-- Unread Badge -->
        <span x-show="unreadCount > 0"
              x-text="unreadCount > 99 ? '99+' : unreadCount"
              class="absolute -top-1 -right-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-red-600 rounded-full min-w-[1.25rem] h-5">
        </span>
    </button>

    <!-- Notification Dropdown -->
    <div x-show="open"
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute right-0 mt-2 w-80 sm:w-96 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-50"
         style="display: none;">
        <!-- Header -->
        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-medium text-gray-900 dark:text-white">Notifikasi</h3>
                <button @click="markAllAsRead()"
                        class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                    Tandai semua dibaca
                </button>
            </div>
        </div>

        <!-- Loading State -->
        <div x-show="notifications.length === 0" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            <p class="mt-2 text-sm">Tidak ada notifikasi</p>
        </div>

        <!-- Notifications List -->
        <div class="max-h-96 overflow-y-auto">
            <template x-for="notification in notifications" :key="notification.id">
                <a :href="getNotificationUrl(notification)"
                   @click="markAsRead(notification.id); open = false;"
                   class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 ease-in-out border-b border-gray-100 dark:border-gray-700 last:border-b-0"
                   :class="{ 'bg-blue-50 dark:bg-gray-700': !notification.is_read }">
                    <div class="flex items-start space-x-3">
                        <!-- Icon -->
                        <div class="flex-shrink-0">
                            <div :class="getIconClass(notification.color)">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          :d="getIconPath(notification.icon)" />
                                </svg>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate" x-text="notification.title"></p>
                            <p class="text-sm text-gray-600 dark:text-gray-400 truncate" x-text="notification.message"></p>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-500" x-text="notification.created_at"></p>
                        </div>

                        <!-- Unread Indicator -->
                        <div x-show="!notification.is_read"
                             class="flex-shrink-0 h-2 w-2 rounded-full bg-blue-600 mt-2">
                        </div>
                    </div>
                </a>
            </template>
        </div>

        <!-- Footer -->
        <div x-show="notifications.length > 0" class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
            <a href="/notifications" class="block text-center text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                Lihat Semua Notifikasi
            </a>
        </div>
    </div>

    <script>
        function fetchUnreadCount() {
            fetch('{{ route("notifications.unread-count") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.unreadCount = data.data.unread_count;
                    }
                });
        }

        function fetchNotifications() {
            fetch('{{ route("notifications.unread") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.notifications = data.data;
                    }
                });
        }

        function markAsRead(id) {
            fetch(`{{ route("notifications.mark-read", ['id' => '']) }}${id}`, { method: 'POST' })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.notifications = this.notifications.map(n => {
                            if (n.id === id) n.is_read = true;
                            return n;
                        });
                        this.unreadCount = Math.max(0, this.unreadCount - 1);
                    }
                });
        }

        function markAllAsRead() {
            fetch('{{ route("notifications.mark-all-read") }}', { method: 'POST' })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.notifications = this.notifications.map(n => ({...n, is_read: true}));
                        this.unreadCount = 0;
                    }
                });
        }

        function getNotificationUrl(notification) {
            if (notification.data.loan_id) {
                return `/loans/${notification.data.loan_id}`;
            }
            if (notification.data.reservation_id) {
                return `/my-reservations`;
            }
            return '#';
        }

        function getIconClass(color) {
            const classes = {
                success: 'bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-300 rounded-full p-1',
                warning: 'bg-yellow-100 text-yellow-600 dark:bg-yellow-900 dark:text-yellow-300 rounded-full p-1',
                danger: 'bg-red-100 text-red-600 dark:bg-red-900 dark:text-red-300 rounded-full p-1',
                info: 'bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300 rounded-full p-1',
            };
            return classes[color] || classes.info;
        }

        function getIconPath(icon) {
            const paths = {
                'check-circle': 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                'clock': 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                'calendar': 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                'exclamation-circle': 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
                'bell': 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9',
            };
            return paths[icon] || paths.bell;
        }
    </script>
</div>
