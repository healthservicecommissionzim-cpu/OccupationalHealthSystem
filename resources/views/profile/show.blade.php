<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Profile — Health Service Commission</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Inter"', 'sans-serif']
                    },
                    colors: {
                        brand: {
                            DEFAULT: '#079b6d',
                            light: '#e9f8f3',
                            dark: '#067c58',
                        },
                        navy: '#101a31',
                        surface: '#f5f7fa',
                        border: '#e1e7ee',
                    }
                }
            }
        }
    </script>
    <style>
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-surface text-slate-800 font-sans antialiased min-h-screen">
<div class="min-h-screen py-3 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <!-- Back Button -->
        <a href="{{ $backRoute }}" class="inline-flex items-center gap-1.5 mb-3 px-3 py-1.5 bg-white text-brand border-2 border-brand rounded-lg font-semibold text-xs hover:bg-brand hover:text-white transition-all duration-300 transform hover:-translate-x-0.5 shadow-sm hover:shadow-md">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            <span>Back</span>
        </a>

        <!-- Profile Header -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 mb-4">
            <div class="flex flex-col sm:flex-row sm:items-start gap-4">
                <div class="w-16 h-16 rounded-lg bg-gradient-to-br from-brand to-brand-light flex items-center justify-center text-white flex-shrink-0">
                    <span class="text-2xl font-bold">
                        @php
                            $fullName = $user->name;
                            $initials = implode('', array_map(fn($word) => substr($word, 0, 1), explode(' ', trim($fullName))));
                            echo strtoupper(substr($initials, 0, 2));
                        @endphp
                    </span>
                </div>
                <div class="flex-1">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <h1 class="text-lg font-bold text-navy mb-0.5" id="display-name">{{ $user->name }}</h1>
                            <p class="text-xs text-slate-600 mb-0.5" id="display-email">{{ $user->email }}</p>
                            <p class="text-xs text-slate-600 mb-2" id="display-phone">{{ $user->phone_number ?? 'Not provided' }}</p>
                        </div>
                        <button onclick="openEditModal()" class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-brand text-white rounded-lg text-xs font-medium hover:bg-brand-dark transition-colors flex-shrink-0">
                            <i class="fa-solid fa-edit text-xs"></i>
                            <span>Edit</span>
                        </button>
                    </div>
                    <div class="flex flex-wrap gap-1.5 mb-2">
                        @foreach($roles as $role)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-brand-light bg-opacity-20 text-brand font-semibold text-xs">
                            {{ $role->display_name }}
                        </span>
                        @endforeach
                    </div>
                    <div>
                        @if($user->status === 'approved')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-green-50 text-green-700 font-semibold text-xs">
                                <i class="fa-solid fa-check text-xs mr-0.5"></i> Approved
                            </span>
                        @elseif($user->status === 'pending')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-yellow-50 text-yellow-700 font-semibold text-xs">
                                <i class="fa-solid fa-hourglass-half text-xs mr-0.5"></i> Pending
                            </span>
                        @elseif($user->status === 'rejected')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-red-50 text-red-700 font-semibold text-xs">
                                <i class="fa-solid fa-times text-xs mr-0.5"></i> Rejected
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Organizational Information -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 mb-4">
            <h2 class="text-sm font-bold text-navy mb-3 flex items-center gap-2">
                <i class="fa-solid fa-building text-brand text-sm"></i> Organizational Information
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Organization</label>
                    <p class="text-xs text-slate-700 mt-0.5">{{ $organization->name ?? 'Not assigned' }}</p>
                </div>
                <div>
                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Province</label>
                    <p class="text-xs text-slate-700 mt-0.5">{{ $user->province->name ?? 'Not assigned' }}</p>
                </div>
                <div>
                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide">District</label>
                    <p class="text-xs text-slate-700 mt-0.5">{{ $user->district->name ?? 'Not assigned' }}</p>
                </div>
                <div>
                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Facility</label>
                    <p class="text-xs text-slate-700 mt-0.5">{{ $facility->name ?? 'Not assigned' }}</p>
                </div>
                <div>
                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Department</label>
                    <p class="text-xs text-slate-700 mt-0.5">{{ $department->name ?? 'Not assigned' }}</p>
                </div>
                <div>
                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Designation</label>
                    <p class="text-xs text-slate-700 mt-0.5">{{ $designation->name ?? 'Not assigned' }}</p>
                </div>
            </div>
        </div>

        <!-- Account Information -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 mb-4">
            <div class="flex items-start justify-between mb-3">
                <h2 class="text-sm font-bold text-navy flex items-center gap-2">
                    <i class="fa-solid fa-info-circle text-brand text-sm"></i> Account Information
                </h2>
                <button onclick="openPasswordModal()" class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-brand text-white rounded-lg text-xs font-medium hover:bg-brand-dark transition-colors">
                    <i class="fa-solid fa-lock text-xs"></i>
                    <span>Change Password</span>
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Member Since</label>
                    <p class="text-xs text-slate-700 mt-0.5">{{ $user->created_at->format('F d, Y') }}</p>
                </div>
                <div>
                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Last Updated</label>
                    <p class="text-xs text-slate-700 mt-0.5">{{ $user->updated_at->format('F d, Y \a\t h:i A') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Personal Information Modal -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-xl w-full max-h-96 overflow-y-auto">
        <div class="p-4 border-b border-slate-200 flex items-center justify-between sticky top-0 bg-white">
            <h2 class="text-base font-bold text-navy flex items-center gap-2">
                <i class="fa-solid fa-edit text-brand text-sm"></i> Edit Personal Information
            </h2>
            <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-times text-lg"></i>
            </button>
        </div>

        <form id="editForm" class="p-4 space-y-3">
            <div>
                <label for="edit_name" class="block text-xs font-semibold text-slate-700 mb-1.5">Full Name</label>
                <input type="text" id="edit_name" name="name" value="{{ $user->name }}" required class="w-full px-3 py-1.5 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent transition-all text-sm">
                <span id="error_name" class="text-red-600 text-xs mt-0.5 hidden"></span>
            </div>

            <div>
                <label for="edit_email" class="block text-xs font-semibold text-slate-700 mb-1.5">Email Address</label>
                <input type="email" id="edit_email" name="email" value="{{ $user->email }}" required class="w-full px-3 py-1.5 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent transition-all text-sm">
                <span id="error_email" class="text-red-600 text-xs mt-0.5 hidden"></span>
            </div>

            <div>
                <label for="edit_phone" class="block text-xs font-semibold text-slate-700 mb-1.5">Phone Number</label>
                <input type="tel" id="edit_phone" name="phone_number" value="{{ $user->phone_number ?? '' }}" class="w-full px-3 py-1.5 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent transition-all text-sm">
                <span id="error_phone_number" class="text-red-600 text-xs mt-0.5 hidden"></span>
            </div>

            <div class="flex gap-2 pt-3 border-t border-slate-200">
                <button type="button" onclick="closeEditModal()" class="flex-1 px-3 py-1.5 border border-slate-200 rounded-lg text-slate-700 font-medium hover:bg-slate-50 transition-colors text-sm">
                    Cancel
                </button>
                <button type="submit" id="editSubmitBtn" class="flex-1 px-3 py-1.5 bg-brand text-white rounded-lg font-medium hover:bg-brand-dark transition-colors text-sm">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Password Change Modal -->
<div id="passwordModal" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-xl w-full">
        <div class="p-4 border-b border-slate-200 flex items-center justify-between">
            <h2 class="text-base font-bold text-navy flex items-center gap-2">
                <i class="fa-solid fa-lock text-brand text-sm"></i> Change Password
            </h2>
            <button onclick="closePasswordModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-times text-lg"></i>
            </button>
        </div>

        <form id="passwordForm" class="p-4 space-y-3">
            <div>
                <label for="current_password" class="block text-xs font-semibold text-slate-700 mb-1.5">Current Password</label>
                <input type="password" id="current_password" name="current_password" required class="w-full px-3 py-1.5 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent transition-all text-sm">
                <span id="error_current_password" class="text-red-600 text-xs mt-0.5 hidden"></span>
            </div>

            <div>
                <label for="new_password" class="block text-xs font-semibold text-slate-700 mb-1.5">New Password</label>
                <input type="password" id="new_password" name="password" required minlength="8" class="w-full px-3 py-1.5 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent transition-all text-sm">
                <span id="error_password" class="text-red-600 text-xs mt-0.5 hidden"></span>
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 mb-1.5">Confirm New Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8" class="w-full px-3 py-1.5 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent transition-all text-sm">
                <span id="error_password_confirmation" class="text-red-600 text-xs mt-0.5 hidden"></span>
            </div>

            <div class="flex gap-2 pt-3 border-t border-slate-200">
                <button type="button" onclick="closePasswordModal()" class="flex-1 px-3 py-1.5 border border-slate-200 rounded-lg text-slate-700 font-medium hover:bg-slate-50 transition-colors text-sm">
                    Cancel
                </button>
                <button type="submit" id="passwordSubmitBtn" class="flex-1 px-3 py-1.5 bg-brand text-white rounded-lg font-medium hover:bg-brand-dark transition-colors text-sm">
                    Update Password
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Toast Notification -->
<div id="toast" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-30 p-4">
    <div id="toastContent" class="rounded-lg shadow-xl border p-6 flex gap-4 max-w-md w-full transform transition-all duration-300 scale-95">
        <div class="flex-shrink-0">
            <i id="toastIcon" class="text-3xl"></i>
        </div>
        <div class="flex-1">
            <h3 id="toastTitle" class="font-bold text-lg mb-1"></h3>
            <p id="toastMessage" class="text-sm"></p>
        </div>
        <button onclick="hideToast()" class="flex-shrink-0 text-slate-400 hover:text-slate-600 transition-colors">
            <i class="fa-solid fa-times text-xl"></i>
        </button>
    </div>
</div>

<script>
// Get CSRF token
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

// Modal functions
function openEditModal() {
    document.getElementById('editModal').classList.remove('hidden');
    document.getElementById('editModal').classList.add('flex');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.getElementById('editModal').classList.remove('flex');
    clearErrors('edit');
}

function openPasswordModal() {
    document.getElementById('passwordModal').classList.remove('hidden');
    document.getElementById('passwordModal').classList.add('flex');
}

function closePasswordModal() {
    document.getElementById('passwordModal').classList.add('hidden');
    document.getElementById('passwordModal').classList.remove('flex');
    document.getElementById('passwordForm').reset();
    clearErrors('password');
}

// Toast functions
function showToast(type, title, message) {
    const toast = document.getElementById('toast');
    const toastContent = document.getElementById('toastContent');
    const toastIcon = document.getElementById('toastIcon');
    const toastTitle = document.getElementById('toastTitle');
    const toastMessage = document.getElementById('toastMessage');

    // Set colors based on type
    if (type === 'success') {
        toastContent.className = 'rounded-lg shadow-xl border p-6 flex gap-4 max-w-md w-full transform transition-all duration-300 bg-green-50 border-green-200';
        toastIcon.className = 'text-3xl fa-solid fa-check-circle text-green-600';
        toastTitle.className = 'font-bold text-lg mb-1 text-green-900';
        toastMessage.className = 'text-sm text-green-800';
    } else {
        toastContent.className = 'rounded-lg shadow-xl border p-6 flex gap-4 max-w-md w-full transform transition-all duration-300 bg-red-50 border-red-200';
        toastIcon.className = 'text-3xl fa-solid fa-exclamation-circle text-red-600';
        toastTitle.className = 'font-bold text-lg mb-1 text-red-900';
        toastMessage.className = 'text-sm text-red-800';
    }

    toastTitle.textContent = title;
    toastMessage.textContent = message;
    toast.classList.remove('hidden');
    toast.classList.add('flex');

    // Add scale animation
    setTimeout(() => {
        toastContent.classList.remove('scale-95');
        toastContent.classList.add('scale-100');
    }, 10);

    if (type === 'success') {
        setTimeout(() => hideToast(), 4000);
    }
}

function hideToast() {
    const toast = document.getElementById('toast');
    const toastContent = document.getElementById('toastContent');
    
    toastContent.classList.remove('scale-100');
    toastContent.classList.add('scale-95');
    
    setTimeout(() => {
        toast.classList.add('hidden');
        toast.classList.remove('flex');
    }, 300);
}

// Clear validation errors
function clearErrors(formType) {
    const prefix = formType === 'edit' ? 'edit_' : '';
    const fields = formType === 'edit' 
        ? ['name', 'email', 'phone_number'] 
        : ['current_password', 'password', 'password_confirmation'];
    
    fields.forEach(field => {
        const errorElement = document.getElementById(`error_${field}`);
        if (errorElement) {
            errorElement.classList.add('hidden');
            errorElement.textContent = '';
        }
    });
}

// Show validation errors
function showErrors(errors) {
    for (const [field, messages] of Object.entries(errors)) {
        const errorElement = document.getElementById(`error_${field}`);
        if (errorElement) {
            errorElement.textContent = Array.isArray(messages) ? messages[0] : messages;
            errorElement.classList.remove('hidden');
        }
    }
}

// Handle edit form submission
document.getElementById('editForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('editSubmitBtn');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i>Saving...';
    
    clearErrors('edit');

    const formData = {
        name: document.getElementById('edit_name').value,
        email: document.getElementById('edit_email').value,
        phone_number: document.getElementById('edit_phone').value,
    };

    console.log('Submitting profile update:', formData);

    try {
        const response = await fetch('{{ route("profile.update") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                ...formData,
                _method: 'PUT'
            })
        });

        console.log('Response status:', response.status);
        const data = await response.json();
        console.log('Response data:', data);

        if (response.ok && data.success) {
            // Update displayed information
            document.getElementById('display-name').textContent = formData.name;
            document.getElementById('display-email').textContent = formData.email;
            document.getElementById('display-phone').textContent = formData.phone_number || 'Not provided';
            
            closeEditModal();
            showToast('success', 'Success!', data.message || 'Your personal information has been updated successfully.');
            
            // Reload after 2 seconds
            setTimeout(() => location.reload(), 2000);
        } else if (response.status === 422 && data.errors) {
            showErrors(data.errors);
            showToast('error', 'Validation Error', 'Please check the form for errors.');
        } else {
            showToast('error', 'Error', data.message || 'Failed to update profile. Please try again.');
        }
    } catch (error) {
        console.error('Fetch error:', error);
        showToast('error', 'Error', 'An unexpected error occurred: ' + error.message);
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
});

// Handle password form submission
document.getElementById('passwordForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('passwordSubmitBtn');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i>Updating...';
    
    clearErrors('password');

    const formData = {
        current_password: document.getElementById('current_password').value,
        password: document.getElementById('new_password').value,
        password_confirmation: document.getElementById('password_confirmation').value,
    };

    try {
        const response = await fetch('{{ route("profile.update-password") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                ...formData,
                _method: 'PUT'
            })
        });

        const data = await response.json();

        if (response.ok && data.success) {
            closePasswordModal();
            showToast('success', 'Success!', data.message || 'Your password has been updated successfully.');
        } else if (response.status === 422 && data.errors) {
            showErrors(data.errors);
            showToast('error', 'Validation Error', 'Please check the form for errors.');
        } else {
            showToast('error', 'Error', data.message || 'Failed to update password. Please try again.');
        }
    } catch (error) {
        console.error('Fetch error:', error);
        showToast('error', 'Error', 'An unexpected error occurred: ' + error.message);
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
});
</script>
</body>
</html>
