@extends('layouts.dashboard')

@section('page_title', 'Git Commit Management')

@section('header_title', 'Git Commit Management')
@section('header_subtitle', 'Version Control & Code Deployment')

@section('sidebar_content')
<div class="px-5 pt-3 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Navigation</div>
<a href="{{ route('dashboards.super_admin') }}" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-brand transition-all font-medium text-[13px]">
    <i class="fa-solid fa-chart-line w-5 text-center text-sm"></i>
    <span>Master Dashboard</span>
</a>
<a href="{{ route('system.git_commit') }}" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg bg-white text-brand border-l-[3px] border-brand shadow-sm transition-all font-medium text-[13px]">
    <i class="fa-brands fa-git-alt w-5 text-center text-sm"></i>
    <span>Git Commit</span>
</a>
@endsection

@section('dashboard_content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
            <i class="fa-brands fa-git-alt text-orange-600"></i>
            Git Version Control
        </h1>
        <p class="text-gray-600 mt-1">Manage code commits and deployments to the repository</p>
    </div>

    <!-- Alert Container -->
    <div id="alert-container"></div>

    <!-- Repository Status -->
    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                <i class="fa-solid fa-code-branch text-blue-600"></i>
                Repository Status
            </h2>
            <button onclick="refreshStatus()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm flex items-center gap-2">
                <i class="fa-solid fa-refresh"></i> Refresh
            </button>
        </div>

        <div id="status-container" class="bg-slate-50 rounded-lg p-4 font-mono text-sm text-slate-700">
            <div class="flex items-center justify-center py-8">
                <i class="fa-solid fa-spinner fa-spin text-gray-400 text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Recent Commits -->
    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-purple-600"></i>
                Recent Commits (Last 10)
            </h2>
            <button onclick="refreshLog()" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-sm flex items-center gap-2">
                <i class="fa-solid fa-refresh"></i> Refresh
            </button>
        </div>

        <div id="log-container" class="bg-slate-50 rounded-lg p-4 font-mono text-sm text-slate-700">
            <div class="flex items-center justify-center py-8">
                <i class="fa-solid fa-spinner fa-spin text-gray-400 text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Commit Form -->
    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-plus-circle text-green-600"></i>
            Create New Commit
        </h2>

        <form id="commit-form" onsubmit="return handleCommit(event)">
            @csrf
            <div class="mb-4">
                <label for="commit-message" class="block text-sm font-medium text-gray-700 mb-2">
                    Commit Message <span class="text-red-500">*</span>
                </label>
                <textarea
                    id="commit-message"
                    name="message"
                    rows="3"
                    required
                    minlength="5"
                    maxlength="500"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                    placeholder="Enter a descriptive commit message (minimum 5 characters)..."
                ></textarea>
                <p class="text-xs text-gray-500 mt-1">Describe what changes you made in this commit</p>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="px-6 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium flex items-center gap-2">
                    <i class="fa-solid fa-check"></i>
                    Commit Changes
                </button>
                <button type="button" onclick="clearForm()" class="px-6 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
                    Clear
                </button>
            </div>
        </form>
    </div>

    <!-- Remote Operations -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Push -->
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center gap-2">
                <i class="fa-solid fa-cloud-arrow-up text-blue-600"></i>
                Push to Remote
            </h3>
            <p class="text-sm text-gray-600 mb-4">Upload your local commits to the remote repository</p>
            <button onclick="handlePush()" class="w-full px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium flex items-center justify-center gap-2">
                <i class="fa-solid fa-upload"></i>
                Push Changes
            </button>
        </div>

        <!-- Pull -->
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center gap-2">
                <i class="fa-solid fa-cloud-arrow-down text-orange-600"></i>
                Pull from Remote
            </h3>
            <p class="text-sm text-gray-600 mb-4">Download latest changes from the remote repository</p>
            <button onclick="handlePull()" class="w-full px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition font-medium flex items-center justify-center gap-2">
                <i class="fa-solid fa-download"></i>
                Pull Changes
            </button>
        </div>
    </div>
</div>

<script>
    // Show alert message
    function showAlert(message, type = 'success') {
        const alertContainer = document.getElementById('alert-container');
        const alertClass = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
        const iconClass = type === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation';
        
        alertContainer.innerHTML = `
            <div class="${alertClass} border-l-4 p-4 mb-6 rounded-lg" role="alert">
                <div class="flex items-center gap-3">
                    <i class="fa-solid ${iconClass} text-xl"></i>
                    <p class="font-medium">${message}</p>
                </div>
            </div>
        `;
        
        setTimeout(() => {
            alertContainer.innerHTML = '';
        }, 5000);
    }

    // Refresh git status
    async function refreshStatus() {
        const container = document.getElementById('status-container');
        container.innerHTML = '<div class="flex items-center justify-center py-8"><i class="fa-solid fa-spinner fa-spin text-gray-400 text-2xl"></i></div>';
        
        try {
            const response = await fetch('{{ route("system.git_commit.status") }}');
            const data = await response.json();
            
            if (data.success) {
                if (data.has_changes) {
                    container.innerHTML = `<pre class="whitespace-pre-wrap">${data.status || 'No changes detected'}</pre>`;
                } else {
                    container.innerHTML = '<p class="text-green-600 font-semibold">✓ Working tree clean - no changes to commit</p>';
                }
            } else {
                container.innerHTML = `<p class="text-red-600">Error: ${data.message}</p>`;
            }
        } catch (error) {
            container.innerHTML = `<p class="text-red-600">Failed to fetch status: ${error.message}</p>`;
        }
    }

    // Refresh git log
    async function refreshLog() {
        const container = document.getElementById('log-container');
        container.innerHTML = '<div class="flex items-center justify-center py-8"><i class="fa-solid fa-spinner fa-spin text-gray-400 text-2xl"></i></div>';
        
        try {
            const response = await fetch('{{ route("system.git_commit.log") }}');
            const data = await response.json();
            
            if (data.success) {
                container.innerHTML = `<pre class="whitespace-pre-wrap">${data.log || 'No commits found'}</pre>`;
            } else {
                container.innerHTML = `<p class="text-red-600">Error: ${data.message}</p>`;
            }
        } catch (error) {
            container.innerHTML = `<p class="text-red-600">Failed to fetch log: ${error.message}</p>`;
        }
    }

    // Handle commit
    async function handleCommit(event) {
        event.preventDefault();
        
        const message = document.getElementById('commit-message').value;
        const submitBtn = event.target.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Committing...';
        
        try {
            const response = await fetch('{{ route("system.git_commit.commit") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({ message })
            });
            
            const data = await response.json();
            
            if (data.success) {
                showAlert('Changes committed successfully!', 'success');
                document.getElementById('commit-message').value = '';
                refreshStatus();
                refreshLog();
            } else {
                showAlert(data.message, 'error');
            }
        } catch (error) {
            showAlert('Failed to commit changes: ' + error.message, 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
        
        return false;
    }

    // Handle push
    async function handlePush() {
        if (!confirm('Are you sure you want to push changes to the remote repository?')) {
            return;
        }
        
        showAlert('Pushing changes... Please wait.', 'success');
        
        try {
            const response = await fetch('{{ route("system.git_commit.push") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                showAlert('Changes pushed successfully!', 'success');
                refreshLog();
            } else {
                showAlert(data.message, 'error');
            }
        } catch (error) {
            showAlert('Failed to push changes: ' + error.message, 'error');
        }
    }

    // Handle pull
    async function handlePull() {
        if (!confirm('Are you sure you want to pull changes from the remote repository? This may overwrite local changes.')) {
            return;
        }
        
        showAlert('Pulling changes... Please wait.', 'success');
        
        try {
            const response = await fetch('{{ route("system.git_commit.pull") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                showAlert('Changes pulled successfully!', 'success');
                refreshStatus();
                refreshLog();
            } else {
                showAlert(data.message, 'error');
            }
        } catch (error) {
            showAlert('Failed to pull changes: ' + error.message, 'error');
        }
    }

    // Clear form
    function clearForm() {
        document.getElementById('commit-message').value = '';
    }

    // Load initial data on page load
    document.addEventListener('DOMContentLoaded', function() {
        refreshStatus();
        refreshLog();
    });
</script>
@endsection
