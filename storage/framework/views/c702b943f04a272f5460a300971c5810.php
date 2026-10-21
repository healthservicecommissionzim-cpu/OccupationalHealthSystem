<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Communication Details</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css']); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        :root {
            --brand: #1D4533;
            --brand-dark: #0f2818;
            --brand-light: #f0fdf4;
        }
        body { font-family: 'Inter', sans-serif; color: #0f172a; background: #f9fafb; }
    </style>
</head>
<body>
<div class="px-4 py-6 max-w-4xl mx-auto">
    <!-- Back Button -->
    <a href="<?php echo e(route('system.communication_logs.index')); ?>" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 mb-6 font-medium">
        <i class="fa-solid fa-arrow-left"></i> Back to Communications
    </a>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-6">
            <div class="flex items-start justify-between gap-4 mb-4">
                <div>
                    <p class="text-sm opacity-90"><?php echo e($log->communication_type); ?></p>
                    <h1 class="text-3xl font-bold mt-1"><?php echo e($log->subject); ?></h1>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?php echo e($log->status_color); ?>">
                    <?php echo e($log->status); ?>

                </span>
            </div>
        </div>

        <!-- Body -->
        <div class="p-6 space-y-6">
            <!-- Summary Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="border border-slate-200 rounded-lg p-4">
                    <p class="text-xs text-slate-600 font-semibold uppercase mb-1">Type</p>
                    <p class="text-slate-900 font-medium"><?php echo e(str_replace('_', ' ', $log->communication_type)); ?></p>
                </div>
                <div class="border border-slate-200 rounded-lg p-4">
                    <p class="text-xs text-slate-600 font-semibold uppercase mb-1">Channel</p>
                    <div class="flex items-center gap-2">
                        <?php if($log->channel == 'email'): ?>
                            <i class="fa-solid fa-envelope text-sky-600"></i>
                        <?php elseif($log->channel == 'sms'): ?>
                            <i class="fa-solid fa-sms text-teal-600"></i>
                        <?php else: ?>
                            <i class="fa-solid fa-database text-slate-600"></i>
                        <?php endif; ?>
                        <span class="text-slate-900 font-medium"><?php echo e(ucfirst($log->channel)); ?></span>
                    </div>
                </div>
                <div class="border border-slate-200 rounded-lg p-4">
                    <p class="text-xs text-slate-600 font-semibold uppercase mb-1">Sent At</p>
                    <p class="text-slate-900 font-medium"><?php echo e($log->sent_at?->format('M d, Y H:i:s') ?? 'Not sent'); ?></p>
                </div>
            </div>

            <!-- Delivery Status Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-200">
                <div>
                    <p class="text-xs text-slate-600 font-semibold uppercase mb-2">Send Status</p>
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold <?php echo e(match($log->status) {
                        'SENT' => 'bg-green-100 text-green-800',
                        'FAILED' => 'bg-red-100 text-red-800',
                        'PENDING' => 'bg-yellow-100 text-yellow-800',
                        default => 'bg-gray-100 text-gray-800'
                    }); ?>">
                        <i class="fa-solid fa-<?php echo e($log->status == 'SENT' ? 'check' : ($log->status == 'FAILED' ? 'xmark' : 'clock')); ?>"></i>
                        <?php echo e($log->status); ?>

                    </span>
                </div>
                <div>
                    <p class="text-xs text-slate-600 font-semibold uppercase mb-2">Delivery Status</p>
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold <?php echo e(match($log->delivery_status ?? 'PENDING') {
                        'DELIVERED' => 'bg-emerald-100 text-emerald-800',
                        'SENT' => 'bg-sky-100 text-sky-800',
                        'PENDING' => 'bg-amber-100 text-amber-800',
                        'FAILED' => 'bg-red-100 text-red-800',
                        'BOUNCED' => 'bg-orange-100 text-orange-800',
                        'UNDELIVERABLE' => 'bg-rose-100 text-rose-800',
                        default => 'bg-gray-100 text-gray-800'
                    }); ?>">
                        <i class="fa-solid fa-<?php echo e($log->delivery_status == 'DELIVERED' ? 'check-double' : ($log->delivery_status == 'FAILED' ? 'exclamation' : ($log->delivery_status == 'PENDING' ? 'hourglass' : 'circle-exclamation'))); ?>"></i>
                        <?php echo e($log->delivery_status ?? 'PENDING'); ?>

                    </span>
                </div>
                <div>
                    <p class="text-xs text-slate-600 font-semibold uppercase mb-2">Attempts</p>
                    <p class="text-slate-900 font-bold text-lg"><?php echo e($log->delivery_attempts ?? 1); ?></p>
                </div>
                <div>
                    <p class="text-xs text-slate-600 font-semibold uppercase mb-2">Provider</p>
                    <p class="text-slate-900 font-medium text-sm"><?php echo e($log->provider ?? 'System'); ?></p>
                </div>
            </div>

            <!-- Timeline -->
            <div class="border border-slate-200 rounded-lg p-4">
                <h3 class="font-semibold text-slate-900 mb-4">Delivery Timeline</h3>
                <div class="space-y-3">
                    <?php if($log->first_attempt_at): ?>
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-3 h-3 rounded-full bg-blue-600 mt-1"></div>
                        </div>
                        <div>
                            <p class="text-xs text-slate-600 font-semibold uppercase">First Attempt</p>
                            <p class="text-slate-900 font-medium"><?php echo e($log->first_attempt_at->format('M d, Y H:i:s')); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if($log->delivered_at): ?>
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-3 h-3 rounded-full bg-emerald-600 mt-1"></div>
                        </div>
                        <div>
                            <p class="text-xs text-slate-600 font-semibold uppercase">Delivered</p>
                            <p class="text-slate-900 font-medium"><?php echo e($log->delivered_at->format('M d, Y H:i:s')); ?></p>
                        </div>
                    </div>
                    <?php elseif($log->delivery_status == 'FAILED' || $log->delivery_status == 'BOUNCED'): ?>
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-3 h-3 rounded-full bg-red-600 mt-1"></div>
                        </div>
                        <div>
                            <p class="text-xs text-slate-600 font-semibold uppercase">Delivery Failed</p>
                            <p class="text-slate-900 font-medium"><?php echo e($log->updated_at->format('M d, Y H:i:s')); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if($log->next_retry_at): ?>
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-3 h-3 rounded-full bg-yellow-600 mt-1"></div>
                        </div>
                        <div>
                            <p class="text-xs text-slate-600 font-semibold uppercase">Next Retry Scheduled</p>
                            <p class="text-slate-900 font-medium"><?php echo e($log->next_retry_at->format('M d, Y H:i:s')); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if($log->webhook_received_at): ?>
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-3 h-3 rounded-full bg-indigo-600 mt-1"></div>
                        </div>
                        <div>
                            <p class="text-xs text-slate-600 font-semibold uppercase">Webhook Received</p>
                            <p class="text-slate-900 font-medium"><?php echo e($log->webhook_received_at->format('M d, Y H:i:s')); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Recipient Information -->
            <div class="border border-slate-200 rounded-lg p-4">
                <h3 class="font-semibold text-slate-900 mb-3">Recipient Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php if($log->recipient_email): ?>
                    <div>
                        <p class="text-xs text-slate-600 font-semibold uppercase mb-1">Email</p>
                        <p class="text-slate-900"><?php echo e($log->recipient_email); ?></p>
                    </div>
                    <?php endif; ?>
                    <?php if($log->recipient_phone): ?>
                    <div>
                        <p class="text-xs text-slate-600 font-semibold uppercase mb-1">Phone</p>
                        <p class="text-slate-900"><?php echo e($log->recipient_phone); ?></p>
                    </div>
                    <?php endif; ?>
                    <?php if($log->recipient): ?>
                    <div>
                        <p class="text-xs text-slate-600 font-semibold uppercase mb-1">User</p>
                        <p class="text-slate-900"><?php echo e($log->recipient->name); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Message Content -->
            <div class="border border-slate-200 rounded-lg p-4">
                <h3 class="font-semibold text-slate-900 mb-3">Message Preview</h3>
                <div class="bg-slate-50 rounded p-4 text-slate-700 text-sm leading-relaxed">
                    <?php echo nl2br(e($log->message_preview)); ?>

                </div>
            </div>

            <!-- Full Content -->
            <?php if($log->full_content): ?>
            <div class="border border-slate-200 rounded-lg p-4">
                <h3 class="font-semibold text-slate-900 mb-3">Full Content</h3>
                <div class="bg-slate-50 rounded p-4 text-slate-700 text-sm leading-relaxed overflow-auto max-h-96">
                    <?php echo nl2br(e($log->full_content)); ?>

                </div>
            </div>
            <?php endif; ?>

            <!-- Failure Information -->
            <?php if($log->isDeliveryFailed()): ?>
            <div class="border-l-4 border-red-600 bg-red-50 rounded-lg p-4">
                <div class="flex gap-3">
                    <i class="fa-solid fa-exclamation-circle text-red-600 mt-1 flex-shrink-0"></i>
                    <div>
                        <h4 class="font-semibold text-red-900 mb-2">Delivery Failed</h4>
                        <?php if($log->failure_reason): ?>
                        <p class="text-red-700 text-sm mb-2"><strong>Reason:</strong> <?php echo e($log->failure_reason); ?></p>
                        <?php endif; ?>
                        <?php if($log->failure_code): ?>
                        <p class="text-red-700 text-sm mb-2"><strong>Error Code:</strong> <?php echo e($log->failure_code); ?></p>
                        <?php endif; ?>
                        <?php if($log->delivery_response): ?>
                        <p class="text-red-700 text-sm"><strong>Provider Response:</strong> <?php echo e(Str::limit($log->delivery_response, 100)); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Retry History -->
            <?php if($log->retry_history && count($log->retry_history) > 0): ?>
            <div class="border border-slate-200 rounded-lg p-4">
                <h3 class="font-semibold text-slate-900 mb-3">Retry History</h3>
                <div class="space-y-2">
                    <?php $__currentLoopData = $log->retry_history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $retry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="text-sm text-slate-600 p-2 bg-slate-50 rounded">
                        <span class="font-medium">Attempt <?php echo e($retry['attempt']); ?>:</span>
                        <?php echo e($retry['status']); ?> - <?php echo e($retry['reason'] ?? 'No reason'); ?>

                        <span class="text-xs text-slate-500"><?php echo e($retry['timestamp']); ?></span>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Provider Information -->
            <?php if($log->provider_message_id || $log->provider_metadata): ?>
            <div class="border border-slate-200 rounded-lg p-4 bg-slate-50">
                <h3 class="font-semibold text-slate-900 mb-3">Provider Details</h3>
                <div class="space-y-2 text-sm">
                    <?php if($log->provider_message_id): ?>
                    <p><strong>Message ID:</strong> <code class="bg-white px-2 py-1 rounded text-xs"><?php echo e($log->provider_message_id); ?></code></p>
                    <?php endif; ?>
                    <?php if($log->provider_metadata): ?>
                    <div>
                        <p><strong>Metadata:</strong></p>
                        <pre class="bg-white p-2 rounded text-xs overflow-auto max-h-48"><?php echo e(json_encode($log->provider_metadata, JSON_PRETTY_PRINT)); ?></pre>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Webhook Data -->
            <?php if($log->webhook_data): ?>
            <div class="border border-slate-200 rounded-lg p-4 bg-indigo-50">
                <h3 class="font-semibold text-slate-900 mb-3">Webhook Data</h3>
                <pre class="bg-white p-2 rounded text-xs overflow-auto max-h-48"><?php echo e(json_encode($log->webhook_data, JSON_PRETTY_PRINT)); ?></pre>
            </div>
            <?php endif; ?>

            <!-- Related Entity -->
            <?php if($log->related_entity_type && $log->related_entity_id): ?>
            <div class="border border-slate-200 rounded-lg p-4">
                <h3 class="font-semibold text-slate-900 mb-3">Related Entity</h3>
                <p class="text-slate-700">
                    <span class="font-medium"><?php echo e($log->related_entity_type); ?>:</span> #<?php echo e($log->related_entity_id); ?>

                </p>
            </div>
            <?php endif; ?>

            <!-- Metadata -->
            <div class="border border-slate-200 rounded-lg p-4 text-sm text-slate-600">
                <p><strong>Created:</strong> <?php echo e($log->created_at->format('M d, Y H:i:s')); ?></p>
                <p><strong>Updated:</strong> <?php echo e($log->updated_at->format('M d, Y H:i:s')); ?></p>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<?php /**PATH D:\OccupationalHealth System\resources\views/super-admin/communications/show.blade.php ENDPATH**/ ?>