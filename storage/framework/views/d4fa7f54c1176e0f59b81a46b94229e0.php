<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>HSC-OHS | Safety Protocols - Zimbabwe Healthcare Workers</title>
    <meta name="description" content="Access critical safety protocols for occupational health hazards, infection control, chemical safety, and emergency procedures for Zimbabwe's healthcare workers.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css']); ?>
    <style>
        :root {
            --brand: #1D4533;
            --brand-dark: #0f2818;
            --brand-light: #f0fdf4;
            --slate-50: #FBFBFA;
            --slate-100: #f1f5f9;
            --slate-200: #e2e8f0;
            --slate-300: #cbd5e1;
            --slate-600: #475569;
            --slate-700: #334155;
            --slate-900: #0f172a;
            --white: #ffffff;
            --critical: #ef4444;
            --critical-light: #fee2e2;
            --chemical: #f59e0b;
            --chemical-light: #fef3c7;
            --occupational: #3b82f6;
            --occupational-light: #dbeafe;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: var(--slate-900);
            overflow-x: hidden;
            background: linear-gradient(to bottom, var(--white) 0%, var(--slate-50) 100%);
        }

        /* Header */
        header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            padding: 1rem 2%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        @media (min-width: 768px) {
            header {
                padding: 1rem 4%;
            }
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            flex-shrink: 0;
        }

        .logo img {
            height: 35px;
            width: auto;
            object-fit: contain;
        }

        @media (min-width: 768px) {
            .logo img {
                height: 50px;
            }
        }

        @media (min-width: 1024px) {
            .logo img {
                height: 55px;
            }
        }

        .logo-text {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-weight: 800;
            font-size: 0.75rem;
            color: var(--brand);
            letter-spacing: -0.02em;
            display: none;
        }

        @media (min-width: 500px) {
            .logo-text {
                display: inline;
                font-size: 0.9rem;
            }
        }

        nav {
            display: none;
            gap: 2rem;
            align-items: center;
        }

        @media (min-width: 769px) {
            nav {
                display: flex;
            }
        }

        nav a {
            text-decoration: none;
            color: var(--slate-600);
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-weight: 500;
            font-size: 0.9rem;
            transition: color 0.3s;
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            min-height: 44px;
            display: flex;
            align-items: center;
        }

        nav a:hover {
            color: var(--brand);
        }

        nav a:focus-visible {
            outline: 2px solid var(--brand);
            outline-offset: 2px;
        }

        .nav-buttons {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .btn {
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s;
            font-size: 0.85rem;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            min-height: 44px;
        }

        .btn-primary {
            background: var(--brand);
            color: var(--white);
            box-shadow: 0 4px 12px rgba(29, 69, 51, 0.2);
        }

        .btn-primary:hover {
            background: var(--brand-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(29, 69, 51, 0.3);
        }

        .btn-primary:focus-visible {
            outline: 2px solid var(--white);
            outline-offset: 2px;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, var(--brand-light) 0%, var(--white) 100%);
            padding: 1.5rem 2%;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        @media (min-width: 500px) {
            .hero {
                padding: 2.5rem 4%;
            }
        }

        @media (min-width: 768px) {
            .hero {
                padding: 3.5rem 4%;
            }
        }

        @media (min-width: 1024px) {
            .hero {
                padding: 4rem 4%;
            }
        }

        .hero h1 {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-size: clamp(1.5rem, 5vw, 3rem);
            font-weight: 800;
            color: var(--slate-900);
            margin-bottom: 1rem;
            line-height: 1.2;
            letter-spacing: -0.02em;
        }

        .hero h1 span {
            color: var(--brand);
        }

        .hero p {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-size: 0.9rem;
            color: var(--slate-600);
            max-width: 700px;
            margin: 0 auto;
            line-height: 1.6;
        }

        @media (min-width: 768px) {
            .hero p {
                font-size: 1.1rem;
            }
        }

        /* Protocols Container */
        .protocols-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 1.5rem 2%;
        }

        @media (min-width: 500px) {
            .protocols-container {
                padding: 2rem 3%;
            }
        }

        @media (min-width: 768px) {
            .protocols-container {
                padding: 2.5rem 4%;
            }
        }

        @media (min-width: 1024px) {
            .protocols-container {
                padding: 3rem 4%;
            }
        }

        .protocols-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        @media (min-width: 501px) {
            .protocols-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1.25rem;
            }
        }

        @media (min-width: 769px) {
            .protocols-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 1.5rem;
            }
        }

        @media (min-width: 1440px) {
            .protocols-grid {
                grid-template-columns: repeat(4, 1fr);
                gap: 2rem;
            }
        }

        .protocol-card {
            background: var(--white);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(29, 69, 51, 0.12), 0 2px 8px rgba(29, 69, 51, 0.08);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .protocol-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 48px rgba(29, 69, 51, 0.16), 0 4px 12px rgba(29, 69, 51, 0.12);
        }

        /* Risk Level Badge */
        .risk-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            z-index: 10;
        }

        .risk-badge.critical {
            background: var(--critical);
            box-shadow: 0 0 0 4px var(--critical-light);
        }

        .risk-badge.chemical {
            background: var(--chemical);
            box-shadow: 0 0 0 4px var(--chemical-light);
        }

        .risk-badge.occupational {
            background: var(--occupational);
            box-shadow: 0 0 0 4px var(--occupational-light);
        }

        /* Risk Level Pill Badge */
        .risk-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 1rem;
            width: fit-content;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        .risk-pill.critical {
            background: var(--critical-light);
            color: var(--critical);
        }

        .risk-pill.chemical {
            background: var(--chemical-light);
            color: var(--chemical);
        }

        .risk-pill.occupational {
            background: var(--occupational-light);
            color: var(--occupational);
        }

        /* Card Content */
        .protocol-card-content {
            padding: 1.5rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .protocol-card-title {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            color: var(--slate-900);
            margin: 0;
            padding-top: 0.5rem;
            line-height: 1.3;
        }

        @media (min-width: 768px) {
            .protocol-card-title {
                font-size: 1.1rem;
            }
        }

        .protocol-card-desc {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-size: 0.875rem;
            color: var(--slate-600);
            line-height: 1.6;
            margin: 0;
        }

        @media (min-width: 768px) {
            .protocol-card-desc {
                font-size: 0.9rem;
            }
        }

        .protocol-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: auto;
        }

        .meta-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.3rem 0.6rem;
            background: var(--brand-light);
            color: var(--brand);
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 600;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        /* Card Actions */
        .protocol-actions {
            display: flex;
            gap: 0.75rem;
            padding: 1rem;
            border-top: 1px solid #e2e8f0;
            background: #fafafa;
            flex-wrap: wrap;
        }

        @media (min-width: 500px) {
            .protocol-actions {
                padding: 1rem 1.5rem;
                gap: 0.75rem;
            }
        }

        .btn-protocol {
            flex: 1;
            min-width: 120px;
            padding: 0.65rem 0.75rem;
            border: none;
            border-radius: 8px;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-weight: 600;
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            min-height: 44px;
        }

        @media (min-width: 500px) {
            .btn-protocol {
                font-size: 0.8rem;
                padding: 0.65rem 1rem;
            }
        }

        .btn-view {
            background: var(--brand);
            color: var(--white);
        }

        .btn-view:hover {
            background: var(--brand-dark);
            transform: translateY(-2px);
        }

        .btn-view:focus-visible {
            outline: 2px solid var(--brand);
            outline-offset: 2px;
        }

        .btn-print {
            background: transparent;
            color: var(--slate-600);
            border: 1.5px solid #e2e8f0;
        }

        .btn-print:hover {
            color: var(--brand);
            border-color: var(--brand);
            background: var(--brand-light);
        }

        .btn-print:focus-visible {
            outline: 2px solid var(--brand);
            outline-offset: 2px;
        }

        /* Protocol Modal */
        .protocol-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            align-items: center;
            justify-content: center;
            padding: 1rem 2%;
        }

        .protocol-modal.active {
            display: flex;
        }

        .protocol-modal-content {
            background: var(--white);
            border-radius: 16px;
            padding: 1.5rem;
            max-width: 600px;
            width: 100%;
            max-height: 85vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            animation: slideUp 0.3s ease;
            position: relative;
        }

        @media (min-width: 500px) {
            .protocol-modal-content {
                padding: 2rem;
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .close-protocol-modal {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--slate-600);
            cursor: pointer;
            padding: 0.5rem;
            transition: color 0.3s;
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
        }

        .close-protocol-modal:hover {
            color: var(--brand);
            background: var(--slate-100);
        }

        .close-protocol-modal:focus-visible {
            outline: 2px solid var(--brand);
            outline-offset: 2px;
        }

        .modal-title {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--slate-900);
            margin-bottom: 0.5rem;
            line-height: 1.3;
        }

        @media (min-width: 500px) {
            .modal-title {
                font-size: 1.5rem;
            }
        }

        .modal-risk-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            width: fit-content;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        .modal-content-section {
            margin-bottom: 1.5rem;
        }

        .modal-content-section h3 {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--slate-900);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            line-height: 1.3;
        }

        @media (min-width: 500px) {
            .modal-content-section h3 {
                font-size: 1rem;
            }
        }

        .modal-content-section ul {
            margin: 0;
            padding-left: 1.5rem;
        }

        .modal-content-section li {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-size: 0.85rem;
            color: var(--slate-600);
            line-height: 1.6;
            margin-bottom: 0.5rem;
        }

        @media (min-width: 500px) {
            .modal-content-section li {
                font-size: 0.9rem;
            }
        }

        .modal-content-text {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-size: 0.9rem;
            color: var(--slate-600);
            line-height: 1.7;
        }

        @media (min-width: 500px) {
            .modal-content-text {
                font-size: 0.95rem;
            }
        }

        /* Footer */
        footer {
            background: var(--brand);
            color: rgba(255, 255, 255, 0.9);
            padding: 1.5rem 2%;
            margin-top: 3rem;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        @media (min-width: 500px) {
            footer {
                padding: 2rem 3%;
            }
        }

        @media (min-width: 768px) {
            footer {
                padding: 2.5rem 4%;
            }
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
        }

        .footer-content h3 {
            color: var(--white);
            margin-bottom: 0.5rem;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-weight: 700;
        }

        .footer-content p {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.6;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        @media (min-width: 500px) {
            .footer-content p {
                font-size: 0.8rem;
            }
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            font-size: 0.7rem;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        @media (min-width: 500px) {
            .footer-bottom {
                font-size: 0.75rem;
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .protocols-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <a href="<?php echo e(route('welcome')); ?>" class="logo">
            <img src="<?php echo e(asset('images/hsc-logo.png')); ?>" alt="HSC Logo">
            <span class="logo-text">SAFETY PROTOCOLS</span>
        </a>
        <nav>
            <a href="<?php echo e(route('welcome')); ?>">Home</a>
            <a href="<?php echo e(route('resources')); ?>">Resources</a>
        </nav>
        <div class="nav-buttons">
            <a href="<?php echo e(route('login')); ?>" class="btn btn-primary">Log In</a>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <h1>Safety <span>Protocols</span> & Procedures</h1>
        <p>Critical operational guidelines and emergency procedures designed to protect Zimbabwe's healthcare workforce from occupational hazards and exposures.</p>
    </section>

    <!-- Protocols Section -->
    <section class="protocols-container">
        <div class="protocols-grid">
            <!-- Protocol 1: Critical Biological & Infection Control -->
            <div class="protocol-card">
                <div class="risk-badge critical"></div>
                <div class="protocol-card-content">
                    <span class="risk-pill critical"><i class="fas fa-circle-exclamation"></i> CRITICAL</span>
                    <h3 class="protocol-card-title">Infectious Pathogen Isolation Protocol</h3>
                    <p class="protocol-card-desc">Immediate clinical zoning, airflow management, and mandatory multi-layer PPE requirements for high-consequence outbreaks.</p>
                    <div class="protocol-meta">
                        <span class="meta-tag"><i class="fas fa-file-contract"></i> SOP</span>
                        <span class="meta-tag"><i class="fas fa-check"></i> MoHCC Approved</span>
                    </div>
                </div>
                <div class="protocol-actions">
                    <button class="btn-protocol btn-view" onclick="openProtocolModal('protocol-1')">
                        <i class="fas fa-eye"></i> View Protocol
                    </button>
                    <button class="btn-protocol btn-print" onclick="printProtocol('protocol-1')">
                        <i class="fas fa-print"></i>
                    </button>
                </div>
            </div>

            <!-- Protocol 2: Sharps & Blood-Borne Pathogens -->
            <div class="protocol-card">
                <div class="risk-badge critical"></div>
                <div class="protocol-card-content">
                    <span class="risk-pill critical"><i class="fas fa-circle-exclamation"></i> CRITICAL</span>
                    <h3 class="protocol-card-title">Needle-Stick Injury Immediate Triage</h3>
                    <p class="protocol-card-desc">The exact step-by-step wash, flush, and report sequence required within the first 60 minutes of accidental clinical exposure.</p>
                    <div class="protocol-meta">
                        <span class="meta-tag"><i class="fas fa-flowchart"></i> Flowchart</span>
                        <span class="meta-tag"><i class="fas fa-clock"></i> Quick-Ref</span>
                    </div>
                </div>
                <div class="protocol-actions">
                    <button class="btn-protocol btn-view" onclick="openProtocolModal('protocol-2')">
                        <i class="fas fa-eye"></i> View Protocol
                    </button>
                    <button class="btn-protocol btn-print" onclick="printProtocol('protocol-2')">
                        <i class="fas fa-print"></i>
                    </button>
                </div>
            </div>

            <!-- Protocol 3: Chemical & Hazardous Material Spills -->
            <div class="protocol-card">
                <div class="risk-badge chemical"></div>
                <div class="protocol-card-content">
                    <span class="risk-pill chemical"><i class="fas fa-triangle-exclamation"></i> CHEMICAL SAFETY</span>
                    <h3 class="protocol-card-title">Cytotoxic & Sterilizer Spill Containment</h3>
                    <p class="protocol-card-desc">Isolation and neutralization steps for chemotherapy agents and industrial ward disinfectants using standard hospital spill kits.</p>
                    <div class="protocol-meta">
                        <span class="meta-tag"><i class="fas fa-book"></i> COSHH Compliant</span>
                        <span class="meta-tag"><i class="fas fa-sheet-plastic"></i> SDS Sheet</span>
                    </div>
                </div>
                <div class="protocol-actions">
                    <button class="btn-protocol btn-view" onclick="openProtocolModal('protocol-3')">
                        <i class="fas fa-eye"></i> View Protocol
                    </button>
                    <button class="btn-protocol btn-print" onclick="printProtocol('protocol-3')">
                        <i class="fas fa-print"></i>
                    </button>
                </div>
            </div>

            <!-- Protocol 4: Physical & Ward Infrastructure Safety -->
            <div class="protocol-card">
                <div class="risk-badge chemical"></div>
                <div class="protocol-card-content">
                    <span class="risk-pill chemical"><i class="fas fa-triangle-exclamation"></i> PHYSICAL RISK</span>
                    <h3 class="protocol-card-title">Safe Patient Handling & Lumbar Ergonomics</h3>
                    <p class="protocol-card-desc">Mechanical and manual transfer guidelines to eliminate workplace back injuries during critical patient adjustments.</p>
                    <div class="protocol-meta">
                        <span class="meta-tag"><i class="fas fa-image"></i> Illustrated</span>
                        <span class="meta-tag"><i class="fas fa-user-nurse"></i> Nursing Care</span>
                    </div>
                </div>
                <div class="protocol-actions">
                    <button class="btn-protocol btn-view" onclick="openProtocolModal('protocol-4')">
                        <i class="fas fa-eye"></i> View Protocol
                    </button>
                    <button class="btn-protocol btn-print" onclick="printProtocol('protocol-4')">
                        <i class="fas fa-print"></i>
                    </button>
                </div>
            </div>

            <!-- Protocol 5: Emergency & Crisis Evacuation -->
            <div class="protocol-card">
                <div class="risk-badge critical"></div>
                <div class="protocol-card-content">
                    <span class="risk-pill critical"><i class="fas fa-circle-exclamation"></i> EMERGENCY</span>
                    <h3 class="protocol-card-title">Hospital Fire & Power Failure Evacuation Routes</h3>
                    <p class="protocol-card-desc">Zoned structural protocols for moving non-ambulatory and intensive care patients during facility emergencies.</p>
                    <div class="protocol-meta">
                        <span class="meta-tag"><i class="fas fa-map"></i> Evacuation Template</span>
                        <span class="meta-tag"><i class="fas fa-building"></i> Blueprint</span>
                    </div>
                </div>
                <div class="protocol-actions">
                    <button class="btn-protocol btn-view" onclick="openProtocolModal('protocol-5')">
                        <i class="fas fa-eye"></i> View Protocol
                    </button>
                    <button class="btn-protocol btn-print" onclick="printProtocol('protocol-5')">
                        <i class="fas fa-print"></i>
                    </button>
                </div>
            </div>

            <!-- Protocol 6: Environmental & Heat Stress -->
            <div class="protocol-card">
                <div class="risk-badge occupational"></div>
                <div class="protocol-card-content">
                    <span class="risk-pill occupational"><i class="fas fa-info-circle"></i> OCCUPATIONAL HYGIENE</span>
                    <h3 class="protocol-card-title">Extended PPE Wear & Hydration Cycle Protocol</h3>
                    <p class="protocol-card-desc">Mandated shift rotation timelines and hydration intervals for staff operating in high-temperature or poorly ventilated clinical wings.</p>
                    <div class="protocol-meta">
                        <span class="meta-tag"><i class="fas fa-heart"></i> Wellness</span>
                        <span class="meta-tag"><i class="fas fa-briefing"></i> Toolbox Talk</span>
                    </div>
                </div>
                <div class="protocol-actions">
                    <button class="btn-protocol btn-view" onclick="openProtocolModal('protocol-6')">
                        <i class="fas fa-eye"></i> View Protocol
                    </button>
                    <button class="btn-protocol btn-print" onclick="printProtocol('protocol-6')">
                        <i class="fas fa-print"></i>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Protocol Modals -->
    <div class="protocol-modal" id="protocolModal">
        <div class="protocol-modal-content">
            <button class="close-protocol-modal" onclick="closeProtocolModal()">
                <i class="fas fa-times"></i>
            </button>
            <div id="modalBody"></div>
        </div>
    </div>

    <!-- Footer -->
    <?php if (isset($component)) { $__componentOriginal8a8716efb3c62a45938aca52e78e0322 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a8716efb3c62a45938aca52e78e0322 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.footer','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8a8716efb3c62a45938aca52e78e0322)): ?>
<?php $attributes = $__attributesOriginal8a8716efb3c62a45938aca52e78e0322; ?>
<?php unset($__attributesOriginal8a8716efb3c62a45938aca52e78e0322); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8a8716efb3c62a45938aca52e78e0322)): ?>
<?php $component = $__componentOriginal8a8716efb3c62a45938aca52e78e0322; ?>
<?php unset($__componentOriginal8a8716efb3c62a45938aca52e78e0322); ?>
<?php endif; ?>

    <script>
        const protocols = {
            'protocol-1': {
                title: 'Infectious Pathogen Isolation Protocol',
                riskLevel: 'CRITICAL',
                riskClass: 'critical',
                sections: [
                    {
                        heading: '🔴 When to Activate',
                        content: 'This protocol activates immediately upon identification of highly contagious pathogens (e.g., airborne-transmissible infections, viral hemorrhagic fevers, multidrug-resistant tuberculosis).'
                    },
                    {
                        heading: '🛡️ Immediate Actions (First 15 minutes)',
                        list: [
                            'Isolate patient in designated negative-pressure room',
                            'Don full PPE: N95/FFP3 mask, eye protection, gown, gloves',
                            'Activate facility infection control team',
                            'Notify facility leadership and local health authorities',
                            'Begin documentation of all staff contacts',
                            'Restrict access to isolated area with signage'
                        ]
                    },
                    {
                        heading: '🔄 Ongoing Protocols',
                        list: [
                            'Maintain airflow management and room pressurization',
                            'Conduct twice-daily PPE stock audits',
                            'Enforce strict hand hygiene between patient contacts',
                            'Implement shift rotation (4-hour maximum exposure per staff)',
                            'Monitor staff for fever, cough, or symptoms daily'
                        ]
                    },
                    {
                        heading: '📋 Exit Requirements',
                        list: [
                            'Patient remains isolated until confirmed negative (2 tests, 48 hours apart)',
                            'All affected staff complete decontamination and medical monitoring',
                            'Submit incident to national health surveillance system',
                            'Conduct ward decontamination with approved disinfectant'
                        ]
                    }
                ]
            },
            'protocol-2': {
                title: 'Needle-Stick Injury Immediate Triage',
                riskLevel: 'CRITICAL',
                riskClass: 'critical',
                sections: [
                    {
                        heading: '⏱️ Critical Timeline: First 60 Minutes',
                        content: 'Immediate action within the first hour significantly reduces infection risk. Every minute counts.'
                    },
                    {
                        heading: '🚨 Step 1: Immediate Wound Management (0-5 minutes)',
                        list: [
                            'STOP work immediately - do not panic',
                            'Encourage wound to bleed gently (do NOT forcefully squeeze)',
                            'Wash with soap and running water for 30 seconds minimum',
                            'Rinse thoroughly under running water',
                            'Dry with clean cloth',
                            'Cover wound with waterproof dressing'
                        ]
                    },
                    {
                        heading: '📞 Step 2: Report & Documentation (5-15 minutes)',
                        list: [
                            'Notify your supervisor/charge nurse immediately',
                            'Identify source patient if known (for serology testing)',
                            'Complete incident form with exact time and needle gauge',
                            'Photograph needle/sharp if available (for records)',
                            'Do NOT discard the needle - retain for blood testing'
                        ]
                    },
                    {
                        heading: '🏥 Step 3: Medical Evaluation (15-60 minutes)',
                        list: [
                            'Visit occupational health clinic or emergency department',
                            'Baseline serology testing (HIV, HBV, HCV)',
                            'Evaluate source patient blood status if available',
                            'Initiate PEP (Post-Exposure Prophylaxis) if indicated - <2 hour window critical',
                            'Receive wound care and tetanus prophylaxis if needed'
                        ]
                    }
                ]
            },
            'protocol-3': {
                title: 'Cytotoxic & Sterilizer Spill Containment',
                riskLevel: 'CHEMICAL SAFETY',
                riskClass: 'chemical',
                sections: [
                    {
                        heading: '⚠️ Hazard Overview',
                        content: 'Cytotoxic drugs and sterilizing agents (e.g., glutaraldehyde, ethylene oxide) pose serious respiratory, skin, and systemic toxicity risks. Spills require immediate containment and professional cleanup.'
                    },
                    {
                        heading: '🛡️ Personal Protection (Don Before Cleanup)',
                        list: [
                            'Heavy-duty nitrile gloves (2 pairs)',
                            'Chemical-resistant apron or gown',
                            'Eye protection (goggles, not glasses)',
                            'Respiratory protection: FFP3 mask minimum (N95 inadequate)',
                            'Closed-toe shoes with spill coverage'
                        ]
                    },
                    {
                        heading: '🧹 Containment Steps',
                        list: [
                            'Evacuate area immediately - close room doors',
                            'Place "Wet Floor / Hazardous Material" warning signs',
                            'Use spill kit: absorbent pads to surround spill perimeter',
                            'Apply appropriate neutralizer (follow SDS sheet) - allow 5-10 minutes contact',
                            'Wipe area multiple times with disposable cloths, discard in hazmat bag',
                            'Double-bag all contaminated materials as chemical waste'
                        ]
                    },
                    {
                        heading: '📋 Post-Incident',
                        list: [
                            'Ventilate area for minimum 30 minutes',
                            'Report to hazmat/waste coordinator',
                            'Complete spill incident log',
                            'Schedule professional waste disposal pickup',
                            'Monitor staff for respiratory symptoms (delayed reaction possible)'
                        ]
                    }
                ]
            },
            'protocol-4': {
                title: 'Safe Patient Handling & Lumbar Ergonomics',
                riskLevel: 'PHYSICAL RISK',
                riskClass: 'chemical',
                sections: [
                    {
                        heading: '💪 Preventing Back Injuries',
                        content: 'Back injuries account for 20% of healthcare worker occupational injuries. Proper technique and mechanical aids prevent permanent disability.'
                    },
                    {
                        heading: '❌ Never Do (High-Risk Moves)',
                        list: [
                            'Lift patients without mechanical assistance (hoists, slide sheets)',
                            'Twist your spine while lifting - reposition your feet instead',
                            'Lift from floor level or above shoulder height',
                            'Perform manual lifts from bed to wheelchair without assessment'
                        ]
                    },
                    {
                        heading: '✅ Proper Manual Handling Technique',
                        list: [
                            'Assess patient mobility BEFORE attempting lift',
                            'Position feet shoulder-width apart for stability',
                            'Keep knees slightly bent, bend from hips not waist',
                            'Hold patient close to your body (not at arm\'s length)',
                            'Maintain neutral spine - no twisting',
                            'Use mechanical aids whenever possible (bed hoists, roller boards, slide sheets)'
                        ]
                    },
                    {
                        heading: '🦾 Mechanical Aids Available',
                        list: [
                            'Patient hoists: for bed-to-chair transfers (load limit: read nameplate)',
                            'Slide sheets: for repositioning in bed or lateral transfers',
                            'Transfer belts: patient wearing belt pulled under armpits during stand-pivot transfer',
                            'Walking aids: walker or crutches for patient ambulation support'
                        ]
                    },
                    {
                        heading: '⚡ If Pain Occurs',
                        list: [
                            'STOP immediately - do not push through pain',
                            'Report to occupational health',
                            'Ice/heat as advised by physiotherapist',
                            'Early intervention prevents chronic conditions'
                        ]
                    }
                ]
            },
            'protocol-5': {
                title: 'Hospital Fire & Power Failure Evacuation Routes',
                riskLevel: 'EMERGENCY',
                riskClass: 'critical',
                sections: [
                    {
                        heading: '🚨 Activation Triggers',
                        content: 'Evacuation begins immediately when: (1) Fire alarm sounds, (2) Smoke visible, (3) Facility manager announces evacuation, (4) Total power failure >15 minutes.'
                    },
                    {
                        heading: '👥 Chain of Command',
                        list: [
                            'Facility manager: Overall coordinator, final go/no-go decision',
                            'Department heads: Account for all staff and patients, report to coordinator',
                            'Nurses: Prepare patients, organize orderly movement',
                            'Security: Manage exit routes, prevent re-entry',
                            'Support staff: Assist with patient transfers, carry medical records'
                        ]
                    },
                    {
                        heading: '🏥 Patient Evacuation Sequence',
                        list: [
                            'FIRST: Ambulatory patients self-evacuate via designated stairwells',
                            'SECOND: Non-ambulatory patients via stair-descent evacuation chairs (trained staff)',
                            'THIRD: Intensive care/critical patients last (requires manual carry + monitoring)',
                            'All patients: Pre-packed evacuation folder with medical summary, allergies, medications'
                        ]
                    },
                    {
                        heading: '🗂️ Document Preservation',
                        list: [
                            'Each ward keeps red evacuation folder with current patient list',
                            'Medical records: Scanned backups stored off-site (quarterly verification)',
                            'Medications: Mobile crash carts pre-positioned for ICU evacuation'
                        ]
                    },
                    {
                        heading: '📍 Assembly Point Procedure',
                        list: [
                            'Meet at designated assembly area (north car park - 150m minimum from facility)',
                            'Department heads conduct headcount (accountability)',
                            'Contact facility security for all-clear to re-enter',
                            'Conduct post-incident review within 24 hours'
                        ]
                    }
                ]
            },
            'protocol-6': {
                title: 'Extended PPE Wear & Hydration Cycle Protocol',
                riskLevel: 'OCCUPATIONAL HYGIENE',
                riskClass: 'occupational',
                sections: [
                    {
                        heading: '☀️ High-Heat & Humid Zone Definition',
                        content: 'Wards with ambient temperature >28°C AND humidity >60%, plus staff wearing full PPE (gown, multiple gloves, N95 mask) for continuous periods.'
                    },
                    {
                        heading: '🧑‍⚕️ Mandatory Shift Rotation (Maximum Exposure)',
                        list: [
                            '4-hour continuous PPE wear maximum per staff member',
                            '1-hour minimum break outside PPE zone (removal, cooling, hydration)',
                            'No more than 2 rotations per 12-hour shift for same individual',
                            'Swap staff: If outbreak extends beyond 2 shifts, rotate external staff in'
                        ]
                    },
                    {
                        heading: '💧 Hydration Protocol',
                        list: [
                            'Drink 250mL (1 cup) water every 15 minutes during PPE wear',
                            'Electrolyte solution (sports drink) every 30 minutes if available',
                            'Break room: Temperature-controlled area, ice water stations, chairs for sitting',
                            'Monitor urine color: pale = adequate hydration, dark yellow = dehydration warning'
                        ]
                    },
                    {
                        heading: '⚠️ Heat Stress Symptoms - REPORT IMMEDIATELY',
                        list: [
                            'Dizziness, lightheadedness, disorientation',
                            'Nausea, vomiting',
                            'Rapid heartbeat (>120 bpm) or chest tightness',
                            'Cessation of sweating (dangerous - heat stroke imminent)',
                            'Confusion or difficulty concentrating'
                        ]
                    },
                    {
                        heading: '🆘 Emergency Response (Heat Stroke Suspected)',
                        list: [
                            'Remove staff from hot environment immediately',
                            'Move to cool area, place in recovery position',
                            'Remove excess clothing, apply cool (not cold) water to skin',
                            'Call ambulance - heat stroke is medical emergency (40°C+ core temp lethal)',
                            'Continue monitoring vital signs until ambulance arrives'
                        ]
                    }
                ]
            }
        };

        function openProtocolModal(protocolId) {
            const protocol = protocols[protocolId];
            if (!protocol) return;

            let html = `<h2 class="modal-title">${protocol.title}</h2>`;
            html += `<div class="modal-risk-pill ${protocol.riskClass}">
                <i class="fas fa-circle-exclamation"></i> ${protocol.riskLevel}
            </div>`;

            protocol.sections.forEach(section => {
                html += `<div class="modal-content-section">
                    <h3>${section.heading}</h3>`;
                
                if (section.list) {
                    html += '<ul>';
                    section.list.forEach(item => {
                        html += `<li>${item}</li>`;
                    });
                    html += '</ul>';
                } else {
                    html += `<p class="modal-content-text">${section.content}</p>`;
                }
                
                html += '</div>';
            });

            document.getElementById('modalBody').innerHTML = html;
            document.getElementById('protocolModal').classList.add('active');
            // Trap focus within modal
            document.getElementById('protocolModal').focus();
        }

        function closeProtocolModal() {
            document.getElementById('protocolModal').classList.remove('active');
        }

        function printProtocol(protocolId) {
            const protocol = protocols[protocolId];
            if (!protocol) return;

            let printContent = `<h1 style="text-align: center; margin-bottom: 2rem;">${protocol.title}</h1>`;
            printContent += `<p style="text-align: center; font-weight: bold; margin-bottom: 2rem;">Risk Level: ${protocol.riskLevel}</p>`;

            protocol.sections.forEach(section => {
                printContent += `<h2 style="margin-top: 1.5rem; margin-bottom: 0.75rem;">${section.heading}</h2>`;
                
                if (section.list) {
                    printContent += '<ul style="margin-bottom: 1rem;">';
                    section.list.forEach(item => {
                        printContent += `<li style="margin-bottom: 0.5rem;">${item}</li>`;
                    });
                    printContent += '</ul>';
                } else {
                    printContent += `<p style="margin-bottom: 1rem;">${section.content}</p>`;
                }
            });

            printContent += `<p style="margin-top: 3rem; font-size: 0.9rem; text-align: center;">
                © 2026 Health Service Commission. All rights reserved.<br>
                Printed on: ${new Date().toLocaleDateString()}
            </p>`;

            const printWindow = window.open('', '', 'width=800,height=600');
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="utf-8">
                    <title>${protocol.title}</title>
                    <style>
                        body { font-family: Arial, sans-serif; max-width: 800px; margin: 2rem; line-height: 1.6; }
                        h1 { color: #1D4533; }
                        h2 { color: #2a5a47; margin-top: 1.5rem; }
                        ul { margin: 1rem 0; }
                        li { margin-bottom: 0.5rem; }
                        @media print { body { margin: 1rem; } }
                    </style>
                </head>
                <body>
                    ${printContent}
                </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.print();
        }

        // Close modal when clicking outside
        document.getElementById('protocolModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeProtocolModal();
            }
        });
    </script>
</body>
</html>
<?php /**PATH D:\OccupationalHealth System\resources\views\safety-protocols.blade.php ENDPATH**/ ?>