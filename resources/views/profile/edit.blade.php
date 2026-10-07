<!DOCTYPE html>
<html lang="en">
<head>
    <script>window.FontAwesomeConfig = {autoReplaceSvg: 'nest'};</script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Profile — Health Service Commission</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Inter"', 'sans-serif']
                    },
                    colors: {
                        brand: {
                            DEFAULT: '#0d7a3d',
                            light: '#1bb74d',
                            dark: '#0a5f30',
                        },
                        navy: '#1e3a5f',
                        surface: '#f8fafc',
                        border: '#e8ecf1',
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
<body class="bg-surface text-slate-800 font-sans antialiased min-h-screen selection:bg-brand-light selection:text-white">
<div class="min-h-screen py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <!-- Back Button -->
        <a href="{{ route('profile.show') }}" class="inline-flex items-center gap-2 mb-6 text-brand hover:text-brand-dark transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
            <span class="text-sm font-medium">Back to Profile</span>
        </a>

        <!-- Edit Form -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-6">
            <h1 class="text-2xl font-bold text-navy mb-6">Edit Personal Information</h1>

            @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-sm font-semibold text-red-700 mb-2">Please fix the following errors:</p>
                <ul class="text-sm text-red-600 space-y-1">
                    @foreach($errors->all() as $error)
                    <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent transition-all" placeholder="Enter your full name">
                    @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent transition-all" placeholder="Enter your email">
                    @error('email')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone_number" class="block text-sm font-semibold text-slate-700 mb-2">Phone Number</label>
                    <input type="tel" id="phone_number" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent transition-all" placeholder="Enter your phone number">
                    @error('phone_number')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3 pt-4">
                    <a href="{{ route('profile.show') }}" class="flex-1 px-4 py-2 border border-slate-200 rounded-lg text-slate-700 font-medium hover:bg-slate-50 transition-colors text-center">
                        Cancel
                    </a>
                    <button type="submit" class="flex-1 px-4 py-2 bg-brand text-white rounded-lg font-medium hover:bg-brand-dark transition-colors">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
