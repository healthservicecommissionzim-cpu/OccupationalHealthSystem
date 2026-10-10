<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>HSC-OHS | Resources & Compliance Hub - Zimbabwe Healthcare Workers</title>
    <meta name="description" content="Access comprehensive occupational health resources, compliance guidelines, clinical protocols, and wellness toolkits for Zimbabwe's healthcare workforce.">
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

        /* Hero Section with Integrated Header */
        .hero-header {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(12px);
            padding: 1.25rem 4%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }

        .logo img {
            height: 55px;
        }

        .logo-text {
            font-weight: 800;
            font-size: 1rem;
            color: var(--brand);
            letter-spacing: -0.02em;
        }

        nav {
            display: flex;
            gap: 2rem;
            align-items: center;
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

        .nav-buttons {
            display: flex;
            gap: 0.75rem;
            align-items: center;
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
            padding: 0;
            text-align: center;
            position: relative;
            overflow: hidden;
            min-height: 50vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .hero-content {
            padding: 3rem 4% 3rem;
            margin-top: 2rem;
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
            .hero-header {
                padding: 1rem 3%;
            }
            
            .logo img {
                height: 45px;
            }
            
            .logo-text {
                font-size: 0.85rem;
            }

            .hero {
                min-height: 45vh;
            }
            
            .hero-content {
                padding: 2rem 3% 2rem;
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
    <!-- Hero Section with Integrated Header -->
    <section class="hero">
        <!-- Header Navigation -->
        <div class="hero-header">
            <a href="{{ route('welcome') }}" class="logo">
                <img src="{{ asset('images/hsc-logo.png') }}" alt="HSC Logo">
                <span class="logo-text">RESOURCES HUB</span>
            </a>
            <nav>
                <a href="{{ route('welcome') }}">Home</a>
                <a href="#resources">Resources</a>
            </nav>
            <div class="nav-buttons">
                <a href="{{ route('login') }}" class="btn btn-primary">Log In</a>
            </div>
        </div>

        <!-- Hero Content -->
        <div class="hero-content">
            <h1>Knowledge Hub & <span>Compliance</span> Resources</h1>
            <p>Centralized operational guidelines, legal frameworks, and wellness toolkits optimized for Zimbabwean healthcare facilities.</p>

            <div class="search-container">
                <div class="search-input-wrapper">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" id="resourceSearch" placeholder="Search by keyword, form name, or regulation (e.g., NSSA, PEP)...">
                </div>
            </div>
        </div>
    </section>

    <!-- Resources Section -->
    <section class="resources-container" id="resources">
        <!-- Pillar A: National Regulatory & Compliance -->
        <div class="resource-section">
            <div class="section-header">
                <span class="section-icon">🏛️</span>
                <h2 class="section-title">National Regulatory & Compliance</h2>
            </div>
            <div class="resource-grid">
                <div class="resource-card">
                    <h3 class="resource-card-title">Occupational Safety and Health Bill, 2025</h3>
                    <p class="resource-card-desc">Plain-English summary and full legal gazette text for Zimbabwe's updated occupational health legislation.</p>
                    <div class="resource-meta">
                        <span class="badge">📄 PDF - 1.2MB</span>
                        <span class="badge type">Regulatory</span>
                    </div>
                    <button class="btn-download">
                        <i class="fas fa-download"></i> Download
                    </button>
                </div>

                <div class="resource-card">
                    <h3 class="resource-card-title">NSSA Form WC.IF.1</h3>
                    <p class="resource-card-desc">Mandatory Employer's Report of an Accident form for workers' compensation claims processing.</p>
                    <div class="resource-meta">
                        <span class="badge">📋 Fillable PDF - 450KB</span>
                        <span class="badge type">NSSA Official</span>
                    </div>
                    <button class="btn-download">
                        <i class="fas fa-download"></i> Download
                    </button>
                </div>

                <div class="resource-card">
                    <h3 class="resource-card-title">MoHCC IPC National Guidelines</h3>
                    <p class="resource-card-desc">Official Ministry Infection Prevention and Control baseline protocols for all healthcare facilities.</p>
                    <div class="resource-meta">
                        <span class="badge">📄 PDF - 3.1MB</span>
                        <span class="badge type">Ministry Policy</span>
                    </div>
                    <button class="btn-download">
                        <i class="fas fa-download"></i> Download
                    </button>
                </div>
            </div>
        </div>

        <!-- Pillar B: Clinical Protocols & Hazard Safety -->
        <div class="resource-section">
            <div class="section-header">
                <span class="section-icon">🩺</span>
                <h2 class="section-title">Clinical Protocols & Hazard Safety</h2>
            </div>
            <div class="resource-grid">
                <div class="resource-card">
                    <h3 class="resource-card-title">Needle-Stick & PEP Flowchart</h3>
                    <p class="resource-card-desc">Immediate response protocol and decision tree for blood-borne pathogen exposure management.</p>
                    <div class="resource-meta">
                        <span class="badge">🖼️ High-Vis Image - 800KB</span>
                        <span class="badge type">Emergency Protocol</span>
                    </div>
                    <button class="btn-download">
                        <i class="fas fa-download"></i> Download
                    </button>
                </div>

                <div class="resource-card">
                    <h3 class="resource-card-title">Hospital Chemical SDS Directory</h3>
                    <p class="resource-card-desc">Safe handling and spill procedures for sterilizers, chemotherapy agents, and hazardous materials.</p>
                    <div class="resource-meta">
                        <span class="badge">📑 Interactive Index</span>
                        <span class="badge type">Hazardous Material</span>
                    </div>
                    <button class="btn-download">
                        <i class="fas fa-download"></i> Download
                    </button>
                </div>

                <div class="resource-card">
                    <h3 class="resource-card-title">Patient Ergonomics & Safe Lifting</h3>
                    <p class="resource-card-desc">Visual guide to mechanical transfers and patient handling to eliminate lumbar and back injuries.</p>
                    <div class="resource-meta">
                        <span class="badge">🖼️ Infographic - 1.5MB</span>
                        <span class="badge type">Ergonomics</span>
                    </div>
                    <button class="btn-download">
                        <i class="fas fa-download"></i> Download
                    </button>
                </div>
            </div>
        </div>

        <!-- Pillar C: Mental Health & Wellness Toolkits -->
        <div class="resource-section">
            <div class="section-header">
                <span class="section-icon">🧠</span>
                <h2 class="section-title">Mental Health & Wellness Toolkits</h2>
            </div>
            <div class="resource-grid">
                <div class="resource-card">
                    <h3 class="resource-card-title">Maslach Burnout Self-Assessment</h3>
                    <p class="resource-card-desc">Anonymized psychological self-test for frontline medical staff to assess burnout risk levels.</p>
                    <div class="resource-meta">
                        <span class="badge">📋 Interactive Form</span>
                        <span class="badge type">Anonymized</span>
                    </div>
                    <button class="btn-download">
                        <i class="fas fa-download"></i> Download
                    </button>
                </div>

                <div class="resource-card">
                    <h3 class="resource-card-title">Gweru Project Wellness Action Plan</h3>
                    <p class="resource-card-desc">Personalized mental resilience framework adapted from successful provincial pilot programs.</p>
                    <div class="resource-meta">
                        <span class="badge">📄 Word Template - 200KB</span>
                        <span class="badge type">Staff Wellbeing</span>
                    </div>
                    <button class="btn-download">
                        <i class="fas fa-download"></i> Download
                    </button>
                </div>

                <div class="resource-card">
                    <h3 class="resource-card-title">24/7 Healthcare Worker Crisis Directory</h3>
                    <p class="resource-card-desc">Fast-access contact numbers for peer-support networks and local counselors nationwide.</p>
                    <div class="resource-meta">
                        <span class="badge">📱 Mobile Text View</span>
                        <span class="badge type">Urgent Support</span>
                    </div>
                    <button class="btn-download">
                        <i class="fas fa-download"></i> Download
                    </button>
                </div>
            </div>
        </div>

        <!-- Pillar D: Institutional Training & Templates -->
        <div class="resource-section">
            <div class="section-header">
                <span class="section-icon">🎓</span>
                <h2 class="section-title">Institutional Training & Templates</h2>
            </div>
            <div class="resource-grid">
                <div class="resource-card">
                    <h3 class="resource-card-title">5-Minute Ward 'Toolbox Talks'</h3>
                    <p class="resource-card-desc">Morning handover micro-presentation briefs covering PPE donning, heat stress, and daily hazards.</p>
                    <div class="resource-meta">
                        <span class="badge">📦 ZIP Bundle - 4.2MB</span>
                        <span class="badge type">Training</span>
                    </div>
                    <button class="btn-download">
                        <i class="fas fa-download"></i> Download
                    </button>
                </div>

                <div class="resource-card">
                    <h3 class="resource-card-title">Ward Hazard Risk Assessment Matrix</h3>
                    <p class="resource-card-desc">Standard pre-formatted risk matrix tool for departmental clinical heads to assess hazards.</p>
                    <div class="resource-meta">
                        <span class="badge">📊 Excel Sheet - 150KB</span>
                        <span class="badge type">Admin Tools</span>
                    </div>
                    <button class="btn-download">
                        <i class="fas fa-download"></i> Download
                    </button>
                </div>

                <div class="resource-card">
                    <h3 class="resource-card-title">OHS Committee SOP Template</h3>
                    <p class="resource-card-desc">Turnkey framework for launching and governing localized hospital safety committees.</p>
                    <div class="resource-meta">
                        <span class="badge">📄 Word Doc - 310KB</span>
                        <span class="badge type">Governance</span>
                    </div>
                    <button class="btn-download">
                        <i class="fas fa-download"></i> Download
                    </button>
                </div>
            </div>
        </div>

        <!-- Info Bar -->
        <div class="info-bar">
            <div class="info-bar-icon">
                <i class="fas fa-lightbulb"></i>
            </div>
            <div class="info-bar-content">
                <p class="info-bar-text"><strong>Data-Saver Mode Active:</strong> All resources are highly compressed for limited network connectivity. Use the 'Download Bundle' feature to save resources locally on your device for offline shifts.</p>
                <button class="info-bar-btn">
                    <i class="fas fa-download"></i> Download Core Offline Kit
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
        // Simple search functionality
        document.getElementById('resourceSearch').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const cards = document.querySelectorAll('.resource-card');
            
            cards.forEach(card => {
                const title = card.querySelector('.resource-card-title').textContent.toLowerCase();
                const desc = card.querySelector('.resource-card-desc').textContent.toLowerCase();
                
                if (title.includes(searchTerm) || desc.includes(searchTerm)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
