<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Font Awesome Icons (CSS version for consistency across all pages) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ticket - {{ $ticket->ticket_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Inter"', 'sans-serif']
                    },
                    colors: {
                        brand: { DEFAULT: '#0d7a3d', light: '#1bb74d', dark: '#0a5f30' },
                        navy: '#1e3a5f',
                        surface: '#f8fafc',
                        border: '#e8ecf1',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-surface text-slate-800 font-sans antialiased min-h-screen">
<div class="min-h-screen py-3 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Ticket Details Card -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 mb-3">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-sm font-bold text-navy">Ticket Information</h2>
                <a href="javascript:history.back()" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white text-brand border-2 border-brand rounded-lg font-semibold text-xs hover:bg-brand hover:text-white transition-all duration-300 transform hover:-translate-x-0.5 shadow-sm hover:shadow-md">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    <span>Back</span>
                </a>
            </div>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-xs text-slate-600">Ticket ID:</span>
                    <span class="text-xs font-semibold text-brand">{{ $ticket->ticket_number }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-xs text-slate-600">Category:</span>
                    <span class="text-xs font-semibold">{{ $ticket->category }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-xs text-slate-600">Status:</span>
                    <span class="text-xs font-semibold
                        @if($ticket->status === 'OPEN') text-blue-700
                        @elseif($ticket->status === 'IN_PROGRESS') text-yellow-700
                        @elseif($ticket->status === 'RESOLVED') text-green-700
                        @else text-gray-700
                        @endif">
                        {{ ucwords(str_replace('_', ' ', $ticket->status)) }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-xs text-slate-600">Created By:</span>
                    <span class="text-xs font-semibold">{{ $ticket->originator->name ?? 'Unknown' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-xs text-slate-600">Email:</span>
                    <span class="text-xs font-semibold">{{ $ticket->originator->email ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-xs text-slate-600">Date:</span>
                    <span class="text-xs font-semibold">{{ $ticket->created_at->format('M d, Y H:i') }}</span>
                </div>
            </div>
            <div class="border-t border-slate-200 pt-2 mt-2">
                <p class="text-xs text-slate-600 mb-1">Description:</p>
                <p class="text-xs text-slate-800 leading-relaxed">{{ $ticket->description }}</p>
            </div>

            <!-- Status Update (Super Admin Only) -->
            @if(auth()->user()->hasRole('super_admin'))
                <div class="border-t border-slate-200 pt-2 mt-2">
                    <label class="text-xs text-slate-600 block mb-2">Update Status:</label>
                    <div class="flex flex-wrap gap-2">
                        <button onclick="updateTicketStatus('OPEN', this)" class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-all duration-200 {{ $ticket->status === 'OPEN' ? 'bg-blue-100 text-blue-700 border-2 border-blue-700' : 'bg-slate-100 text-slate-600 border-2 border-slate-200 hover:border-blue-300' }}">
                            Open
                        </button>
                        <button onclick="updateTicketStatus('IN_PROGRESS', this)" class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-all duration-200 {{ $ticket->status === 'IN_PROGRESS' ? 'bg-yellow-100 text-yellow-700 border-2 border-yellow-700' : 'bg-slate-100 text-slate-600 border-2 border-slate-200 hover:border-yellow-300' }}">
                            In Progress
                        </button>
                        <button onclick="updateTicketStatus('RESOLVED', this)" class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-all duration-200 {{ $ticket->status === 'RESOLVED' ? 'bg-green-100 text-green-700 border-2 border-green-700' : 'bg-slate-100 text-slate-600 border-2 border-slate-200 hover:border-green-300' }}">
                            Resolved
                        </button>
                        <button onclick="updateTicketStatus('CLOSED', this)" class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-all duration-200 {{ $ticket->status === 'CLOSED' ? 'bg-gray-100 text-gray-700 border-2 border-gray-700' : 'bg-slate-100 text-slate-600 border-2 border-slate-200 hover:border-gray-300' }}">
                            Closed
                        </button>
                    </div>
                </div>
            @endif
        </div>

        <!-- Messages Card -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4">
            <h2 class="text-sm font-bold text-navy mb-3">Messages</h2>
            <div class="space-y-3 mb-3">
                <!-- Original Message -->
                <div class="flex gap-2">
                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center flex-shrink-0 text-xs">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-1.5 mb-0.5">
                            <span class="text-xs font-semibold">{{ $ticket->originator->name ?? 'Unknown' }}</span>
                            <span class="text-xs px-1.5 py-0.25 rounded-full bg-blue-50 text-blue-700 font-semibold">Originator</span>
                        </div>
                        <p class="text-xs text-slate-500 mb-1">{{ $ticket->created_at->format('M d, Y H:i A') }}</p>
                    </div>
                </div>

                <!-- Replies -->
                @if($ticket->replies->count() > 0)
                    @foreach($ticket->replies as $reply)
                        <div class="flex gap-2">
                            <div class="w-8 h-8 rounded-full bg-green-100 text-green-700 flex items-center justify-center flex-shrink-0 text-xs">
                                <i class="fa-solid fa-user-tie"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-1.5 mb-0.5">
                                    <span class="text-xs font-semibold">{{ $reply->sender->name ?? 'Unknown' }}</span>
                                    <span class="text-xs px-1.5 py-0.25 rounded-full bg-green-50 text-green-700 font-semibold">{{ $reply->sender_role ?? 'Admin' }}</span>
                                </div>
                                <p class="text-xs text-slate-500 mb-1">{{ $reply->created_at->format('M d, Y H:i A') }}</p>
                                <div class="bg-slate-50 p-2 rounded-lg border border-slate-200 text-xs text-slate-800">{{ $reply->message }}</div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- Reply Form -->
            @if(auth()->user()->hasRole('super_admin') && $ticket->status !== 'CLOSED')
                <form id="replyForm" class="border-t border-slate-200 pt-3">
                    @csrf
                    <textarea id="replyMessage" name="message" placeholder="Type your reply..." rows="3" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-brand focus:border-brand" required></textarea>
                    <button type="submit" class="mt-2 inline-flex items-center gap-1 px-3 py-1.5 bg-brand text-white rounded-lg text-xs font-semibold hover:bg-brand-dark transition-colors">
                        <i class="fa-solid fa-paper-plane text-xs"></i>
                        Send
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle reply form submission
        const replyForm = document.getElementById('replyForm');
        if (replyForm) {
            replyForm.addEventListener('submit', handleReplySubmit);
        }
    });

    async function handleReplySubmit(e) {
        e.preventDefault();

        const ticketId = '{{ $ticket->id }}';
        const messageTextarea = document.getElementById('replyMessage');
        const button = e.target.querySelector('button[type="submit"]');
        const originalHTML = button.innerHTML;

        try {
            // Show loading state
            button.disabled = true;
            button.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Sending...';

            const response = await fetch(`/support/ticket/${ticketId}/reply`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                credentials: 'same-origin',
                body: JSON.stringify({ message: messageTextarea.value })
            });

            const data = await response.json();

            if (response.ok && data.success) {
                // Clear the textarea
                messageTextarea.value = '';
                
                // Show success notification in the middle
                showNotification('Reply sent successfully! Redirecting...', 'success');
                
                // Redirect to dashboard after 2 seconds
                setTimeout(() => {
                    window.location.href = '/dashboard';
                }, 2000);
            } else {
                throw new Error(data.message || 'Failed to send reply');
            }
        } catch (error) {
            // Re-enable button
            button.disabled = false;
            button.innerHTML = originalHTML;
            
            showNotification('Error: ' + error.message, 'error');
        }
    }

    async function updateTicketStatus(status, buttonElement) {
        const ticketId = '{{ $ticket->id }}';
        const originalHTML = buttonElement.innerHTML;
        
        try {
            // Disable all status buttons
            document.querySelectorAll('button[onclick*="updateTicketStatus"]').forEach(btn => {
                btn.disabled = true;
                btn.style.opacity = '0.5';
            });

            // Show loading state
            buttonElement.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Updating...';

            const response = await fetch(`/support/ticket/${ticketId}/status`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                credentials: 'same-origin',
                body: JSON.stringify({ status: status })
            });

            const data = await response.json();

            if (response.ok && data.success) {
                // Show success notification
                showNotification('Status updated successfully!', 'success');
                
                // Reload the page after a short delay
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                throw new Error(data.message || data.error || 'Failed to update status');
            }
        } catch (error) {
            // Re-enable all buttons
            document.querySelectorAll('button[onclick*="updateTicketStatus"]').forEach(btn => {
                btn.disabled = false;
                btn.style.opacity = '1';
            });
            buttonElement.innerHTML = originalHTML;
            
            showNotification('Error: ' + error.message, 'error');
        }
    }

    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 px-6 py-4 rounded-lg text-white text-sm font-semibold shadow-lg z-50 ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        }`;
        notification.textContent = message;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
</script>
</body>
</html>
