<!-- Profile Modal Component -->
<div id="profileModalOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden items-center justify-center p-4">
    <div id="profileModalContent" class="bg-white rounded-lg shadow-xl max-w-3xl w-full max-h-screen overflow-y-auto">
        <!-- Modal Header -->
        <div class="p-4 border-b border-slate-200 flex items-center justify-between sticky top-0 bg-white">
            <h2 class="text-base font-bold text-navy">My Profile</h2>
            <button onclick="closeProfileModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-times text-lg"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div id="profileModalBody" class="p-4 space-y-4">
            <!-- Profile Header -->
            <div class="flex flex-col sm:flex-row sm:items-start gap-4">
                <div id="profileAvatar" class="w-16 h-16 rounded-lg bg-gradient-to-br from-brand to-brand-light flex items-center justify-center text-white flex-shrink-0">
                    <span class="text-2xl font-bold" id="profileInitials">AB</span>
                </div>
                <div class="flex-1">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <h1 class="text-lg font-bold text-navy" id="displayName">User Name</h1>
                            <p class="text-xs text-slate-600 mb-0.5" id="displayEmail">user@email.com</p>
                            <p class="text-xs text-slate-600 mb-2" id="displayPhone">Not provided</p>
                        </div>
                        <button onclick="openEditModalFromProfile()" class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-brand text-white rounded-lg text-xs font-medium hover:bg-brand-dark transition-colors flex-shrink-0">
                            <i class="fa-solid fa-edit text-xs"></i>
                            <span>Edit</span>
                        </button>
                    </div>
                    <div id="profileRoles" class="flex flex-wrap gap-1.5 mb-2"></div>
                    <div id="profileStatus"></div>
                </div>
            </div>

            <!-- Organizational Information -->
            <div>
                <h2 class="text-sm font-bold text-navy mb-2 flex items-center gap-2">
                    <i class="fa-solid fa-building text-brand text-sm"></i> Organizational Information
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-xs">
                    <div>
                        <label class="font-semibold text-slate-500 uppercase tracking-wide">Organization</label>
                        <p id="orgName" class="text-slate-700 mt-0.5">Not assigned</p>
                    </div>
                    <div>
                        <label class="font-semibold text-slate-500 uppercase tracking-wide">Province</label>
                        <p id="provName" class="text-slate-700 mt-0.5">Not assigned</p>
                    </div>
                    <div>
                        <label class="font-semibold text-slate-500 uppercase tracking-wide">District</label>
                        <p id="distName" class="text-slate-700 mt-0.5">Not assigned</p>
                    </div>
                    <div>
                        <label class="font-semibold text-slate-500 uppercase tracking-wide">Facility</label>
                        <p id="facName" class="text-slate-700 mt-0.5">Not assigned</p>
                    </div>
                    <div>
                        <label class="font-semibold text-slate-500 uppercase tracking-wide">Department</label>
                        <p id="depName" class="text-slate-700 mt-0.5">Not assigned</p>
                    </div>
                    <div>
                        <label class="font-semibold text-slate-500 uppercase tracking-wide">Designation</label>
                        <p id="desName" class="text-slate-700 mt-0.5">Not assigned</p>
                    </div>
                </div>
            </div>

            <!-- Account Information -->
            <div>
                <div class="flex items-start justify-between mb-2">
                    <h2 class="text-sm font-bold text-navy flex items-center gap-2">
                        <i class="fa-solid fa-info-circle text-brand text-sm"></i> Account Information
                    </h2>
                    <button onclick="openPasswordModalFromProfile()" class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-brand text-white rounded-lg text-xs font-medium hover:bg-brand-dark transition-colors">
                        <i class="fa-solid fa-lock text-xs"></i>
                        <span>Change Password</span>
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-xs">
                    <div>
                        <label class="font-semibold text-slate-500 uppercase tracking-wide">Member Since</label>
                        <p id="memberSince" class="text-slate-700 mt-0.5">-</p>
                    </div>
                    <div>
                        <label class="font-semibold text-slate-500 uppercase tracking-wide">Last Updated</label>
                        <p id="lastUpdated" class="text-slate-700 mt-0.5">-</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal (Same as before but nested in profile modal) -->
