<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Broadcast Message · System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { margin: 0; background: #f5f7fa; color: #263149; font: 14px Inter, 'Segoe UI', Arial, sans-serif; }
        .page { max-width: 1000px; margin: auto; padding: 35px 25px 70px; }
        
        /* Header */
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 25px; }
        .head { display: flex; gap: 14px; }
        .icon { width: 46px; height: 46px; border-radius: 12px; background: #e9f8f3; color: #079b6d; display: grid; place-items: center; font-size: 22px; }
        .title { margin: 0; color: #101a31; font-size: 24px; font-weight: 750; }
        .sub { margin-top: 5px; color: #718096; font-size: 13px; }
        .back { padding: 10px 17px; border: 1px solid #ccd6df; border-radius: 8px; background: white; color: #101a31; font-weight: 650; font-size: 12px; text-decoration: none; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px; }
        .back:hover { background: #f8fafb; }
        
        /* Card */
        .card { background: #fff; border: 1px solid #e1e7ee; border-radius: 14px; box-shadow: 0 8px 25px rgba(16,26,49,.06); overflow: hidden; margin-bottom: 22px; }
        .chead { padding: 20px 24px; border-bottom: 1px solid #e1e7ee; }
        .ctitle { color: #101a31; font-size: 15px; font-weight: 700; }
        .cdesc { color: #718096; font-size: 13px; margin-top: 4px; }
        .cbody { padding: 24px; }
        
        /* Form */
        .form-group { margin-bottom: 20px; }
        .label { display: block; margin-bottom: 8px; color: #263149; font-size: 13px; font-weight: 600; }
        .required { color: #ef4444; }
        .input, .select, .textarea { width: 100%; padding: 11px 14px; border: 1px solid #d1d9e1; border-radius: 8px; font-size: 14px; font-family: inherit; transition: all 0.2s; }
        .input:focus, .select:focus, .textarea:focus { outline: none; border-color: #079b6d; box-shadow: 0 0 0 3px rgba(7,155,109,.1); }
        .textarea { min-height: 150px; resize: vertical; }
        .help-text { margin-top: 6px; color: #718096; font-size: 12px; }
        
        /* Radio buttons */
        .radio-group { display: flex; gap: 16px; margin-top: 10px; }
        .radio-label { display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 10px 14px; border: 1px solid #d1d9e1; border-radius: 8px; transition: all 0.2s; }
        .radio-label:hover { background: #f8fafb; }
        .radio-label input { cursor: pointer; }
        .radio-label input:checked + span { font-weight: 600; color: #079b6d; }
        
        /* Multi-select */
        .multi-select { max-height: 300px; overflow-y: auto; border: 1px solid #d1d9e1; border-radius: 8px; padding: 10px; }
        .user-item { padding: 8px 12px; border-radius: 6px; margin-bottom: 4px; display: flex; align-items: center; gap: 10px; transition: background 0.2s; }
        .user-item:hover { background: #f8fafb; }
        .user-item input { cursor: pointer; }
        .user-item label { cursor: pointer; flex: 1; font-size: 13px; }
        
        /* Buttons */
        .btn { padding: 12px 24px; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; }
        .btn-primary { background: #079b6d; color: white; }
        .btn-primary:hover { background: #068158; }
        .btn-secondary { background: #e1e7ee; color: #263149; }
        .btn-secondary:hover { background: #d1d9e1; }
        
        /* Alert */
        .alert { padding: 14px 18px; border-radius: 8px; margin-bottom: 20px; font-size: 13px; }
        .alert-error { background: #fee; color: #c00; border: 1px solid #fcc; }
        .alert-success { background: #efe; color: #060; border: 1px solid #cfc; }
        
        /* Character counter */
        .char-counter { text-align: right; color: #718096; font-size: 12px; margin-top: 6px; }
        .char-counter.warning { color: #f59e0b; }
        .char-counter.danger { color: #ef4444; }
    </style>
</head>
<body>
<div class="page">
    <!-- Header -->
    <div class="header">
        <div class="head">
            <div class="icon">
                <i class="fa-solid fa-bullhorn"></i>
            </div>
            <div>
                <h1 class="title">Broadcast Message</h1>
                <p class="sub">Send email or SMS to one or multiple users</p>
            </div>
        </div>
        <a href="{{ route('dashboards.super_admin') }}" class="back">
            <i class="fa-solid fa-arrow-left"></i>
            Back to Dashboard
        </a>
    </div>

    @if($errors->any())
    <div class="alert alert-error">
        <strong>Error:</strong>
        <ul style="margin: 8px 0 0 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('system.broadcast_message.send') }}" method="POST" x-data="{
        channel: 'email',
        recipientType: 'single',
        message: '',
        smsLimit: 160,
        get charCount() { return this.message.length },
        get charsRemaining() { return this.smsLimit - this.charCount },
        get counterClass() {
            if (this.channel !== 'sms') return '';
            if (this.charsRemaining < 0) return 'danger';
            if (this.charsRemaining < 20) return 'warning';
            return '';
        }
    }">
        @csrf

        <!-- Channel & Recipient Selection -->
        <div class="card">
            <div class="chead" style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h2 class="ctitle">1. Channel & Recipients</h2>
                    <p class="cdesc">Select delivery method and who will receive this message</p>
                </div>
                <div>
                    <label class="label" style="margin-bottom: 8px; font-size: 12px; text-align: right;">Delivery Channel <span class="required">*</span></label>
                    <div class="radio-group" style="margin-top: 0;">
                        <label class="radio-label">
                            <input type="radio" name="channel" value="email" x-model="channel" checked />
                            <span><i class="fa-solid fa-envelope"></i> Email</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="channel" value="sms" x-model="channel" />
                            <span><i class="fa-solid fa-sms"></i> SMS</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="cbody">
                <!-- Recipient Type -->
                <div class="form-group">
                    <label class="label">Recipient Type <span class="required">*</span></label>
                    <div class="radio-group">
                        <label class="radio-label">
                            <input type="radio" name="recipient_type" value="single" x-model="recipientType" checked />
                            <span>Single User</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="recipient_type" value="multiple" x-model="recipientType" />
                            <span>Multiple Users</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="recipient_type" value="all" x-model="recipientType" />
                            <span>All Users</span>
                        </label>
                    </div>
                </div>

                <!-- Single User Select -->
                <div x-show="recipientType === 'single'" x-transition class="form-group">
                    <label class="label">Select User <span class="required">*</span></label>
                    <select name="recipient_id" class="select">
                        <option value="">-- Choose User --</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}">
                            {{ $user->name }} 
                            @if($user->email) ({{ $user->email }}) @endif
                            @if($user->phone_number) - {{ $user->phone_number }} @endif
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Multiple Users Select -->
                <div x-show="recipientType === 'multiple'" x-transition>
                    <label class="label">Select Users <span class="required">*</span></label>
                    <div class="multi-select">
                        @foreach($users as $user)
                        <div class="user-item">
                            <input type="checkbox" name="recipient_ids[]" value="{{ $user->id }}" id="user_{{ $user->id }}" />
                            <label for="user_{{ $user->id }}">
                                <strong>{{ $user->name }}</strong>
                                @if($user->email) <span style="color: #718096;">• {{ $user->email }}</span> @endif
                                @if($user->phone_number) <span style="color: #718096;">• {{ $user->phone_number }}</span> @endif
                            </label>
                        </div>
                        @endforeach
                    </div>
                    <p class="help-text">Select multiple users by checking the boxes</p>
                </div>

                <!-- All Users Notice -->
                <div x-show="recipientType === 'all'" x-transition>
                    <div style="background: #fef3c7; border: 1px solid #fbbf24; border-radius: 8px; padding: 14px;">
                        <i class="fa-solid fa-exclamation-triangle" style="color: #f59e0b;"></i>
                        <strong>Warning:</strong> This will send the message to all {{ $users->count() }} approved users in the system.
                    </div>
                </div>
            </div>
        </div>

        <!-- Message Content -->
        <div class="card">
            <div class="chead">
                <h2 class="ctitle">2. Compose Message</h2>
                <p class="cdesc">Write your message content</p>
            </div>
            <div class="cbody">
                <!-- Subject (Email Only) -->
                <div x-show="channel === 'email'" x-transition class="form-group">
                    <label class="label">Subject <span class="required">*</span></label>
                    <input type="text" name="subject" class="input" placeholder="Enter email subject" value="{{ old('subject') }}" />
                </div>

                <!-- Message -->
                <div class="form-group">
                    <label class="label">Message <span class="required">*</span></label>
                    <textarea name="message" class="textarea" placeholder="Type your message here..." x-model="message" required>{{ old('message') }}</textarea>
                    <div class="char-counter" :class="counterClass" x-show="channel === 'sms'">
                        <span x-text="charCount"></span> characters
                        <span x-show="charCount > 0"> • </span>
                        <span x-show="charCount > 0" x-text="charsRemaining + ' remaining'"></span>
                        <span x-show="charsRemaining < 0" style="font-weight: 600;"> (Message too long!)</span>
                    </div>
                    <p class="help-text" x-show="channel === 'sms'">
                        <i class="fa-solid fa-info-circle"></i> SMS messages are limited to 160 characters
                    </p>
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div style="display: flex; gap: 12px; justify-content: flex-end;">
            <a href="{{ route('dashboards.super_admin') }}" class="btn btn-secondary">
                <i class="fa-solid fa-times"></i>
                Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-paper-plane"></i>
                Send Broadcast
            </button>
        </div>
    </form>
</div>
</body>
</html>
