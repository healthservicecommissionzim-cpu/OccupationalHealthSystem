<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate, private, max-age=0" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta name="robots" content="noarchive" />
    <title>HSC-OHS | Track Incident</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300..700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
    <div class="track-container">
        <div class="track-card">
            <header class="track-header">
                <div class="track-header-content">
                    <div class="track-icon">
                        <img src="{{ asset('images/hsc-logo.png') }}" alt="HSC-OHS Logo">
                    </div>
                    <h1>Track Your Incident</h1>
                </div>
                <a href="{{ route('welcome') }}" class="back-home-link">
                    Back to Home
                </a>
            </header>

            <!-- Tabs Navigation -->
            <div class="tabs-container">
                <button type="button" class="tab-button active" id="trackCaseTab" data-tab="track-case">
                    <i class="fa-solid fa-search"></i> Track Case
                </button>
                <button type="button" class="tab-button" id="documentUploadTab" data-tab="document-upload">
                    <i class="fa-solid fa-cloud-arrow-up"></i> Upload Documents
                </button>
            </div>

            <!-- Tab 1: Track Case -->
            <div id="track-case" class="tab-content active">
                <div class="track-search">
                    <form id="trackForm" class="search-form">
                        @csrf
                        <div class="form-group">
                            <label for="reference_number">Incident Reference Number <span class="required">*</span></label>
                            <div class="input-wrapper">
                                <input 
                                    type="text" 
                                    id="reference_number" 
                                    name="reference_number" 
                                    placeholder="e.g., HSC-2024-001234"
                                    required
                                    autofocus
                                    autocomplete="off"
                                >
                                <button type="submit" class="btn-search" id="searchBtn">
                                    <span class="spinner" id="spinner" style="display: none;">
                                        <i class="fa-solid fa-spinner fa-spin"></i>
                                    </span>
                                    <span class="search-text"><i class="fa-solid fa-magnifying-glass"></i> Search</span>
                                </button>
                            </div>
                            <div class="error-message" id="errorMessage" style="display: none;"></div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tab 2: Document Upload -->
            <div id="document-upload" class="tab-content">
                <div class="track-search">
                    <form id="documentSearchForm" class="search-form">
                        @csrf
                        <div class="form-group">
                            <label for="document_reference_number">Incident Reference Number <span class="required">*</span></label>
                            <div class="input-wrapper">
                                <input 
                                    type="text" 
                                    id="document_reference_number" 
                                    name="reference_number" 
                                    placeholder="e.g., HSC-2024-001234"
                                    required
                                    autocomplete="off"
                                >
                                <button type="submit" class="btn-search" id="documentSearchBtn">
                                    <span class="spinner" id="documentSpinner" style="display: none;">
                                        <i class="fa-solid fa-spinner fa-spin"></i>
                                    </span>
                                    <span class="search-text"><i class="fa-solid fa-magnifying-glass"></i> Search</span>
                                </button>
                            </div>
                            <div class="error-message" id="documentErrorMessage" style="display: none;"></div>
                        </div>
                    </form>
                </div>

                <!-- Upload Form (Hidden until search) -->
                <div id="uploadFormContainer" style="display: none; padding: 2rem;">
                    <div style="margin-bottom: 1.5rem; padding: 1rem; background: var(--slate-50); border-radius: var(--radius-sm); border-left: 4px solid var(--brand);">
                        <p style="font-size: 0.85rem; color: var(--slate-700); margin: 0;">
                            <strong>Case Found:</strong> <span id="uploadCaseRefNumber" style="color: var(--brand); font-weight: 700;"></span>
                        </p>
                    </div>

                    <form id="documentUploadFormTrack" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="uploadIncidentIdHidden" name="incident_id">

                        <!-- Document Type Selection -->
                        <div class="form-group" style="margin-bottom: 1.5rem;">
                            <label for="documentType" style="font-size: 0.85rem; font-weight: 700; color: var(--slate-700); margin-bottom: 0.5rem; display: block;">
                                Select Document Type <span class="required">*</span>
                            </label>
                            <select id="documentType" style="width: 100%; padding: 0.6rem 0.85rem; border: 1.5px solid var(--slate-200); border-radius: 6px; font-size: 0.85rem; color: var(--slate-900); font-family: inherit; cursor: pointer; transition: all 0.2s ease;" onfocus="this.style.borderColor='#1D4533'; this.style.boxShadow='0 0 0 3px rgba(29, 69, 51, 0.25)'" onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                                <option value="">-- Choose Document Type --</option>
                                <option value="doctors_report">1. Doctor's Report</option>
                                <option value="injury_report">2. Injury Report from Z1149 dully completed</option>
                                <option value="sick_leave">3. Sick Leave Forms</option>
                                <option value="treatment_records">4. Certified Copies of Initial Treatment Records</option>
                                <option value="national_id">5. Certified Copy of National Registration Card</option>
                                <option value="banking_details">6. Banking Details Forms</option>
                                <option value="bank_statement">7. Bank Statements (Nostro & ZiG)</option>
                                <option value="police_report">8. Police Report</option>
                                <option value="death_certificate">9. Death Certificate</option>
                                <option value="heir_certificate">10. Certificate of Heir/Letter of Administration/Certificate of Guardianship</option>
                                <option value="db8_form">11. DB8 Form</option>
                                <option value="termination_letter">12. Letter of Termination of Service</option>
                                <option value="marriage_certificate">13. Certified Copy of Marriage Certificate/Marriage Confirmation</option>
                            </select>
                        </div>

                        <!-- File Selection -->
                        <div class="form-group" style="margin-bottom: 1.5rem;">
                            <label for="uploadDocuments" style="font-size: 0.85rem; font-weight: 700; color: var(--slate-700); margin-bottom: 1rem; display: block;">
                                Select File <span class="required">*</span>
                                <span style="font-size: 0.7rem; color: var(--slate-500); font-weight: 400;">PDF, Word, Excel, Images (Max 10MB per file)</span>
                            </label>
                            <div style="border: 2px dashed var(--slate-300); border-radius: var(--radius-sm); padding: 2rem; text-align: center; background: var(--slate-50); cursor: pointer; transition: all 0.3s ease;" id="trackDropZone">
                                <div style="margin-bottom: 1rem;">
                                    <i class="fa-solid fa-cloud-arrow-up" style="font-size: 2.5rem; color: var(--brand);"></i>
                                </div>
                                <p style="color: var(--slate-700); font-weight: 600; margin-bottom: 0.3rem;">Drag and drop file here</p>
                                <p style="color: var(--slate-500); font-size: 0.85rem; margin-bottom: 1rem;">or click to browse</p>
                                <input type="file" id="uploadDocuments" name="document_file" accept=".pdf,.docx,.xlsx,.jpg,.jpeg,.png" style="display: none;" />
                            </div>
                            <div id="currentFileDisplay" style="margin-top: 0.75rem; padding: 0.75rem; background: var(--slate-50); border: 1px solid var(--slate-200); border-radius: 6px; text-align: center; color: var(--slate-500); font-size: 0.85rem; display: none;">
                                <i class="fa-solid fa-file" style="margin-right: 0.5rem;"></i><span id="currentFileName"></span>
                            </div>
                        </div>

                        <!-- Add Button -->
                        <div style="display: flex; gap: 0.75rem; margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid var(--slate-100);">
                            <button type="button" id="addToQueueBtn" style="padding: 0.55rem 1.4rem; border-radius: 6px; font-weight: 700; font-size: 0.8rem; border: none; background: var(--brand); color: white; cursor: pointer; transition: all 0.2s ease;">
                                <i class="fa-solid fa-plus"></i> Add Document to Queue
                            </button>
                        </div>

                        <!-- Queue List -->
                        <div style="margin-bottom: 1.5rem;">
                            <label style="font-size: 0.85rem; font-weight: 700; color: var(--slate-700); margin-bottom: 0.75rem; display: block;">
                                Documents to Upload (<span id="queueCount">0</span>)
                            </label>
                            <div id="documentQueue" style="background: var(--slate-50); border: 1px solid var(--slate-200); border-radius: 6px; padding: 1rem; min-height: 100px; max-height: 300px; overflow-y: auto;">
                                <p style="color: var(--slate-500); text-align: center; font-size: 0.85rem; margin: 0;">No documents queued yet</p>
                            </div>
                        </div>

                        <!-- Final Upload Section -->
                        <div style="display: flex; gap: 0.75rem; padding-top: 1.5rem; border-top: 1px solid var(--slate-100);">
                            <button type="button" id="clearQueueBtn" style="padding: 0.55rem 1.4rem; border-radius: 6px; font-weight: 700; font-size: 0.8rem; border: 1px solid var(--slate-200); background: var(--slate-100); color: var(--slate-700); cursor: pointer; transition: all 0.2s ease;">
                                <i class="fa-solid fa-trash"></i> Clear Queue
                            </button>
                            <button type="submit" class="btn btn-primary" id="submitUploadBtn" style="padding: 0.55rem 1.4rem; border-radius: 6px; font-weight: 700; font-size: 0.8rem; border: none; background: var(--brand); color: white; cursor: pointer; transition: all 0.2s ease; margin-left: auto;" disabled>
                                <i class="fa-solid fa-cloud-arrow-up"></i> Upload All Documents
                            </button>
                        </div>
                    </form>

                    <div style="margin-top: 1.5rem; text-align: center;">
                        <button type="button" id="newSearchBtn" class="btn btn-secondary" style="padding: 0.55rem 1.4rem; border-radius: 6px; font-weight: 700; font-size: 0.8rem; border: 1px solid var(--slate-200); background: var(--slate-100); color: var(--slate-700); cursor: pointer; transition: all 0.2s ease;">
                            <i class="fa-solid fa-search"></i> Search Another Case
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Overlay -->
    <div class="modal-overlay" id="resultModal" style="display: none;">
        <div class="modal-content">
            <button class="modal-close" onclick="closeModal()">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <div class="modal-header">
                <div class="status-badge" id="statusBadge"></div>
                <h2 id="modalTitle"></h2>
            </div>
            <div class="modal-body" id="modalBody"></div>
            <div class="modal-footer">
                <a id="downloadBtn" href="#" class="btn-download-pdf" download>
                    <i class="fa-solid fa-download"></i> Download PDF
                </a>
                <button class="btn-close" onclick="closeModal()">Close</button>
            </div>
        </div>
    </div>

    <!-- First Aid Modal -->
    <div class="modal-overlay" id="firstAidModal" style="display: none;">
        <div class="modal-content">
            <button class="modal-close" onclick="closeFirstAidModal()">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <div class="modal-header">
                <h2><i class="fa-solid fa-first-aid"></i> First Aid Record</h2>
            </div>
            <div class="modal-body" id="firstAidBody"></div>
            <div class="modal-footer">
                <a id="firstAidDownloadBtn" href="#" class="btn-download-pdf" download>
                    <i class="fa-solid fa-download"></i> Download PDF
                </a>
                <button class="btn-close" onclick="closeFirstAidModal()">Close</button>
            </div>
        </div>
    </div>

    <style>
        :root {
            --brand: #1D4533;
            --brand-dark: #0f2818;
            --brand-light: #f0fdf4;
            --brand-glow: rgba(29, 69, 51, 0.25);
            --slate-50: #FBFBFA;
            --slate-100: #f1f5f9;
            --slate-200: #e2e8f0;
            --slate-300: #cbd5e1;
            --slate-400: #94a3b8;
            --slate-500: #64748b;
            --slate-600: #475569;
            --slate-700: #334155;
            --slate-800: #1e293b;
            --slate-900: #0f172a;
            --white: #ffffff;
            --red-500: #ef4444;
            --amber-500: #f59e0b;
            --green-500: #22c55e;
            --radius: 12px;
            --radius-sm: 6px;
            --shadow-card: 0 8px 30px -8px rgba(0, 0, 0, 0.08), 0 2px 12px -4px rgba(0, 0, 0, 0.02);
            --shadow-focus: 0 0 0 3px var(--brand-glow);
            --transition: 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideUp {
            from { opacity: 1; transform: translateY(0); }
            to { opacity: 0; transform: translateY(-20px); }
        }

        .notification {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
            animation: slideDown 0.4s ease-out;
        }

        .notification.hide {
            animation: slideUp 0.4s ease-out forwards;
        }

        .notification-content {
            background: var(--white);
            border-radius: var(--radius);
            padding: 2rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            text-align: center;
            min-width: 360px;
            max-width: 500px;
        }

        .notification.success .notification-icon {
            color: var(--green-500);
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .notification.error .notification-icon {
            color: var(--red-500);
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .notification-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--slate-900);
            margin-bottom: 0.5rem;
        }

        .notification-message {
            font-size: 0.95rem;
            color: var(--slate-600);
            line-height: 1.5;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #f0f2f5 0%, #e5e7eb 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem 1rem;
        }

        .track-container { width: 100%; max-width: 540px; }

        .track-card {
            background: var(--white);
            border-radius: var(--radius);
            box-shadow: var(--shadow-card);
            border-top: 5px solid var(--brand);
            overflow: hidden;
        }

        .track-header {
            padding: 1.5rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
            border-bottom: 1px solid var(--slate-100);
        }

        .track-header-content {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex: 1;
            min-width: 0;
        }

        .track-icon {
            width: 48px;
            height: 48px;
            background: var(--brand-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .track-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .track-header h1 {
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--slate-900);
            margin-bottom: 0;
            word-wrap: break-word;
            overflow-wrap: break-word;
            word-break: break-word;
        }

        .back-home-link {
            text-decoration: none;
            color: var(--white);
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0;
            white-space: nowrap;
            padding: 0.55rem 1.4rem;
            border-radius: var(--radius-sm);
            transition: all var(--transition);
            background: var(--brand);
            box-shadow: 0 2px 8px rgba(29, 69, 51, 0.2);
            border: none;
            flex-shrink: 0;
        }

        .back-home-link:hover {
            background: var(--brand-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 16px rgba(29, 69, 51, 0.3);
        }

        .track-search {
            padding: 2rem;
        }

        /* Tabs Styling */
        .tabs-container {
            display: flex;
            gap: 0;
            border-bottom: 2px solid var(--slate-200);
            margin: 0;
            background: var(--white);
        }

        .tab-button {
            padding: 1rem 1.5rem;
            background: transparent;
            border: none;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--slate-500);
            border-bottom: 3px solid transparent;
            transition: all var(--transition);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: -2px;
            position: relative;
            z-index: 1;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .tab-button:hover {
            color: var(--brand);
            background: var(--slate-50);
        }

        .tab-button.active {
            color: var(--brand);
            border-bottom-color: var(--brand);
            background: var(--white);
            font-weight: 700;
        }

        .tab-content {
            display: none;
            padding: 0;
        }

        .tab-content.active {
            display: block;
        }

        .search-form { width: 100%; }

        .form-group {
            margin-bottom: 0;
        }

        .form-group label {
            display: block;
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--slate-700);
            margin-bottom: 0.5rem;
        }

        .form-group label .required {
            color: var(--red-500);
            font-weight: 700;
        }

        .input-wrapper {
            display: flex;
            gap: 0.5rem;
            width: 100%;
        }

        .input-wrapper input {
            flex: 1;
            padding: 0.6rem 0.85rem;
            border: 1.5px solid var(--slate-200);
            border-radius: var(--radius-sm);
            font-size: 0.85rem;
            font-family: inherit;
            color: var(--slate-900);
            transition: all var(--transition);
        }

        .input-wrapper input:focus {
            outline: none;
            border-color: var(--brand);
            box-shadow: var(--shadow-focus);
        }

        .btn-search {
            padding: 0.6rem 1.2rem;
            background: var(--brand);
            color: var(--white);
            border: none;
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 0.82rem;
            cursor: pointer;
            transition: all var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            box-shadow: 0 2px 8px rgba(29, 69, 51, 0.2);
            white-space: nowrap;
        }

        .btn-search:hover:not(:disabled) {
            background: var(--brand-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 16px rgba(29, 69, 51, 0.3);
        }

        .btn-search:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .error-message {
            color: var(--red-500);
            font-size: 0.78rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        /* Modal Overlay */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2000;
            padding: 1rem;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-content {
            background: var(--white);
            border-radius: var(--radius);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            width: 100%;
            max-height: 85vh;
            overflow-y: auto;
            position: relative;
            animation: slideUp 0.3s ease;
        }

        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .modal-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: var(--slate-100);
            border: none;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--slate-700);
            transition: all var(--transition);
            z-index: 10;
        }

        .modal-close:hover {
            background: var(--slate-200);
            color: var(--slate-900);
        }

        .modal-header {
            padding: 2rem 2rem 1rem;
            border-bottom: 1px solid var(--slate-100);
        }

        .status-badge {
            display: inline-block;
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 0.75rem;
        }

        .status-badge.pending { background: #fef3c7; color: #92400e; }
        .status-badge.verified { background: #dbeafe; color: #0c4a6e; }
        .status-badge.investigating { background: #fce7f3; color: #831843; }
        .status-badge.resolved { background: #dcfce7; color: #15803d; }

        .modal-header h2 {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--slate-900);
        }

        .modal-footer {
            padding: 1.5rem 2rem;
            border-top: 1px solid var(--slate-100);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-download-pdf {
            padding: 0.6rem 1.5rem;
            background: #1D4533;
            color: var(--white);
            border: none;
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 0.82rem;
            cursor: pointer;
            transition: all var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-download-pdf:hover {
            background: #0f2818;
            transform: translateY(-1px);
        }

        .modal-body {
            padding: 1.5rem 2rem;
        }

        .detail-section {
            margin-bottom: 1.75rem;
        }

        .detail-section:last-child {
            margin-bottom: 0;
        }

        .detail-section-title {
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--brand);
            text-transform: uppercase;
            letter-spacing: 0.03em;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .detail-row {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 1rem;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--slate-100);
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--slate-600);
        }

        .detail-value {
            font-size: 0.85rem;
            color: var(--slate-900);
            word-break: break-word;
        }

        .btn-close {
            padding: 0.6rem 1.5rem;
            background: var(--slate-100);
            color: var(--slate-700);
            border: 1px solid var(--slate-200);
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 0.82rem;
            cursor: pointer;
            transition: all var(--transition);
        }

        .btn-close:hover {
            background: var(--slate-200);
            border-color: var(--slate-300);
        }

        .btn-view-first-aid {
            padding: 0.3rem 0.75rem;
            background: var(--brand);
            color: var(--white);
            border: none;
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 0.75rem;
            cursor: pointer;
            transition: all var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            margin-left: 0.5rem;
        }

        .btn-view-first-aid:hover {
            background: var(--brand-dark);
        }

        .first-aid-section {
            margin-top: 1.75rem;
            padding-top: 1.75rem;
            border-top: 1px solid var(--slate-100);
        }

        .first-aid-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .first-aid-records {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .first-aid-record {
            background: var(--slate-50);
            border: 1px solid var(--slate-200);
            border-radius: var(--radius-sm);
            padding: 1rem;
        }

        .first-aid-record-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.75rem;
        }

        .first-aid-status {
            display: inline-block;
            padding: 0.25rem 0.6rem;
            border-radius: 20px;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .first-aid-status.pending { background: #fef3c7; color: #92400e; }
        .first-aid-status.approved { background: #dcfce7; color: #15803d; }
        .first-aid-status.rejected { background: #fee2e2; color: #991b1b; }

        .first-aid-info {
            font-size: 0.8rem;
            color: var(--slate-600);
        }

        @media (max-width: 640px) {
            .track-header {
                flex-direction: row;
                align-items: center;
                gap: 0.5rem;
                padding: 0.75rem 1rem;
                flex-wrap: nowrap;
            }

            .track-header-content {
                flex: 1;
                min-width: 0;
                gap: 0.5rem;
            }

            .track-icon {
                width: 32px;
                height: 32px;
            }

            .track-header h1 {
                font-size: 0.75rem;
                line-height: 1.2;
            }

            .back-home-link {
                padding: 0.35rem 0.6rem;
                font-size: 0.6rem;
                flex-shrink: 0;
            }

            .track-search { padding: 1.5rem; }
            .modal-header { padding: 1.5rem 1.5rem 1rem; }
            .modal-body { padding: 1.5rem; }
            .modal-footer { padding: 1.5rem; }
            .input-wrapper { flex-direction: column; }
            .btn-search { width: 100%; justify-content: center; }
            .detail-row { grid-template-columns: 1fr; gap: 0.35rem; }
        }

        @media (max-width: 480px) {
            .track-header {
                padding: 0.6rem 0.75rem;
                gap: 0.4rem;
            }

            .track-icon {
                width: 28px;
                height: 28px;
            }

            .track-header h1 {
                font-size: 0.65rem;
            }

            .back-home-link {
                padding: 0.3rem 0.5rem;
                font-size: 0.55rem;
            }
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>

    <script>
        // Aggressive cache prevention - run before anything else
        (function() {
            'use strict';

            // Replace the URL in history to prevent back button from returning to this form
            if (window.history.replaceState) {
                window.history.replaceState({noBackButton: true}, null, window.location.href);
            }

            // Prevent browser cache from restoring form data
            window.addEventListener('pageshow', function(event) {
                // Force reload if page was loaded from bfcache (back/forward cache)
                if (event.persisted) {
                    window.location.reload();
                }
                
                // Always reset forms to ensure no data is shown
                const forms = document.querySelectorAll('form');
                forms.forEach(form => {
                    form.reset();
                });
            });

            // Clear data when leaving the page
            window.addEventListener('pagehide', function() {
                const forms = document.querySelectorAll('form');
                forms.forEach(form => {
                    form.reset();
                    // Clear all input values explicitly
                    const inputs = form.querySelectorAll('input, textarea, select');
                    inputs.forEach(input => {
                        input.value = '';
                    });
                });
                // Clear all storage
                sessionStorage.clear();
                localStorage.removeItem('trackFormData');
            });

            // Prevent form autofill from browser cache
            window.addEventListener('load', function() {
                const forms = document.querySelectorAll('form');
                forms.forEach(form => {
                    // Disable browser autocomplete on all forms
                    form.setAttribute('autocomplete', 'off');
                    
                    // Reset all fields explicitly
                    form.reset();
                    
                    // Clear all input values and add autocomplete=off
                    const inputs = form.querySelectorAll('input, textarea, select');
                    inputs.forEach(input => {
                        input.value = '';
                        input.setAttribute('autocomplete', 'off');
                    });
                });
            });
        })();

        let currentIncidentId = null;
        let uploadedFiles = [];

        // Tab switching functionality
        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');
                
                // Remove active class from all buttons and contents
                document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
                
                // Add active class to clicked button and corresponding content
                this.classList.add('active');
                document.getElementById(tabId).classList.add('active');
                
                // Focus input based on tab
                if (tabId === 'track-case') {
                    setTimeout(() => document.getElementById('reference_number').focus(), 100);
                } else if (tabId === 'document-upload') {
                    setTimeout(() => document.getElementById('document_reference_number').focus(), 100);
                }
            });
        });

        // ===== TRACK CASE TAB =====
        document.getElementById('trackForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Clear storage on submit
            sessionStorage.clear();
            localStorage.removeItem('trackFormData');
            
            const referenceNumber = document.getElementById('reference_number').value.trim();
            const searchBtn = document.getElementById('searchBtn');
            const spinner = document.getElementById('spinner');
            const searchText = document.querySelector('#searchBtn .search-text');
            const errorMessage = document.getElementById('errorMessage');
            
            if (!referenceNumber) {
                errorMessage.style.display = 'flex';
                errorMessage.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i> Please enter a reference number';
                return;
            }
            
            // Show loading state
            searchBtn.disabled = true;
            spinner.style.display = 'inline';
            searchText.style.display = 'none';
            errorMessage.style.display = 'none';
            
            try {
                const response = await fetch('{{ route("incidents.search") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ reference_number: referenceNumber })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    displayIncidentDetails(data.incident);
                } else {
                    errorMessage.style.display = 'flex';
                    errorMessage.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i> ' + (data.message || 'Incident not found');
                }
            } catch (error) {
                errorMessage.style.display = 'flex';
                errorMessage.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i> An error occurred. Please try again.';
            } finally {
                searchBtn.disabled = false;
                spinner.style.display = 'none';
                searchText.style.display = 'inline-flex';
            }
        });

        // ===== DOCUMENT UPLOAD TAB =====
        document.getElementById('documentSearchForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Clear storage on submit
            sessionStorage.clear();
            localStorage.removeItem('trackFormData');
            
            const referenceNumber = document.getElementById('document_reference_number').value.trim();
            const searchBtn = document.getElementById('documentSearchBtn');
            const spinner = document.getElementById('documentSpinner');
            const searchText = document.querySelector('#documentSearchBtn .search-text');
            const errorMessage = document.getElementById('documentErrorMessage');
            
            if (!referenceNumber) {
                errorMessage.style.display = 'flex';
                errorMessage.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i> Please enter a reference number';
                return;
            }
            
            // Show loading state
            searchBtn.disabled = true;
            spinner.style.display = 'inline';
            searchText.style.display = 'none';
            errorMessage.style.display = 'none';
            
            try {
                const response = await fetch('{{ route("incidents.search") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ reference_number: referenceNumber })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Store incident ID for document upload
                    currentIncidentId = data.incident.id;
                    
                    // Show upload form
                    document.querySelector('#document-upload .track-search').style.display = 'none';
                    document.getElementById('uploadFormContainer').style.display = 'block';
                    document.getElementById('uploadCaseRefNumber').textContent = referenceNumber;
                    document.getElementById('uploadIncidentIdHidden').value = currentIncidentId;
                    
                    errorMessage.style.display = 'none';
                } else {
                    errorMessage.style.display = 'flex';
                    errorMessage.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i> ' + (data.message || 'Incident not found');
                    
                    // Reset form
                    currentIncidentId = null;
                    document.getElementById('uploadFormContainer').style.display = 'none';
                }
            } catch (error) {
                errorMessage.style.display = 'flex';
                errorMessage.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i> An error occurred. Please try again.';
                
                // Reset form
                currentIncidentId = null;
                document.getElementById('uploadFormContainer').style.display = 'none';
            } finally {
                searchBtn.disabled = false;
                spinner.style.display = 'none';
                searchText.style.display = 'inline-flex';
            }
        });

        // New Search button in Document Upload tab
        document.getElementById('newSearchBtn').addEventListener('click', function() {
            document.getElementById('documentSearchForm').reset();
            document.querySelector('#document-upload .track-search').style.display = 'block';
            document.getElementById('uploadFormContainer').style.display = 'none';
            uploadedFiles = [];
            document.getElementById('uploadDocuments').value = '';
            document.getElementById('uploadNotes').value = '';
            document.getElementById('uploadNotesCount').textContent = '0';
            displayUploadedFiles();
            document.getElementById('document_reference_number').focus();
        });

        // Document Upload Functionality
        const trackDropZone = document.getElementById('trackDropZone');
        const uploadDocumentsInput = document.getElementById('uploadDocuments');
        const documentTypeSelect = document.getElementById('documentType');
        const addToQueueBtn = document.getElementById('addToQueueBtn');
        const clearQueueBtn = document.getElementById('clearQueueBtn');
        const submitUploadBtn = document.getElementById('submitUploadBtn');
        const documentQueue = document.getElementById('documentQueue');
        const queueCount = document.getElementById('queueCount');
        const currentFileDisplay = document.getElementById('currentFileDisplay');
        const currentFileName = document.getElementById('currentFileName');

        let selectedFile = null;
        let documentQueue_items = [];

        if (trackDropZone && uploadDocumentsInput) {
            // Click to upload
            trackDropZone.addEventListener('click', () => uploadDocumentsInput.click());
            
            // File selection
            uploadDocumentsInput.addEventListener('change', function(e) {
                if (this.files.length > 0) {
                    selectedFile = this.files[0];
                    displaySelectedFile();
                }
            });
            
            // Drag and drop
            trackDropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                trackDropZone.style.borderColor = 'var(--brand)';
                trackDropZone.style.background = 'rgba(29, 69, 51, 0.05)';
            });
            
            trackDropZone.addEventListener('dragleave', () => {
                trackDropZone.style.borderColor = 'var(--slate-300)';
                trackDropZone.style.background = 'var(--slate-50)';
            });
            
            trackDropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                trackDropZone.style.borderColor = 'var(--slate-300)';
                trackDropZone.style.background = 'var(--slate-50)';
                
                if (e.dataTransfer.files.length > 0) {
                    selectedFile = e.dataTransfer.files[0];
                    uploadDocumentsInput.files = e.dataTransfer.files;
                    displaySelectedFile();
                }
            });
        }

        function displaySelectedFile() {
            if (selectedFile) {
                currentFileDisplay.style.display = 'block';
                currentFileName.textContent = `${selectedFile.name} (${(selectedFile.size / 1024 / 1024).toFixed(2)} MB)`;
            } else {
                currentFileDisplay.style.display = 'none';
            }
        }

        if (addToQueueBtn) {
            addToQueueBtn.addEventListener('click', (e) => {
                e.preventDefault();
                
                if (!documentTypeSelect.value) {
                    alert('Please select a document type.');
                    return;
                }
                
                if (!selectedFile) {
                    alert('Please select a file.');
                    return;
                }
                
                // Get document type label
                const selectedOption = documentTypeSelect.options[documentTypeSelect.selectedIndex];
                const documentLabel = selectedOption.text;
                
                // Add to queue
                documentQueue_items.push({
                    type: documentTypeSelect.value,
                    label: documentLabel,
                    file: selectedFile
                });
                
                // Reset form
                documentTypeSelect.value = '';
                selectedFile = null;
                uploadDocumentsInput.value = '';
                displaySelectedFile();
                
                // Update queue display
                updateQueueDisplay();
                
                // Enable submit button
                submitUploadBtn.disabled = false;
                submitUploadBtn.style.opacity = '1';
            });
        }

        function updateQueueDisplay() {
            queueCount.textContent = documentQueue_items.length;
            documentQueue.innerHTML = '';
            
            if (documentQueue_items.length === 0) {
                documentQueue.innerHTML = '<p style="color: var(--slate-500); text-align: center; font-size: 0.85rem; margin: 0;">No documents queued yet</p>';
                submitUploadBtn.disabled = true;
                submitUploadBtn.style.opacity = '0.6';
                return;
            }
            
            const ul = document.createElement('ul');
            ul.style.listStyle = 'none';
            ul.style.padding = '0';
            ul.style.margin = '0';
            
            documentQueue_items.forEach((item, index) => {
                const li = document.createElement('li');
                li.style.cssText = 'padding: 0.75rem; display: flex; align-items: center; justify-content: space-between; background: var(--white); border: 1px solid var(--slate-200); border-radius: 6px; margin-bottom: 0.5rem; font-size: 0.85rem;';
                
                const info = document.createElement('div');
                info.style.cssText = 'flex: 1;';
                info.innerHTML = `
                    <div style="color: var(--brand); font-weight: 700; margin-bottom: 0.25rem;">${item.label}</div>
                    <div style="color: var(--slate-600); font-size: 0.8rem;">
                        <i class="fa-solid fa-file"></i> ${item.file.name} (${(item.file.size / 1024 / 1024).toFixed(2)} MB)
                    </div>
                `;
                
                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.innerHTML = '<i class="fa-solid fa-trash"></i>';
                removeBtn.style.cssText = 'background: none; border: none; color: var(--red-500); cursor: pointer; padding: 0.5rem; font-size: 1rem; margin-left: 1rem;';
                removeBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    documentQueue_items.splice(index, 1);
                    updateQueueDisplay();
                });
                
                li.appendChild(info);
                li.appendChild(removeBtn);
                ul.appendChild(li);
            });
            
            documentQueue.appendChild(ul);
        }

        if (clearQueueBtn) {
            clearQueueBtn.addEventListener('click', (e) => {
                e.preventDefault();
                documentQueue_items = [];
                documentTypeSelect.value = '';
                selectedFile = null;
                uploadDocumentsInput.value = '';
                currentFileDisplay.style.display = 'none';
                updateQueueDisplay();
            });
        }

        if (document.getElementById('documentUploadFormTrack')) {
            document.getElementById('documentUploadFormTrack').addEventListener('submit', (e) => {
                e.preventDefault();
                
                // Clear storage on submit
                sessionStorage.clear();
                localStorage.removeItem('trackFormData');
                
                if (!currentIncidentId) {
                    alert('Please search for an incident case first.');
                    return;
                }
                
                if (documentQueue_items.length === 0) {
                    alert('Please add at least one document to the queue.');
                    return;
                }
                
                submitUploadBtn.disabled = true;
                submitUploadBtn.innerHTML = '<i class="fa-solid fa-spinner" style="animation: spin 1s linear infinite;"></i> Uploading...';
                
                const formData = new FormData();
                
                // Add all documents to form data
                documentQueue_items.forEach((item, index) => {
                    formData.append(`documents[${index}][type]`, item.type);
                    formData.append(`documents[${index}][file]`, item.file);
                });
                
                formData.append('incident_id', currentIncidentId);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                
                fetch('/incidents/upload-documents', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('success', 'Upload Successful', 'Documents uploaded successfully! They will be reviewed and linked to your case.');
                        documentQueue_items = [];
                        documentTypeSelect.value = '';
                        selectedFile = null;
                        uploadDocumentsInput.value = '';
                        currentFileDisplay.style.display = 'none';
                        updateQueueDisplay();
                        submitUploadBtn.disabled = false;
                        submitUploadBtn.innerHTML = '<i class="fa-solid fa-cloud-arrow-up"></i> Upload All Documents';
                        
                        // Reset form for new search after 2 seconds
                        setTimeout(() => {
                            currentIncidentId = null;
                            document.getElementById('documentSearchForm').reset();
                            document.querySelector('#document-upload .track-search').style.display = 'block';
                            document.getElementById('uploadFormContainer').style.display = 'none';
                            document.getElementById('document_reference_number').focus();
                        }, 2000);
                    } else {
                        showNotification('error', 'Upload Failed', data.message || 'Upload failed. Please try again.');
                        submitUploadBtn.disabled = false;
                        submitUploadBtn.innerHTML = '<i class="fa-solid fa-cloud-arrow-up"></i> Upload All Documents';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('error', 'Error', 'An error occurred during upload. Please try again.');
                    submitUploadBtn.disabled = false;
                    submitUploadBtn.innerHTML = '<i class="fa-solid fa-cloud-arrow-up"></i> Upload All Documents';
                });
            });
        }

        function displayIncidentDetails(incident) {
            const modal = document.getElementById('resultModal');
            const statusBadge = document.getElementById('statusBadge');
            const modalTitle = document.getElementById('modalTitle');
            const modalBody = document.getElementById('modalBody');
            
            const statusClass = incident.status.toLowerCase().replace(/ /g, '-');
            statusBadge.className = 'status-badge ' + statusClass;
            statusBadge.textContent = incident.status;
            
            let titleHtml = 'Incident #' + incident.reference_number;
            if (incident.first_aid_records && incident.first_aid_records.length > 0) {
                titleHtml += `<button class="btn-view-first-aid" onclick="showFirstAidRecord(${incident.first_aid_records[0].id})">
                    <i class="fa-solid fa-eye"></i> View First Aid Record
                </button>`;
            }
            modalTitle.innerHTML = titleHtml;
            
            let html = `
                <div class="detail-section">
                    <div class="detail-section-title"><i class="fa-solid fa-user"></i> Injured Person</div>
                    <div class="detail-row"><div class="detail-label">Name</div><div class="detail-value">${incident.first_name} ${incident.surname}</div></div>
                    <div class="detail-row"><div class="detail-label">Designation</div><div class="detail-value">${incident.designation_name}</div></div>
                    <div class="detail-row"><div class="detail-label">Department</div><div class="detail-value">${incident.department_name}</div></div>
                    <div class="detail-row"><div class="detail-label">Phone</div><div class="detail-value">${incident.phone_number}</div></div>
                    <div class="detail-row"><div class="detail-label">Email</div><div class="detail-value">${incident.email}</div></div>
                </div>
                
                <div class="detail-section">
                    <div class="detail-section-title"><i class="fa-solid fa-clock"></i> Incident Details</div>
                    <div class="detail-row"><div class="detail-label">Date</div><div class="detail-value">${new Date(incident.incident_date).toLocaleDateString()}</div></div>
                    <div class="detail-row"><div class="detail-label">Time</div><div class="detail-value">${incident.incident_time}</div></div>
                    <div class="detail-row"><div class="detail-label">Description</div><div class="detail-value">${incident.incident_description}</div></div>
                </div>
                
                <div class="detail-section">
                    <div class="detail-section-title"><i class="fa-solid fa-location-dot"></i> Location</div>
                    <div class="detail-row"><div class="detail-label">Facility</div><div class="detail-value">${incident.facility_name}</div></div>
                    <div class="detail-row"><div class="detail-label">District</div><div class="detail-value">${incident.district_name}</div></div>
                    <div class="detail-row"><div class="detail-label">Province</div><div class="detail-value">${incident.province_name}</div></div>
                </div>
                
                <div class="detail-section">
                    <div class="detail-section-title"><i class="fa-solid fa-user-tie"></i> Supervisor</div>
                    <div class="detail-row"><div class="detail-label">Name</div><div class="detail-value">${incident.supervisor_name}</div></div>
                    <div class="detail-row"><div class="detail-label">Designation</div><div class="detail-value">${incident.supervisor_designation}</div></div>
                </div>
                
                <div class="detail-section">
                    <div class="detail-section-title"><i class="fa-solid fa-calendar"></i> Timeline</div>
                    <div class="detail-row"><div class="detail-label">Reported On</div><div class="detail-value">${new Date(incident.created_at).toLocaleDateString()}</div></div>
                    <div class="detail-row"><div class="detail-label">Last Updated</div><div class="detail-value">${new Date(incident.updated_at).toLocaleDateString()}</div></div>
                </div>
            `;
            
            modalBody.innerHTML = html;
            modal.style.display = 'flex';

            document.getElementById('downloadBtn').href = `/incidents/${incident.reference_number}/download-pdf`;
        }

        function showFirstAidRecord(recordId) {
            fetch(`/api/first-aid-records/${recordId}`)
                .then(response => response.json())
                .then(data => {
                    const record = data.record;
                    const firstAidModal = document.getElementById('firstAidModal');
                    const firstAidBody = document.getElementById('firstAidBody');
                    
                    let html = `
                        <div class="detail-section">
                            <div class="detail-section-title"><i class="fa-solid fa-user-nurse"></i> Nurse Information</div>
                            <div class="detail-row"><div class="detail-label">Name</div><div class="detail-value">${record.nurse_name}</div></div>
                            <div class="detail-row"><div class="detail-label">License / EC Number</div><div class="detail-value">${record.nurse_license_number}</div></div>
                        </div>
                        
                        <div class="detail-section">
                            <div class="detail-section-title"><i class="fa-solid fa-bandage"></i> Injury Information</div>
                            <div class="detail-row"><div class="detail-label">Description</div><div class="detail-value">${record.injury_description}</div></div>
                        </div>
                        
                        <div class="detail-section">
                            <div class="detail-section-title"><i class="fa-solid fa-first-aid"></i> First Aid Details</div>
                            <div class="detail-row"><div class="detail-label">Treatment</div><div class="detail-value">${record.first_aid_description}</div></div>
                            <div class="detail-row"><div class="detail-label">Time Administered</div><div class="detail-value">${new Date(record.time_administered).toLocaleString()}</div></div>
                            <div class="detail-row"><div class="detail-label">Location</div><div class="detail-value">${record.location}</div></div>
                            <div class="detail-row"><div class="detail-label">Severity</div><div class="detail-value">${record.severity_level}</div></div>
                        </div>
                    `;
                    
                    if (record.notes) {
                        html += `
                            <div class="detail-section">
                                <div class="detail-section-title"><i class="fa-solid fa-note-sticky"></i> Notes</div>
                                <div class="detail-row"><div class="detail-label">Notes</div><div class="detail-value">${record.notes}</div></div>
                            </div>
                        `;
                    }
                    
                    firstAidBody.innerHTML = html;
                    firstAidModal.style.display = 'flex';

                    document.getElementById('firstAidDownloadBtn').href = `/first-aid/${recordId}/download-pdf`;
                });
        }

        function closeFirstAidModal() {
            document.getElementById('firstAidModal').style.display = 'none';
        }

        function closeModal() {
            document.getElementById('resultModal').style.display = 'none';
        }

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
                closeFirstAidModal();
            }
        });

        // Close modal on background click
        document.getElementById('resultModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        document.getElementById('firstAidModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeFirstAidModal();
            }
        });

        function showNotification(type, title, message) {
            // Remove existing notification if any
            const existingNotification = document.getElementById('notification-container');
            if (existingNotification) {
                existingNotification.remove();
            }

            // Create notification elements
            const container = document.createElement('div');
            container.id = 'notification-container';
            container.className = `notification ${type}`;
            
            const content = document.createElement('div');
            content.className = 'notification-content';
            
            const icon = document.createElement('div');
            icon.className = 'notification-icon';
            icon.innerHTML = type === 'success' 
                ? '<i class="fa-solid fa-circle-check"></i>'
                : '<i class="fa-solid fa-circle-xmark"></i>';
            
            const titleEl = document.createElement('div');
            titleEl.className = 'notification-title';
            titleEl.textContent = title;
            
            const messageEl = document.createElement('div');
            messageEl.className = 'notification-message';
            messageEl.textContent = message;
            
            content.appendChild(icon);
            content.appendChild(titleEl);
            content.appendChild(messageEl);
            container.appendChild(content);
            
            document.body.appendChild(container);
            
            // Auto-hide after 4 seconds
            setTimeout(() => {
                container.classList.add('hide');
                setTimeout(() => {
                    container.remove();
                }, 400);
            }, 4000);
        }
    </script>
</body>
</html>
