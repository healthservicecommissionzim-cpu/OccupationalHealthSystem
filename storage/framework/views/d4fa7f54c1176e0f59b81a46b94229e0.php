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
    <style>
        :root {
            --brand: #1D4533;
            --brand-light: #f0fdf4;
            --dark: #000000;
            --slate: #475569;
            --light: #FBFBFA;
            --white: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #0f172a;
            overflow-x: hidden;
            background: linear-gradient(to bottom, #ffffff 0%, #f9fafb 100%);
        }

        /* Header */
        header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            padding: 1rem 2%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(0,0,0,0.05);
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
            max-width: 70%;
        }

        .logo img {
            height: 22px;
            flex-shrink: 0;
        }

        @media (min-width: 768px) {
            .logo img {
                height: 28px;
            }
            
            .logo {
                max-width: none;
            }
        }

        .logo-text {
            font-weight: 800;
            font-size: 0.6rem;
            color: var(--brand);
            letter-spacing: -0.02em;
            line-height: 1.1;
            display: block;
        }

        @media (min-width: 400px) {
            .logo-text {
                font-size: 0.7rem;
            }
        }

        @media (min-width: 500px) {
            .logo-text {
                font-size: 0.8rem;
            }
        }

        @media (min-width: 768px) {
            .logo-text {
                font-size: 1rem;
            }
        }

        /* Desktop Navigation */
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
            color: var(--slate);
            font-weight: 500;
            font-size: 0.9rem;
            transition: color 0.3s;
        }

        nav a:hover {
            color: var(--brand);
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: flex;
            align-items: center;
            justify-content: center;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.5rem;
            width: 44px;
            height: 44px;
        }

        @media (min-width: 769px) {
            .mobile-menu-toggle {
                display: none;
            }
        }

        .mobile-menu-toggle:focus-visible {
            outline: 2px solid var(--brand);
            outline-offset: 2px;
        }

        .hamburger-icon {
            width: 24px;
            height: 18px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            pointer-events: none;
        }

        .hamburger-line {
            width: 100%;
            height: 3px;
            background-color: var(--brand);
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .mobile-menu-toggle.open .hamburger-line {
            background-color: var(--brand);
        }

        .mobile-menu-toggle.open .hamburger-line:nth-child(1) {
            transform: rotate(45deg) translate(6px, 6px);
        }

        .mobile-menu-toggle.open .hamburger-line:nth-child(2) {
            opacity: 0;
            transform: translateX(-20px);
        }

        .mobile-menu-toggle.open .hamburger-line:nth-child(3) {
            transform: rotate(-45deg) translate(6px, -6px);
        }

        /* Mobile Menu Backdrop */
        .mobile-menu-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 2000;
            pointer-events: none;
        }

        .mobile-menu-backdrop.active {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        /* Mobile Menu Overlay */
        .mobile-menu {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100dvh;
            background: white;
            opacity: 0;
            transform: translateY(-100%);
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.4s ease;
            z-index: 2001;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            pointer-events: none;
        }

        .mobile-menu.active {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }

        .mobile-menu-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            background: var(--brand-light);
        }

        .mobile-menu-close {
            background: none;
            border: none;
            font-size: 1.8rem;
            color: var(--brand);
            cursor: pointer;
            padding: 0.5rem;
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: background 0.3s;
        }

        .mobile-menu-close:hover {
            background: rgba(29, 69, 51, 0.1);
        }

        .mobile-menu-nav {
            display: flex !important;
            flex-direction: column;
            padding: 2rem 0;
            gap: 0.5rem;
            flex: 1;
            justify-content: center;
        }

        .mobile-menu-nav a {
            text-decoration: none !important;
            color: var(--dark) !important;
            font-weight: 600;
            font-size: 1.5rem;
            padding: 1.25rem 2rem;
            border-left: 4px solid transparent;
            transition: all 0.3s;
            text-align: left;
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
        }

        .mobile-menu-nav a:hover {
            background: var(--brand-light);
            color: var(--brand);
            border-left-color: var(--brand);
            padding-left: 2.5rem;
        }

        .mobile-menu-buttons {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            padding: 2rem;
            margin-top: auto;
        }

        .mobile-menu-buttons .btn {
            justify-content: center;
            width: 100%;
            padding: 1rem 2rem;
            font-size: 1.1rem;
        }
            padding: 1rem 1.5rem;
        }

        .mobile-menu-buttons .btn {
            justify-content: center;
            width: 100%;
        }

        /* Mobile Menu Backdrop */
        .mobile-menu-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            background: rgba(0, 0, 0, 0.5);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 1999;
        }

        .mobile-menu-backdrop.active {
            opacity: 1;
            visibility: visible;
        }

        .nav-buttons {
            display: none;
        }

        @media (min-width: 769px) {
            .nav-buttons {
                display: flex;
                gap: 0.75rem;
                align-items: center;
            }
        }

        .btn {
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s;
            font-size: 0.85rem;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: var(--brand);
            color: white;
            box-shadow: 0 4px 12px rgba(29, 69, 51, 0.2);
        }

        .btn-primary:hover {
            background: #163b28;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(29, 69, 51, 0.3);
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%);
            padding: 4rem 4%;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero h1 {
            font-size: clamp(2rem, 5vw, 3rem);
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 1rem;
            line-height: 1.2;
            letter-spacing: -0.02em;
        }

        .hero h1 span {
            color: var(--brand);
        }

        .hero p {
            font-size: 1.1rem;
            color: var(--slate);
            max-width: 700px;
            margin: 0 auto 2rem;
            line-height: 1.6;
        }

        /* Search Bar */
        .search-container {
            max-width: 600px;
            margin: 0 auto 2rem;
            position: relative;
        }

        .search-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-input {
            width: 100%;
            padding: 0.9rem 1.2rem 0.9rem 2.8rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.95rem;
            font-family: inherit;
            transition: all 0.3s;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--brand);
            box-shadow: 0 0 0 3px rgba(29, 69, 51, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            color: var(--slate);
            font-size: 1.1rem;
        }

        /* Resource Sections */
        .resources-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 3rem 4%;
        }

        .resource-section {
            margin-bottom: 4rem;
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 3px solid var(--brand);
        }

        .section-icon {
            font-size: 2rem;
            color: var(--brand);
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark);
            margin: 0;
        }

        /* Resource Grid */
        .resource-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .resource-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 8px 32px rgba(29, 69, 51, 0.12), 0 2px 8px rgba(29, 69, 51, 0.08);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .resource-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 48px rgba(29, 69, 51, 0.16), 0 4px 12px rgba(29, 69, 51, 0.12);
        }

        .resource-card-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--dark);
            margin: 0;
        }

        .resource-card-desc {
            font-size: 0.9rem;
            color: var(--slate);
            line-height: 1.6;
            margin: 0;
        }

        .resource-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.6rem;
            margin-top: auto;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.4rem 0.8rem;
            background: var(--brand-light);
            color: var(--brand);
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge.type {
            background: #fef3c7;
            color: #92400e;
        }

        .btn-download {
            padding: 0.6rem 1rem;
            background: var(--brand);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s;
        }

        .btn-download:hover {
            background: #163b28;
            transform: translateY(-2px);
        }

        /* Info Bar */
        .info-bar {
            background: linear-gradient(135deg, #f0fdf4 0%, #e8f5e9 100%);
            border-left: 4px solid var(--brand);
            padding: 1.2rem 1.5rem;
            border-radius: 8px;
            margin-top: 3rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .info-bar-icon {
            font-size: 1.5rem;
            color: var(--brand);
            flex-shrink: 0;
        }

        .info-bar-content {
            flex: 1;
        }

        .info-bar-text {
            font-size: 0.9rem;
            color: var(--slate);
            line-height: 1.5;
            margin-bottom: 0.75rem;
        }

        .info-bar-btn {
            padding: 0.5rem 1rem;
            background: var(--brand);
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-bar-btn:hover {
            background: #163b28;
        }

        /* Footer */
        footer {
            background: var(--brand);
            color: rgba(255, 255, 255, 0.9);
            padding: 2rem 4%;
            margin-top: 4rem;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
        }

        .footer-content h3 {
            color: white;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .footer-content p {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.6;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            font-size: 0.8rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            header {
                padding: 1rem 2%;
            }

            .hero {
                padding: 2.5rem 2%;
            }

            .resources-container {
                padding: 2rem 2%;
            }

            .resource-grid {
                grid-template-columns: 1fr;
            }

            .search-input {
                font-size: 1rem;
            }

            .info-bar {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <a href="<?php echo e(route('welcome')); ?>" class="logo">
            <img src="<?php echo e(asset('images/hsc-logo.png')); ?>" alt="HSC Logo">
            <span class="logo-text">HEALTH SERVICE COMMISSION</span>
        </a>
        <nav>
            <a href="<?php echo e(route('welcome')); ?>">Home</a>
            <a href="<?php echo e(route('resources')); ?>">Resources</a>
            <a href="<?php echo e(route('safety_protocols')); ?>">Safety Protocols</a>
        </nav>
        <div class="nav-buttons">
            <a href="<?php echo e(route('login')); ?>" class="btn btn-primary">Log In</a>
            <a href="<?php echo e(route('register')); ?>" class="btn btn-primary">Register</a>
        </div>
        <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
            <div class="hamburger-icon">
                <div class="hamburger-line"></div>
                <div class="hamburger-line"></div>
                <div class="hamburger-line"></div>
            </div>
        </button>
    </header>

    <!-- Mobile Menu -->
    <div class="mobile-menu-backdrop" onclick="toggleMobileMenu()"></div>
    <div class="mobile-menu">
        <div class="mobile-menu-header">
            <span style="font-weight: 700; color: var(--brand); font-size: 1.2rem;">Menu</span>
            <button class="mobile-menu-close" onclick="toggleMobileMenu()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <nav class="mobile-menu-nav">
            <a href="<?php echo e(route('welcome')); ?>">Home</a>
            <a href="<?php echo e(route('resources')); ?>">Resources</a>
            <a href="<?php echo e(route('safety_protocols')); ?>">Safety Protocols</a>
        </nav>
        <div class="mobile-menu-buttons">
            <a href="<?php echo e(route('login')); ?>" class="btn btn-primary">Log In</a>
            <a href="<?php echo e(route('register')); ?>" class="btn btn-primary">Register</a>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="hero">
        <h1>Safety <span>Protocols</span> & Procedures</h1>
        <p>Critical operational guidelines and emergency procedures designed to protect Zimbabwe's healthcare workforce from occupational hazards and exposures.</p>
    </section>

    <!-- Safety Protocols Section -->
    <section class="resources-container" id="protocols">
        <div class="resource-section">
            <div class="section-header">
                <span class="section-icon">⚠️</span>
                <h2 class="section-title">Infectious Disease & Exposure Control</h2>
            </div>
            <div class="resource-grid">
                <div class="resource-card">
                    <h3 class="resource-card-title">Infectious Pathogen Isolation Protocol</h3>
                    <p class="resource-card-desc">Airborne and droplet precautions for TB, COVID-19, and emerging respiratory infections in clinical settings.</p>
                    <div class="resource-meta">
                        <span class="badge">⚠️ CRITICAL</span>
                        <span class="badge type">Infectious</span>
                    </div>
                    <button class="btn-download">
                        <i class="fas fa-eye"></i> View Protocol
                    </button>
                </div>

                <div class="resource-card">
                    <h3 class="resource-card-title">Needle-Stick Injury Response</h3>
                    <p class="resource-card-desc">Immediate post-exposure prophylaxis (PEP) protocol and blood-borne pathogen management flowchart.</p>
                    <div class="resource-meta">
                        <span class="badge">⚠️ CRITICAL</span>
                        <span class="badge type">Occupational</span>
                    </div>
                    <button class="btn-download">
                        <i class="fas fa-eye"></i> View Protocol
                    </button>
                </div>

                <div class="resource-card">
                    <h3 class="resource-card-title">PPE Donning & Doffing Sequence</h3>
                    <p class="resource-card-desc">Step-by-step visual guide to proper personal protective equipment use and contamination prevention.</p>
                    <div class="resource-meta">
                        <span class="badge">⚠️ CRITICAL</span>
                        <span class="badge type">Infectious</span>
                    </div>
                    <button class="btn-download">
                        <i class="fas fa-eye"></i> View Protocol
                    </button>
                </div>
            </div>
        </div>

        <div class="resource-section">
            <div class="section-header">
                <span class="section-icon">☣️</span>
                <h2 class="section-title">Chemical Hazards & Spill Management</h2>
            </div>
            <div class="resource-grid">
                <div class="resource-card">
                    <h3 class="resource-card-title">Cytotoxic Spill Containment</h3>
                    <p class="resource-card-desc">Emergency decontamination procedures for chemotherapy agent exposure and contaminated surface cleanup.</p>
                    <div class="resource-meta">
                        <span class="badge">☣️ CHEMICAL</span>
                        <span class="badge type">Emergency</span>
                    </div>
                    <button class="btn-download">
                        <i class="fas fa-eye"></i> View Protocol
                    </button>
                </div>

                <div class="resource-card">
                    <h3 class="resource-card-title">Sterilizer Safety & Vapor Exposure</h3>
                    <p class="resource-card-desc">Ethylene oxide and glutaraldehyde handling protocols including ventilation requirements and leak response.</p>
                    <div class="resource-meta">
                        <span class="badge">☣️ CHEMICAL</span>
                        <span class="badge type">Occupational</span>
                    </div>
                    <button class="btn-download">
                        <i class="fas fa-eye"></i> View Protocol
                    </button>
                </div>

                <div class="resource-card">
                    <h3 class="resource-card-title">Formaldehyde Handling Standards</h3>
                    <p class="resource-card-desc">Pathology and mortuary safety guidelines for formaldehyde storage, dilution, and vapor control measures.</p>
                    <div class="resource-meta">
                        <span class="badge">☣️ CHEMICAL</span>
                        <span class="badge type">Occupational</span>
                    </div>
                    <button class="btn-download">
                        <i class="fas fa-eye"></i> View Protocol
                    </button>
                </div>
            </div>
        </div>

        <div class="resource-section">
            <div class="section-header">
                <span class="section-icon">🏥</span>
                <h2 class="section-title">Physical Hazards & Ergonomics</h2>
            </div>
            <div class="resource-grid">
                <div class="resource-card">
                    <h3 class="resource-card-title">Safe Patient Handling & Transfers</h3>
                    <p class="resource-card-desc">Manual lifting elimination techniques using mechanical hoists and proper body mechanics to prevent back injuries.</p>
                    <div class="resource-meta">
                        <span class="badge">🏥 OCCUPATIONAL</span>
                        <span class="badge type">Ergonomics</span>
                    </div>
                    <button class="btn-download">
                        <i class="fas fa-eye"></i> View Protocol
                    </button>
                </div>

                <div class="resource-card">
                    <h3 class="resource-card-title">Extended PPE Wear Protocol</h3>
                    <p class="resource-card-desc">Heat stress prevention and hydration strategies for prolonged protective equipment use during isolation shifts.</p>
                    <div class="resource-meta">
                        <span class="badge">🏥 OCCUPATIONAL</span>
                        <span class="badge type">Wellness</span>
                    </div>
                    <button class="btn-download">
                        <i class="fas fa-eye"></i> View Protocol
                    </button>
                </div>

                <div class="resource-card">
                    <h3 class="resource-card-title">Radiation Protection in Imaging</h3>
                    <p class="resource-card-desc">X-ray and fluoroscopy safety including lead apron requirements, distance protocols, and dosimetry badge use.</p>
                    <div class="resource-meta">
                        <span class="badge">🏥 OCCUPATIONAL</span>
                        <span class="badge type">Radiology</span>
                    </div>
                    <button class="btn-download">
                        <i class="fas fa-eye"></i> View Protocol
                    </button>
                </div>
            </div>
        </div>

        <div class="resource-section">
            <div class="section-header">
                <span class="section-icon">🚨</span>
                <h2 class="section-title">Emergency Response Procedures</h2>
            </div>
            <div class="resource-grid">
                <div class="resource-card">
                    <h3 class="resource-card-title">Hospital Fire Evacuation Routes</h3>
                    <p class="resource-card-desc">Ward-specific egress maps, patient evacuation priorities, and assembly point locations for fire emergencies.</p>
                    <div class="resource-meta">
                        <span class="badge">🚨 EMERGENCY</span>
                        <span class="badge type">Fire Safety</span>
                    </div>
                    <button class="btn-download">
                        <i class="fas fa-eye"></i> View Protocol
                    </button>
                </div>

                <div class="resource-card">
                    <h3 class="resource-card-title">Oxygen Cylinder Safety & Leak Response</h3>
                    <p class="resource-card-desc">Medical gas storage, transport precautions, and immediate actions for cylinder valve failures or leaks.</p>
                    <div class="resource-meta">
                        <span class="badge">🚨 EMERGENCY</span>
                        <span class="badge type">Chemical</span>
                    </div>
                    <button class="btn-download">
                        <i class="fas fa-eye"></i> View Protocol
                    </button>
                </div>

                <div class="resource-card">
                    <h3 class="resource-card-title">Workplace Violence De-escalation</h3>
                    <p class="resource-card-desc">Verbal de-escalation techniques, panic button locations, and security response protocols for aggressive patients.</p>
                    <div class="resource-meta">
                        <span class="badge">🚨 EMERGENCY</span>
                        <span class="badge type">Security</span>
                    </div>
                    <button class="btn-download">
                        <i class="fas fa-eye"></i> View Protocol
                    </button>
                </div>
            </div>
        </div>

        <!-- Info Bar -->
        <div class="info-bar">
            <div class="info-bar-icon">
                <i class="fas fa-print"></i>
            </div>
            <div class="info-bar-content">
                <p class="info-bar-text"><strong>Quick Reference Cards:</strong> All protocols are available as laminated pocket cards. Request physical copies from your facility's OHS coordinator for ward display and personal reference.</p>
                <button class="info-bar-btn">
                    <i class="fas fa-download"></i> Download All Protocols (PDF Bundle)
                </button>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <h3>Health Service Commission</h3>
            <p>Working Together for People's Health</p>
            <p style="font-size: 0.75rem; margin-top: 0.75rem;">
                © 2026 Health Service Commission. All rights reserved.
            </p>
            <div class="footer-bottom">
                For accessibility support, contact: resources@injury.hsc.org.zw
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        function toggleMobileMenu() {
            const menu = document.querySelector('.mobile-menu');
            const backdrop = document.querySelector('.mobile-menu-backdrop');
            const toggle = document.querySelector('.mobile-menu-toggle');
            
            menu.classList.toggle('active');
            backdrop.classList.toggle('active');
            toggle.classList.toggle('open');
            
            document.body.style.overflow = menu.classList.contains('active') ? 'hidden' : '';
        }
    </script>
</body>
</html>
<?php /**PATH D:\OccupationalHealth System\resources\views\safety-protocols.blade.php ENDPATH**/ ?>