<div id="editModalFromProfile" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-xl w-full max-h-screen overflow-y-auto">
        <div class="p-4 border-b border-slate-200 flex items-center justify-between sticky top-0 bg-white">
            <h2 class="text-base font-bold text-navy flex items-center gap-2">
                <i class="fa-solid fa-edit text-brand text-sm"></i> Edit Personal Information
            </h2>
            <button onclick="closeEditModalFromProfile()" class="text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-times text-lg"></i>
            </button>
        </div>

        <form id="editFormFromProfile" class="p-4 space-y-3">
            <div>
                <label for="edit_name_modal" class="block text-xs font-semibold text-slate-700 mb-1.5">Full Name</label>
                <input type="text" id="edit_name_modal" name="name" required class="w-full px-3 py-1.5 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent transition-all text-sm">
                <span class="error_edit_modal text-red-600 text-xs mt-0.5 hidden" data-field="name"></span>
            </div>

            <div>
                <label for="edit_email_modal" class="block text-xs font-semibold text-slate-700 mb-1.5">Email Address</label>
                <input type="email" id="edit_email_modal" name="email" required class="w-full px-3 py-1.5 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent transition-all text-sm">
                <span class="error_edit_modal text-red-600 text-xs mt-0.5 hidden" data-field="email"></span>
            </div>

            <div>
                <label for="edit_phone_modal" class="block text-xs font-semibold text-slate-700 mb-1.5">Phone Number</label>
                <input type="tel" id="edit_phone_modal" name="phone_number" class="w-full px-3 py-1.5 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent transition-all text-sm">
                <span class="error_edit_modal text-red-600 text-xs mt-0.5 hidden" data-field="phone_number"></span>
            </div>

            <div class="flex gap-2 pt-3 border-t border-slate-200">
                <button type="button" onclick="closeEditModalFromProfile()" class="flex-1 px-3 py-1.5 border border-slate-200 rounded-lg text-slate-700 font-medium hover:bg-slate-50 transition-colors text-sm">
                    Cancel
                </button>
                <button type="submit" id="editSubmitBtnModal" class="flex-1 px-3 py-1.5 bg-brand text-white rounded-lg font-medium hover:bg-brand-dark transition-colors text-sm">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Password Modal -->
<div id="passwordModalFromProfile" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-xl w-full">
        <div class="p-4 border-b border-slate-200 flex items-center justify-between">
            <h2 class="text-base font-bold text-navy flex items-center gap-2">
                <i class="fa-solid fa-lock text-brand text-sm"></i> Change Password
            </h2>
            <button onclick="closePasswordModalFromProfile()" class="text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-times text-lg"></i>
            </button>
        </div>

        <form id="passwordFormFromProfile" class="p-4 space-y-3">
            <div>
                <label for="current_password_modal" class="block text-xs font-semibold text-slate-700 mb-1.5">Current Password</label>
                <input type="password" id="current_password_modal" name="current_password" required class="w-full px-3 py-1.5 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent transition-all text-sm">
                <span class="error_password_modal text-red-600 text-xs mt-0.5 hidden" data-field="current_password"></span>
            </div>

            <div>
                <label for="new_password_modal" class="block text-xs font-semibold text-slate-700 mb-1.5">New Password</label>
                <input type="password" id="new_password_modal" name="password" required minlength="8" class="w-full px-3 py-1.5 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent transition-all text-sm">
                <span class="error_password_modal text-red-600 text-xs mt-0.5 hidden" data-field="password"></span>
            </div>

            <div>
                <label for="password_confirmation_modal" class="block text-xs font-semibold text-slate-700 mb-1.5">Confirm New Password</label>
                <input type="password" id="password_confirmation_modal" name="password_confirmation" required minlength="8" class="w-full px-3 py-1.5 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent transition-all text-sm">
                <span class="error_password_modal text-red-600 text-xs mt-0.5 hidden" data-field="password_confirmation"></span>
            </div>

            <div class="flex gap-2 pt-3 border-t border-slate-200">
                <button type="button" onclick="closePasswordModalFromProfile()" class="flex-1 px-3 py-1.5 border border-slate-200 rounded-lg text-slate-700 font-medium hover:bg-slate-50 transition-colors text-sm">
                    Cancel
                </button>
                <button type="submit" id="passwordSubmitBtnModal" class="flex-1 px-3 py-1.5 bg-brand text-white rounded-lg font-medium hover:bg-brand-dark transition-colors text-sm">
                    Update Password
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const profileCsrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

