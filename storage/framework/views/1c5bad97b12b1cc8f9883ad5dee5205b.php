<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
  <title>System Thresholds · Configuration</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400,500,600,700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: #f1f5f9;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem 1rem;
    }

    .container {
      max-width: 1000px;
      width: 100%;
      margin: 0 auto;
    }

    /* header */
    .page-header {
      margin-bottom: 1.75rem;
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
    }

    .page-header h1 {
      font-size: 1.8rem;
      font-weight: 700;
      color: #0b1a2f;
      display: flex;
      align-items: center;
      gap: 0.75rem;
      letter-spacing: -0.02em;
    }

    .page-header h1 i {
      color: #1D4533;
      background: rgba(29, 69, 51, 0.08);
      padding: 0.5rem;
      border-radius: 12px;
    }

    .page-header p {
      color: #475569;
      font-weight: 500;
      font-size: 0.9rem;
    }

    .badge-pulse {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      background: #fef9c3;
      color: #854d0e;
      padding: 0.3rem 1rem;
      border-radius: 40px;
      font-size: 0.7rem;
      font-weight: 600;
      border: 1px solid #fde68a;
    }

    .badge-pulse i {
      font-size: 0.6rem;
    }

    /* warning banner */
    .alert-banner {
      background: #fefce8;
      border: 1px solid #fde68a;
      border-radius: 16px;
      padding: 1rem 1.5rem;
      display: flex;
      align-items: flex-start;
      gap: 1rem;
      margin-bottom: 2rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
    }

    .alert-banner i {
      color: #d97706;
      font-size: 1.3rem;
      margin-top: 0.1rem;
    }

    .alert-banner h4 {
      font-weight: 700;
      color: #92400e;
      font-size: 0.9rem;
      margin-bottom: 0.1rem;
    }

    .alert-banner p {
      color: #78350f;
      font-size: 0.85rem;
      font-weight: 500;
      line-height: 1.5;
      opacity: 0.9;
    }

    /* main card */
    .main-card {
      background: #ffffff;
      border-radius: 24px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
      border: 1px solid #eef2f6;
      overflow: hidden;
    }

    /* tab bar */
    .tab-bar {
      display: flex;
      flex-wrap: wrap;
      background: #fafcff;
      border-bottom: 2px solid #eef2f6;
      padding: 0 1.5rem;
      gap: 0.25rem;
    }

    .tab-btn {
      padding: 1rem 1.25rem;
      font-size: 0.8rem;
      font-weight: 600;
      color: #64748b;
      background: transparent;
      border: none;
      border-bottom: 3px solid transparent;
      cursor: pointer;
      transition: all 0.2s ease;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-family: 'Inter', sans-serif;
      letter-spacing: 0.2px;
      white-space: nowrap;
    }

    .tab-btn i {
      font-size: 0.9rem;
      opacity: 0.7;
    }

    .tab-btn:hover {
      color: #1D4533;
      background: rgba(29, 69, 51, 0.03);
    }

    .tab-btn.active {
      color: #1D4533;
      border-bottom-color: #1D4533;
      font-weight: 700;
    }

    .tab-btn.active i {
      opacity: 1;
    }

    .tab-btn .badge-tab {
      background: #e2e8f0;
      color: #475569;
      font-size: 0.6rem;
      padding: 0.1rem 0.5rem;
      border-radius: 20px;
      font-weight: 600;
    }

    .tab-btn.active .badge-tab {
      background: #1D4533;
      color: white;
    }

    /* content panel */
    .tab-content {
      padding: 2rem 2rem 2rem;
    }

    .tab-pane {
      display: none;
      animation: fadePane 0.25s ease;
    }

    .tab-pane.active {
      display: block;
    }

    @keyframes fadePane {
      from {
        opacity: 0;
        transform: translateY(6px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .pane-header {
      display: flex;
      align-items: center;
      gap: 1rem;
      margin-bottom: 1.75rem;
      padding-bottom: 1rem;
      border-bottom: 2px solid #f1f5f9;
    }

    .pane-icon {
      width: 44px;
      height: 44px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 12px;
      font-size: 1.3rem;
    }

    .pane-header-text h3 {
      font-size: 1.1rem;
      font-weight: 700;
      color: #0b1a2f;
      letter-spacing: -0.01em;
    }

    .pane-header-text p {
      font-size: 0.8rem;
      color: #64748b;
      font-weight: 500;
    }

    .control-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1.5rem;
      margin-bottom: 1.75rem;
    }

    .form-group {
      display: flex;
      flex-direction: column;
    }

    .form-group label {
      font-size: 0.7rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.4px;
      color: #475569;
      margin-bottom: 0.4rem;
    }

    .form-group input {
      padding: 0.7rem 1rem;
      border: 2px solid #e2e8f0;
      border-radius: 10px;
      font-size: 0.9rem;
      font-weight: 500;
      background: #fafcff;
      transition: all 0.2s ease;
      color: #0b1a2f;
      font-family: 'Inter', sans-serif;
    }

    .form-group input:focus {
      outline: none;
      border-color: #1D4533;
      background: #ffffff;
      box-shadow: 0 0 0 4px rgba(29, 69, 51, 0.06);
    }

    .help-text {
      font-size: 0.7rem;
      color: #64748b;
      margin-top: 0.4rem;
      font-weight: 500;
    }

    .preview-section {
      background: #f8fafc;
      border-radius: 14px;
      padding: 1.25rem 1.5rem;
      border: 1px solid #eef2f6;
      margin-top: 0.25rem;
    }

    .preview-title {
      font-size: 0.7rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.4px;
      color: #475569;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      margin-bottom: 0.75rem;
    }

    .preview-items {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
      gap: 1rem;
    }

    .preview-item {
      background: white;
      padding: 0.8rem 1rem;
      border-radius: 10px;
      border-left: 4px solid #1D4533;
    }

    .preview-label {
      font-size: 0.6rem;
      font-weight: 600;
      color: #64748b;
      text-transform: uppercase;
      letter-spacing: 0.3px;
    }

    .preview-value {
      font-size: 1rem;
      font-weight: 700;
      color: #1D4533;
      margin-top: 0.1rem;
    }

    .action-bar {
      display: flex;
      gap: 1rem;
      justify-content: flex-end;
      margin-top: 2rem;
      padding-top: 1.5rem;
      border-top: 2px solid #f1f5f9;
    }

    .btn {
      padding: 0.65rem 1.5rem;
      border: none;
      border-radius: 40px;
      font-weight: 600;
      font-size: 0.85rem;
      display: inline-flex;
      align-items: center;
      gap: 0.6rem;
      cursor: pointer;
      transition: all 0.2s ease;
      font-family: 'Inter', sans-serif;
    }

    .btn-primary {
      background: #1D4533;
      color: white;
      box-shadow: 0 4px 12px rgba(29, 69, 51, 0.15);
    }

    .btn-primary:hover {
      background: #14382a;
      transform: translateY(-1px);
      box-shadow: 0 6px 16px rgba(29, 69, 51, 0.2);
    }

    .btn-secondary {
      background: white;
      color: #475569;
      border: 1px solid #e2e8f0;
    }

    .btn-secondary:hover {
      background: #f8fafc;
      border-color: #cbd5e1;
    }

    /* responsiveness */
    @media (max-width: 640px) {
      .page-header {
        flex-direction: column;
        align-items: flex-start;
      }

      .tab-bar {
        padding: 0 0.5rem;
        overflow-x: auto;
        flex-wrap: nowrap;
        gap: 0;
      }

      .tab-btn {
        padding: 0.75rem 0.8rem;
        font-size: 0.7rem;
        white-space: nowrap;
      }

      .tab-btn span {
        display: none;
      }

      .tab-btn i {
        font-size: 1rem;
        margin-right: 0;
      }

      .tab-content {
        padding: 1.5rem;
      }

      .control-grid {
        grid-template-columns: 1fr;
      }

      .action-bar {
        flex-direction: column;
        align-items: stretch;
      }

      .btn {
        justify-content: center;
      }

      .alert-banner {
        flex-direction: column;
        padding: 1rem;
      }
    }

    @media (max-width: 480px) {
      .tab-btn {
        padding: 0.6rem 0.6rem;
        font-size: 0.65rem;
      }

      .tab-btn i {
        font-size: 0.9rem;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <!-- header -->
    <div class="page-header">
      <div>
        <h1><i class="fa-solid fa-sliders"></i> System Thresholds</h1>
        <p style="margin-top: 4px;">Configure incident workflow &amp; compliance rules</p>
      </div>
      <span class="badge-pulse"><i class="fa-solid fa-circle" style="color: #16a34a; font-size: 0.5rem;"></i> Live configuration</span>
    </div>

    <!-- warning -->
    <div class="alert-banner">
      <i class="fa-solid fa-triangle-exclamation"></i>
      <div>
        <h4>⚙️ Configuration Warning</h4>
        <p>Changes to threshold variables affect system-wide behavior. Ensure full compliance with legislation and organizational policies before modifying.</p>
      </div>
    </div>

    <!-- main card with tabs -->
    <div class="main-card">
      <!-- tab bar -->
      <div class="tab-bar" role="tablist">
        <button class="tab-btn active" data-tab="0" role="tab"><i class="fa-solid fa-calendar-days"></i> <span>Leave Limit</span> <span class="badge-tab">High</span></button>
        <button class="tab-btn" data-tab="1" role="tab"><i class="fa-solid fa-exclamation-triangle"></i> <span>Statutory Risk</span> <span class="badge-tab">High</span></button>
        <button class="tab-btn" data-tab="2" role="tab"><i class="fa-solid fa-bolt"></i> <span>Auto Escalate</span> <span class="badge-tab">Med</span></button>
        <button class="tab-btn" data-tab="3" role="tab"><i class="fa-solid fa-clock"></i> <span>Review SLA</span> <span class="badge-tab">Low</span></button>
        <button class="tab-btn" data-tab="4" role="tab"><i class="fa-solid fa-file-upload"></i> <span>Witness Window</span> <span class="badge-tab">Med</span></button>
      </div>

      <!-- tab content -->
      <div class="tab-content">
        <form id="thresholdForm">
          <!-- 0: Leave Limit -->
          <div class="tab-pane active" data-pane="0" role="tabpanel">
            <div class="pane-header">
              <div class="pane-icon" style="background: #fee2e2; color: #b91c1c;"><i class="fa-solid fa-calendar-days"></i></div>
              <div class="pane-header-text">
                <h3>Maximum Injury Leave</h3>
                <p>Configure the maximum number of days workers can take for injury-related leave</p>
              </div>
            </div>
            <div class="control-grid">
              <div class="form-group">
                <label for="leave_limit">Days Allowed</label>
                <input type="number" id="leave_limit" name="leave_limit" value="<?php echo e($thresholds['leave_limit']); ?>" placeholder="e.g., 90" />
                <div class="help-text">HSB Circular 3/2017 – Medical Board escalation threshold</div>
              </div>
            </div>
            <div class="preview-section">
              <div class="preview-title"><i class="fa-regular fa-lightbulb"></i> Impact Preview</div>
              <div class="preview-items">
                <div class="preview-item"><div class="preview-label">Escalation Trigger</div><div class="preview-value">After <span id="preview-leave"><?php echo e($thresholds['leave_limit']); ?></span> days</div></div>
                <div class="preview-item"><div class="preview-label">Auto Action</div><div class="preview-value">Medical Board Review</div></div>
              </div>
            </div>
          </div>

          <!-- 1: Statutory Risk -->
          <div class="tab-pane" data-pane="1" role="tabpanel">
            <div class="pane-header">
              <div class="pane-icon" style="background: #fef3c7; color: #b45309;"><i class="fa-solid fa-exclamation-triangle"></i></div>
              <div class="pane-header-text">
                <h3>Statutory Risk Threshold</h3>
                <p>Cases older than this age are flagged for compliance review</p>
              </div>
            </div>
            <div class="control-grid">
              <div class="form-group">
                <label for="statutory_risk_months">Months</label>
                <input type="number" id="statutory_risk_months" name="statutory_risk_months" value="<?php echo e($thresholds['statutory_risk_months']); ?>" placeholder="e.g., 9" />
                <div class="help-text">Used for quarterly compliance reporting to HSB</div>
              </div>
            </div>
            <div class="preview-section">
              <div class="preview-title"><i class="fa-regular fa-lightbulb"></i> Impact Preview</div>
              <div class="preview-items">
                <div class="preview-item"><div class="preview-label">Flag Cases After</div><div class="preview-value"><span id="preview-statutory"><?php echo e($thresholds['statutory_risk_months']); ?></span> months old</div></div>
                <div class="preview-item"><div class="preview-label">Reporting Cycle</div><div class="preview-value">Quarterly Audit</div></div>
              </div>
            </div>
          </div>

          <!-- 2: Auto Escalate -->
          <div class="tab-pane" data-pane="2" role="tabpanel">
            <div class="pane-header">
              <div class="pane-icon" style="background: #ede9fe; color: #7c3aed;"><i class="fa-solid fa-bolt"></i></div>
              <div class="pane-header-text">
                <h3>Auto-Escalation Timer</h3>
                <p>Automatically escalate idle cases to prevent bottlenecks</p>
              </div>
            </div>
            <div class="control-grid">
              <div class="form-group">
                <label for="auto_escalation_days">Days of Inactivity</label>
                <input type="number" id="auto_escalation_days" name="auto_escalation_days" value="<?php echo e($thresholds['auto_escalation_days']); ?>" placeholder="e.g., 14" />
                <div class="help-text">No reviewer activity triggers automatic escalation</div>
              </div>
            </div>
            <div class="preview-section">
              <div class="preview-title"><i class="fa-regular fa-lightbulb"></i> Impact Preview</div>
              <div class="preview-items">
                <div class="preview-item"><div class="preview-label">Escalate If Idle</div><div class="preview-value"><span id="preview-escalation"><?php echo e($thresholds['auto_escalation_days']); ?></span> days</div></div>
                <div class="preview-item"><div class="preview-label">Notification</div><div class="preview-value">Next Level Alert</div></div>
              </div>
            </div>
          </div>

          <!-- 3: Review SLA -->
          <div class="tab-pane" data-pane="3" role="tabpanel">
            <div class="pane-header">
              <div class="pane-icon" style="background: #dbeafe; color: #2563eb;"><i class="fa-solid fa-hourglass-end"></i></div>
              <div class="pane-header-text">
                <h3>Review Period SLAs</h3>
                <p>Set performance targets for each review stage</p>
              </div>
            </div>
            <div class="control-grid">
              <div class="form-group">
                <label for="supervisor_review_days">Supervisor Review (Days)</label>
                <input type="number" id="supervisor_review_days" name="supervisor_review_days" value="<?php echo e($thresholds['supervisor_review_days']); ?>" placeholder="e.g., 7" />
                <div class="help-text">Target turnaround for supervisor verification</div>
              </div>
              <div class="form-group">
                <label for="hr_review_days">HR Review (Days)</label>
                <input type="number" id="hr_review_days" name="hr_review_days" value="<?php echo e($thresholds['hr_review_days']); ?>" placeholder="e.g., 14" />
                <div class="help-text">Target turnaround for HR assessment</div>
              </div>
            </div>
            <div class="preview-section">
              <div class="preview-title"><i class="fa-regular fa-lightbulb"></i> SLA Timeline</div>
              <div class="preview-items">
                <div class="preview-item"><div class="preview-label">Stage 1: Supervisor</div><div class="preview-value"><span id="preview-supervisor"><?php echo e($thresholds['supervisor_review_days']); ?></span> days</div></div>
                <div class="preview-item"><div class="preview-label">Stage 2: HR</div><div class="preview-value"><span id="preview-hr"><?php echo e($thresholds['hr_review_days']); ?></span> days</div></div>
              </div>
            </div>
          </div>

          <!-- 4: Witness -->
          <div class="tab-pane" data-pane="4" role="tabpanel">
            <div class="pane-header">
              <div class="pane-icon" style="background: #dcfce7; color: #15803d;"><i class="fa-solid fa-file-upload"></i></div>
              <div class="pane-header-text">
                <h3>Witness Statement Deadline</h3>
                <p>Grace period for witnesses to submit statements after escalation</p>
              </div>
            </div>
            <div class="control-grid">
              <div class="form-group">
                <label for="witness_statement_days">Days After Escalation</label>
                <input type="number" id="witness_statement_days" name="witness_statement_days" value="<?php echo e($thresholds['witness_statement_days']); ?>" placeholder="e.g., 21" />
                <div class="help-text">Witnesses cannot submit statements after this period</div>
              </div>
            </div>
            <div class="preview-section">
              <div class="preview-title"><i class="fa-regular fa-lightbulb"></i> Impact Preview</div>
              <div class="preview-items">
                <div class="preview-item"><div class="preview-label">Submission Window</div><div class="preview-value"><span id="preview-witness"><?php echo e($thresholds['witness_statement_days']); ?></span> days</div></div>
                <div class="preview-item"><div class="preview-label">Enforcement</div><div class="preview-value">Hard Cutoff</div></div>
              </div>
            </div>
          </div>

          <!-- actions -->
          <div class="action-bar">
            <button type="button" class="btn btn-secondary" onclick="resetForm()"><i class="fa-solid fa-undo"></i> Reset</button>
            <button type="button" class="btn btn-primary" onclick="saveThresholds()"><i class="fa-regular fa-floppy-disk"></i> Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    // ----- TABS -----
    const tabButtons = document.querySelectorAll('.tab-btn');
    const panes = document.querySelectorAll('.tab-pane');

    tabButtons.forEach(btn => {
      btn.addEventListener('click', function(e) {
        const tabId = this.dataset.tab;

        // update buttons
        tabButtons.forEach(b => b.classList.remove('active'));
        this.classList.add('active');

        // update panes
        panes.forEach(pane => {
          pane.classList.remove('active');
          if (pane.dataset.pane === tabId) {
            pane.classList.add('active');
          }
        });
      });
    });

    // ----- LIVE PREVIEW -----
    document.getElementById('leave_limit')?.addEventListener('input', function() {
      document.getElementById('preview-leave').textContent = this.value || 0;
    });
    document.getElementById('statutory_risk_months')?.addEventListener('input', function() {
      document.getElementById('preview-statutory').textContent = this.value || 0;
    });
    document.getElementById('auto_escalation_days')?.addEventListener('input', function() {
      document.getElementById('preview-escalation').textContent = this.value || 0;
    });
    document.getElementById('supervisor_review_days')?.addEventListener('input', function() {
      document.getElementById('preview-supervisor').textContent = this.value || 0;
    });
    document.getElementById('hr_review_days')?.addEventListener('input', function() {
      document.getElementById('preview-hr').textContent = this.value || 0;
    });
    document.getElementById('witness_statement_days')?.addEventListener('input', function() {
      document.getElementById('preview-witness').textContent = this.value || 0;
    });

    // ----- RESET / SAVE -----
    function resetForm() {
      if (confirm('Reset all changes to last saved values?')) {
        location.reload();
      }
    }

    function saveThresholds() {
      const form = document.getElementById('thresholdForm');
      const formData = new FormData(form);

      fetch('<?php echo e(route("system.thresholds")); ?>', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Accept': 'application/json'
        },
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        alert(data.success ? '✅ Saved successfully!' : '❌ Error: ' + (data.message || 'Unknown error'));
      })
      .catch(error => {
        console.error('Error:', error);
        alert('❌ Failed to save');
      });
    }
  </script>
</body>
</html><?php /**PATH D:\OccupationalHealth System\resources\views\system\thresholds.blade.php ENDPATH**/ ?>