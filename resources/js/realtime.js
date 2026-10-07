/**
 * Real-time Update System for HSC-OHS
 * Listens to incident changes and updates the interface in real-time
 */

class RealtimeUpdateManager {
    constructor(options = {}) {
        this.listeners = {};
        this.incidentId = options.incidentId || null;
        this.userId = options.userId || null;
        this.debug = options.debug || false;
        this.updateCallbacks = {};
        
        if (this.debug) {
            console.log('RealtimeUpdateManager initialized', {
                incidentId: this.incidentId,
                userId: this.userId
            });
        }
    }

    /**
     * Subscribe to incident updates
     */
    subscribeToIncident(incidentId, callbacks = {}) {
        if (!window.Echo) {
            console.warn('Laravel Echo not available. Real-time updates disabled.');
            return;
        }

        this.incidentId = incidentId;
        this.updateCallbacks = callbacks;

        // Subscribe to private channel for this incident
        window.Echo.private(`incident.${incidentId}`)
            .listen('.incident.updated', (data) => {
                this.handleIncidentUpdate(data);
            });

        if (this.debug) {
            console.log(`Subscribed to incident ${incidentId} updates`);
        }
    }

    /**
     * Handle incoming incident update
     */
    handleIncidentUpdate(data) {
        if (this.debug) {
            console.log('Incident update received:', data);
        }

        const { update_type, changes, message, timestamp } = data;

        // Call appropriate callback based on update type
        switch (update_type) {
            case 'status_change':
                this.onStatusChange(data);
                break;
            case 'document_upload':
                this.onDocumentUpload(data);
                break;
            case 'recommendation':
                this.onRecommendation(data);
                break;
            case 'escalation':
                this.onEscalation(data);
                break;
            default:
                this.onGenericUpdate(data);
        }

        // Call custom callback if provided
        if (this.updateCallbacks[update_type]) {
            this.updateCallbacks[update_type](data);
        }
    }

    /**
     * Handle status change
     */
    onStatusChange(data) {
        const { old_status, new_status } = data.changes;
        const statusElement = document.querySelector('[data-incident-status]');
        
        if (statusElement) {
            statusElement.textContent = this.formatStatus(new_status);
            statusElement.className = `status-badge status-${new_status.toLowerCase()}`;
        }

        this.showNotification(`Status Updated`, `Case status changed to ${this.formatStatus(new_status)}`);
    }

    /**
     * Handle document upload
     */
    onDocumentUpload(data) {
        const { document_type } = data.changes;
        
        // Refresh documents list
        const documentsContainer = document.querySelector('[data-documents-list]');
        if (documentsContainer) {
            this.refreshDocumentsList();
        }

        this.showNotification('Document Uploaded', `New document uploaded: ${document_type}`);
    }

    /**
     * Handle recommendation submission
     */
    onRecommendation(data) {
        const { recommendation_type } = data.changes;
        
        this.showNotification('Recommendation Submitted', `Executive recommendation received`);
        
        // Refresh case details
        this.refreshCaseDetails();
    }

    /**
     * Handle escalation
     */
    onEscalation(data) {
        const { escalated_to } = data.changes;
        
        this.showNotification('Case Escalated', `Case has been escalated to ${escalated_to}`);
        
        // Refresh case status
        this.refreshCaseDetails();
    }

    /**
     * Handle generic update
     */
    onGenericUpdate(data) {
        this.showNotification('Update Received', data.message);
    }

    /**
     * Show in-app notification
     */
    showNotification(title, message) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = 'realtime-notification';
        notification.innerHTML = `
            <div class="notification-content">
                <div class="notification-title">${title}</div>
                <div class="notification-message">${message}</div>
            </div>
        `;

        document.body.appendChild(notification);

        // Animate in
        setTimeout(() => {
            notification.classList.add('show');
        }, 10);

        // Auto remove after 5 seconds
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }

    /**
     * Format status for display
     */
    formatStatus(status) {
        return status
            .split('_')
            .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
            .join(' ');
    }

    /**
     * Refresh documents list
     */
    refreshDocumentsList() {
        if (this.updateCallbacks.refreshDocuments) {
            this.updateCallbacks.refreshDocuments();
        }
    }

    /**
     * Refresh case details
     */
    refreshCaseDetails() {
        if (this.updateCallbacks.refreshCase) {
            this.updateCallbacks.refreshCase();
        }
    }

    /**
     * Unsubscribe from incident updates
     */
    unsubscribe() {
        if (window.Echo && this.incidentId) {
            window.Echo.leave(`incident.${this.incidentId}`);
        }
    }
}

// Export for use in modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = RealtimeUpdateManager;
}