// Profile Modal Functions
async function openProfileModal() {
    try {
        const response = await fetch('<?php echo e(route("profile.data")); ?>', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': profileCsrfToken
            }
        });

        if (!response.ok) throw new Error('Failed to load profile');
        const data = await response.json();

        // Populate profile data
        populateProfileModal(data);
        
        // Show modal
        document.getElementById('profileModalOverlay').classList.remove('hidden');
        document.getElementById('profileModalOverlay').classList.add('flex');
    } catch (error) {
        console.error('Error loading profile:', error);
        showProfileToast('error', 'Error', 'Failed to load profile data');
    }
}

function closeProfileModal() {
    document.getElementById('profileModalOverlay').classList.add('hidden');
    document.getElementById('profileModalOverlay').classList.remove('flex');
}

function populateProfileModal(data) {
    const user = data.user;

    // Update profile header
    document.getElementById('profileInitials').textContent = user.initials;
    document.getElementById('displayName').textContent = user.name;
    document.getElementById('displayEmail').textContent = user.email;
    document.getElementById('displayPhone').textContent = user.phone_number || 'Not provided';
    document.getElementById('memberSince').textContent = user.created_at;
    document.getElementById('lastUpdated').textContent = user.updated_at;

    // Update roles
    const rolesHtml = data.roles.map(role => 
        `<span class="inline-flex items-center px-2 py-0.5 rounded-full bg-brand-light bg-opacity-20 text-brand font-semibold text-xs">${role.display_name}</span>`
    ).join('');
    document.getElementById('profileRoles').innerHTML = rolesHtml;

    // Update status
    let statusHtml = '';
    if (user.status === 'approved') {
        statusHtml = '<span class="inline-flex items-center px-2 py-0.5 rounded-full bg-green-50 text-green-700 font-semibold text-xs"><i class="fa-solid fa-check text-xs mr-0.5"></i> Approved</span>';
    } else if (user.status === 'pending') {
        statusHtml = '<span class="inline-flex items-center px-2 py-0.5 rounded-full bg-yellow-50 text-yellow-700 font-semibold text-xs"><i class="fa-solid fa-hourglass-half text-xs mr-0.5"></i> Pending</span>';
    } else if (user.status === 'rejected') {
        statusHtml = '<span class="inline-flex items-center px-2 py-0.5 rounded-full bg-red-50 text-red-700 font-semibold text-xs"><i class="fa-solid fa-times text-xs mr-0.5"></i> Rejected</span>';
    }
    document.getElementById('profileStatus').innerHTML = statusHtml;

    // Update organizational info
    document.getElementById('orgName').textContent = data.organization?.name || 'Not assigned';
    document.getElementById('provName').textContent = data.province?.name || 'Not assigned';
    document.getElementById('distName').textContent = data.district?.name || 'Not assigned';
    document.getElementById('facName').textContent = data.facility?.name || 'Not assigned';
    document.getElementById('depName').textContent = data.department?.name || 'Not assigned';
    document.getElementById('desName').textContent = data.designation?.name || 'Not assigned';
}

// Edit Modal Functions
function openEditModalFromProfile() {
    const name = document.getElementById('displayName').textContent;
    const email = document.getElementById('displayEmail').textContent;
    const phone = document.getElementById('displayPhone').textContent === 'Not provided' ? '' : document.getElementById('displayPhone').textContent;

    document.getElementById('edit_name_modal').value = name;
    document.getElementById('edit_email_modal').value = email;
    document.getElementById('edit_phone_modal').value = phone;

    document.getElementById('editModalFromProfile').classList.remove('hidden');
    document.getElementById('editModalFromProfile').classList.add('flex');
}

function closeEditModalFromProfile() {
    document.getElementById('editModalFromProfile').classList.add('hidden');
    document.getElementById('editModalFromProfile').classList.remove('flex');
    document.querySelectorAll('.error_edit_modal').forEach(el => el.classList.add('hidden'));
}

// Password Modal Functions
function openPasswordModalFromProfile() {
    document.getElementById('passwordModalFromProfile').classList.remove('hidden');
    document.getElementById('passwordModalFromProfile').classList.add('flex');
    document.getElementById('passwordFormFromProfile').reset();
}

