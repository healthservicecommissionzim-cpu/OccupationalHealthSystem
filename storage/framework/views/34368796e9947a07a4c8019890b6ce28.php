<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident Report</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #1D4533;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #1D4533;
            font-size: 24px;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
            font-size: 12px;
        }
        .reference-number {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-weight: bold;
            color: #1D4533;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            background: #1D4533;
            color: white;
            padding: 10px 15px;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .row {
            display: flex;
            margin-bottom: 12px;
        }
        .label {
            flex: 0 0 30%;
            font-weight: bold;
            color: #1D4533;
            font-size: 12px;
        }
        .value {
            flex: 1;
            color: #333;
            font-size: 12px;
            word-break: break-word;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-badge.pending { background: #fef3c7; color: #92400e; }
        .status-badge.verified { background: #dbeafe; color: #0c4a6e; }
        .status-badge.investigating { background: #fce7f3; color: #831843; }
        .status-badge.resolved { background: #dcfce7; color: #15803d; }
        .divider {
            border-top: 1px solid #e0e0e0;
            margin: 20px 0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            font-size: 11px;
            color: #999;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Occupational Health & Safety System</h1>
            <p>Incident Report</p>
        </div>

        <div class="reference-number">
            Reference Number: <?php echo e($incident->reference_number); ?>

        </div>

        <!-- Injured Person Section -->
        <div class="section">
            <div class="section-title">Injured Person Information</div>
            <div class="row">
                <div class="label">Name:</div>
                <div class="value"><?php echo e($incident->first_name); ?> <?php echo e($incident->surname); ?></div>
            </div>
            <div class="row">
                <div class="label">Designation:</div>
                <div class="value"><?php echo e($incident->designation->name ?? 'N/A'); ?></div>
            </div>
            <div class="row">
                <div class="label">Department:</div>
                <div class="value"><?php echo e($incident->department->name ?? 'N/A'); ?></div>
            </div>
            <div class="row">
                <div class="label">Phone:</div>
                <div class="value"><?php echo e($incident->phone_number); ?></div>
            </div>
            <div class="row">
                <div class="label">Email:</div>
                <div class="value"><?php echo e($incident->email); ?></div>
            </div>
        </div>

        <div class="divider"></div>

        <!-- Incident Details Section -->
        <div class="section">
            <div class="section-title">Incident Details</div>
            <div class="row">
                <div class="label">Date:</div>
                <div class="value"><?php echo e($incident->incident_date->format('Y-m-d')); ?></div>
            </div>
            <div class="row">
                <div class="label">Time:</div>
                <div class="value"><?php echo e($incident->incident_time); ?></div>
            </div>
            <div class="row">
                <div class="label">Description:</div>
                <div class="value"><?php echo e($incident->incident_description); ?></div>
            </div>
            <div class="row">
                <div class="label">Status:</div>
                <div class="value">
                    <span class="status-badge <?php echo e(strtolower($incident->status)); ?>">
                        <?php echo e($incident->status); ?>

                    </span>
                </div>
            </div>
        </div>

        <div class="divider"></div>

        <!-- Location Section -->
        <div class="section">
            <div class="section-title">Location Information</div>
            <div class="row">
                <div class="label">Facility:</div>
                <div class="value"><?php echo e($incident->facility->name ?? 'N/A'); ?></div>
            </div>
            <div class="row">
                <div class="label">District:</div>
                <div class="value"><?php echo e($incident->district->name ?? 'N/A'); ?></div>
            </div>
            <div class="row">
                <div class="label">Province:</div>
                <div class="value"><?php echo e($incident->province->name ?? 'N/A'); ?></div>
            </div>
        </div>

        <div class="divider"></div>

        <!-- Supervisor Section -->
        <div class="section">
            <div class="section-title">Supervisor Information</div>
            <div class="row">
                <div class="label">Name:</div>
                <div class="value"><?php echo e($incident->supervisor_name); ?></div>
            </div>
            <div class="row">
                <div class="label">Designation:</div>
                <div class="value"><?php echo e($incident->supervisorDesignation->name ?? 'N/A'); ?></div>
            </div>
        </div>

        <?php if($incident->firstAidRecords->count() > 0): ?>
            <div class="divider"></div>

            <!-- First Aid Section -->
            <div class="section">
                <div class="section-title">First Aid Record</div>
                <?php $__currentLoopData = $incident->firstAidRecords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="row">
                        <div class="label">Nurse Name:</div>
                        <div class="value"><?php echo e($record->nurse_name); ?></div>
                    </div>
                    <div class="row">
                        <div class="label">License/EC Number:</div>
                        <div class="value"><?php echo e($record->nurse_license_number); ?></div>
                    </div>
                    <div class="row">
                        <div class="label">Injury Description:</div>
                        <div class="value"><?php echo e($record->injury_description); ?></div>
                    </div>
                    <div class="row">
                        <div class="label">First Aid Provided:</div>
                        <div class="value"><?php echo e($record->first_aid_description); ?></div>
                    </div>
                    <div class="row">
                        <div class="label">Time Administered:</div>
                        <div class="value"><?php echo e($record->time_administered->format('Y-m-d H:i')); ?></div>
                    </div>
                    <div class="row">
                        <div class="label">Location:</div>
                        <div class="value"><?php echo e($record->location); ?></div>
                    </div>
                    <div class="row">
                        <div class="label">Severity:</div>
                        <div class="value"><?php echo e(ucfirst($record->severity_level)); ?></div>
                    </div>
                    <?php if($record->notes): ?>
                        <div class="row">
                            <div class="label">Notes:</div>
                            <div class="value"><?php echo e($record->notes); ?></div>
                        </div>
                    <?php endif; ?>
                    <div class="row">
                        <div class="label">Status:</div>
                        <div class="value">
                            <span class="status-badge <?php echo e($record->status); ?>">
                                <?php echo e(ucfirst($record->status)); ?>

                            </span>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

        <div class="divider"></div>

        <!-- Timeline Section -->
        <div class="section">
            <div class="section-title">Timeline</div>
            <div class="row">
                <div class="label">Reported On:</div>
                <div class="value"><?php echo e($incident->created_at->format('Y-m-d H:i')); ?></div>
            </div>
            <div class="row">
                <div class="label">Last Updated:</div>
                <div class="value"><?php echo e($incident->updated_at->format('Y-m-d H:i')); ?></div>
            </div>
        </div>

        <div class="footer">
            <p>Generated on <?php echo e(now()->format('Y-m-d H:i')); ?></p>
            <p>Occupational Health & Safety System</p>
        </div>
    </div>
</body>
</html>
<?php /**PATH D:\OccupationalHealth System\resources\views\incidents\pdf.blade.php ENDPATH**/ ?>