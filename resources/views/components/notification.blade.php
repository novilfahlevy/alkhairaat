{{-- Notification Component --}}
@if (session('success'))
    <div id="success-message" class="mb-4 rounded-lg bg-green-100 p-4 text-sm text-green-700 dark:bg-green-900/30 dark:text-green-400">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div id="error-message" class="mb-4 rounded-lg bg-red-100 p-4 text-sm text-red-700 dark:bg-red-900/30 dark:text-red-400">
        {{ session('error') }}
    </div>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-close notifications after 4 seconds
        const successMessage = document.getElementById('success-message');
        const errorMessage = document.getElementById('error-message');

        function autoCloseNotification(messageElement) {
            if (messageElement) {
                setTimeout(() => {
                    messageElement.style.transition = 'opacity 0.5s ease-out';
                    messageElement.style.opacity = '0';
                    setTimeout(() => {
                        messageElement.remove();
                    }, 500);
                }, 4000);
            }
        }

        autoCloseNotification(successMessage);
        autoCloseNotification(errorMessage);
    });
</script>