function closePasswordModalFromProfile() {
    document.getElementById('passwordModalFromProfile').classList.add('hidden');
    document.getElementById('passwordModalFromProfile').classList.remove('flex');
    document.querySelectorAll('.error_password_modal').forEach(el => el.classList.add('hidden'));
}

// Toast for profile modal
function showProfileToast(type, title, message) {
    const toast = document.createElement('div');
    toast.className = 'fixed bottom-4 right-4 z-50 rounded-lg shadow-lg p-4 flex gap-3 min-w-80';
    
    if (type === 'success') {
        toast.className += ' bg-green-50 border border-green-200';
        toast.innerHTML = `
            <i class="fa-solid fa-check-circle text-green-600 text-xl flex-shrink-0"></i>
            <div class="flex-1">
                <p class="font-semibold text-green-900">${title}</p>
                <p class="text-sm text-green-800">${message}</p>
            </div>
        `;
    } else {
        toast.className += ' bg-red-50 border border-red-200';
        toast.innerHTML = `
            <i class="fa-solid fa-exclamation-circle text-red-600 text-xl flex-shrink-0"></i>
            <div class="flex-1">
                <p class="font-semibold text-red-900">${title}</p>
                <p class="text-sm text-red-800">${message}</p>
            </div>
        `;
    }

    document.body.appendChild(toast);
    
    if (type === 'success') {
        setTimeout(() => toast.remove(), 4000);
    }
}

// Edit form submission
document.getElementById('editFormFromProfile')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('editSubmitBtnModal');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i>Saving...';

    const formData = {
        name: document.getElementById('edit_name_modal').value,
        email: document.getElementById('edit_email_modal').value,
        phone_number: document.getElementById('edit_phone_modal').value,
    };

    try {
        const response = await fetch('<?php echo e(route("profile.update")); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': profileCsrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ ...formData, _method: 'PUT' })
        });

        const data = await response.json();

        if (response.ok && data.success) {
            closeEditModalFromProfile();
            showProfileToast('success', 'Success!', 'Your personal information has been updated.');
            setTimeout(() => openProfileModal(), 500);
        } else if (response.status === 422 && data.errors) {
            document.querySelectorAll('.error_edit_modal').forEach(el => {
                el.classList.add('hidden');
                el.textContent = '';
            });
            Object.entries(data.errors).forEach(([field, messages]) => {
                const errorEl = document.querySelector(`.error_edit_modal[data-field="${field}"]`);
                if (errorEl) {
                    errorEl.textContent = Array.isArray(messages) ? messages[0] : messages;
                    errorEl.classList.remove('hidden');
                }
            });
        } else {
            showProfileToast('error', 'Error', data.message || 'Failed to update profile');
        }
    } catch (error) {
        showProfileToast('error', 'Error', 'An unexpected error occurred');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
});

// Password form submission
document.getElementById('passwordFormFromProfile')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('passwordSubmitBtnModal');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i>Updating...';

    const formData = {
        current_password: document.getElementById('current_password_modal').value,
        password: document.getElementById('new_password_modal').value,
        password_confirmation: document.getElementById('password_confirmation_modal').value,
    };

    try {
        const response = await fetch('<?php echo e(route("profile.update-password")); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': profileCsrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ ...formData, _method: 'PUT' })
        });

        const data = await response.json();

        if (response.ok && data.success) {
            closePasswordModalFromProfile();
            showProfileToast('success', 'Success!', 'Your password has been updated.');
        } else if (response.status === 422 && data.errors) {
            document.querySelectorAll('.error_password_modal').forEach(el => {
                el.classList.add('hidden');
                el.textContent = '';
            });
            Object.entries(data.errors).forEach(([field, messages]) => {
                const errorEl = document.querySelector(`.error_password_modal[data-field="${field}"]`);
                if (errorEl) {
                    errorEl.textContent = Array.isArray(messages) ? messages[0] : messages;
                    errorEl.classList.remove('hidden');
                }
            });
        } else {
            showProfileToast('error', 'Error', data.message || 'Failed to update password');
        }
    } catch (error) {
        showProfileToast('error', 'Error', 'An unexpected error occurred');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
});
</script>
<?php /**PATH D:\OccupationalHealth System\resources\views/components/profile-modal.blade.php ENDPATH**/ ?>