<!-- Real-time Update System -->
<link rel="stylesheet" href="{{ asset('css/realtime-notifications.css') }}">

<!-- Initialize Echo and Realtime Manager -->
<script>
    // Initialize realtime updates for current user
    document.addEventListener('DOMContentLoaded', function() {
        const incidentId = document.querySelector('[data-incident-id]')?.getAttribute('data-incident-id');
        
        if (incidentId && window.Echo) {
            window.realtimeManager = new RealtimeUpdateManager({
                incidentId: parseInt(incidentId),
                userId: {{ auth()->id() ?? 'null' }},
                debug: {{ config('app.debug') ? 'true' : 'false' }}
            });

            // Subscribe with callbacks
            window.realtimeManager.subscribeToIncident(parseInt(incidentId), {
                'status_change': function(data) {
                    console.log('Status changed:', data);
                    location.reload(); // Refresh page on status change
                },
                'document_upload': function(data) {
                    console.log('Document uploaded:', data);
                    if (window.refreshDocuments) {
                        window.refreshDocuments();
                    }
                },
                'recommendation': function(data) {
                    console.log('Recommendation received:', data);
                    if (window.refreshCase) {
                        window.refreshCase();
                    }
                }
            });
        }
    });

    // Cleanup on page unload
    window.addEventListener('unload', function() {
        if (window.realtimeManager) {
            window.realtimeManager.unsubscribe();
        }
    });
</script>
