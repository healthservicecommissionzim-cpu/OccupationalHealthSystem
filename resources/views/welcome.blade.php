<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>HSC-OHS | Occupational Health & Safety Portal - Zimbabwe Healthcare Workers</title>
    <meta name="description" content="Report workplace incidents, exposures, and access occupational health services for Zimbabwe healthcare workers. Secure, fast, and confidential.">
    
    <!-- DNS Prefetch for faster external resource loading -->
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
    
    <!-- Preconnect for critical resources -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Optimized font loading -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet"></noscript>
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    
    <!-- Accessibility Stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/accessibility.css') }}">
    
    <!-- Preload critical assets -->
    <link rel="preload" href="{{ asset('images/hsc-logo.png') }}" as="image">
    
    <!-- Botpress Chatbot Script -->
    <script src="https://cdn.botpress.cloud/webchat/v5.0/inject.js"></script>
    <script src="https://files.bpcontent.cloud/2026/10/21/16/20261021161004-5GQMAHNX.js" defer></script>
    
    @vite(['resources/css/app.css'])
    <style>
        :root {
            --brand: #1D4533;
            --brand-dark: #0f2818;
            --brand-light: #f0fdf4;
            --dark: #000000;
            --slate: #475569;
            --light: #FBFBFA;
            --white: #ffffff;
            --yellow: #fef3c7;
            --yellow-dark: #92400e;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: #0f172a;
            overflow-x: hidden;
            background: linear-gradient(to bottom, #ffffff 0%, #f9fafb 100%);
            position: relative;
        }
        
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(29, 69, 51, 0.02) 1px, transparent 1px),
                radial-gradient(circle at 90% 80%, rgba(29, 69, 51, 0.02) 1px, transparent 1px);
            background-size: 50px 50px, 80px 80px;
            z-index: -1;
            pointer-events: none;
        }
        
        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 800"><defs><filter id="blur"><feGaussianBlur in="SourceGraphic" stdDeviation="2"/></filter></defs><path d="M0,200 Q300,100 600,150 T1200,200 L1200,0 L0,0 Z" fill="%23d1fae5" opacity="0.3" filter="url(%23blur)"/><path d="M0,400 Q300,300 600,350 T1200,400 L1200,800 L0,800 Z" fill="%2310b981" opacity="0.08"/><circle cx="100" cy="150" r="80" fill="%23059669" opacity="0.05"/><circle cx="1100" cy="650" r="120" fill="%23047857" opacity="0.06"/></svg>');
            background-repeat: repeat;
            background-size: 1200px 800px;
            background-position: 0 0;
            z-index: -1;
            pointer-events: none;
            animation: float 20s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        /* Emergency Banner */
        .emergency-banner {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            padding: 0.6rem 4%;
            text-align: center;
            font-weight: 600;
            color: var(--yellow-dark);
            border-bottom: 2px solid #fbbf24;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            font-size: 0.8rem;
            flex-wrap: wrap;
        }
        
        .emergency-banner i {
            color: #f59e0b;
        }
        
        /* Header should stay visible when menu is open on mobile */
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
            gap: 0.75rem;
            text-decoration: none;
            flex-shrink: 0;
            max-width: 70%;
        }
        
        .logo img {
            height: 55px;
            width: auto;
            max-height: 55px;
            object-fit: contain;
            flex-shrink: 0;
        }

        @media (min-width: 768px) {
            .logo img {
                height: 55px;
            }
            
            .logo {
                max-width: none;
            }
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
            text-decoration: none !important;
            color: var(--slate);
            font-weight: 500;
            font-size: 0.9rem;
            transition: color 0.3s;
        }
        
        nav a:hover {
            color: var(--brand);
            text-decoration: none !important;
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
            text-decoration: none !important;
            transition: all 0.3s;
            font-size: 0.85rem;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-outline {
            background: transparent;
            color: var(--brand);
            border: 1.5px solid var(--brand);
        }
        
        .btn-outline:hover {
            background: var(--brand);
            color: white;
            text-decoration: none !important;
        }
        
        .btn-primary {
            background: var(--brand);
            color: white;
            box-shadow: 0 4px 12px rgba(29, 69, 51, 0.2);
        }
        
        .btn-primary:hover {
            background: var(--brand-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(29, 69, 51, 0.3);
            text-decoration: none !important;
        }
        
        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%);
            padding: 4rem 4% 3rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        /* Mobile hero - show background image */
        @media (max-width: 500px) {
            .hero {
                background: linear-gradient(135deg, rgba(240, 253, 244, 0.85) 0%, rgba(255, 255, 255, 0.85) 100%), url('{{ asset("images/hero image.jpg") }}');
                background-size: cover;
                background-position: center;
                background-attachment: fixed;
                padding: 3rem 2% 2.5rem;
                min-height: 500px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 600"><defs><linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:%23d1fae5;stop-opacity:0.4"/><stop offset="100%" style="stop-color:%2310b981;stop-opacity:0.2"/></linearGradient></defs><path d="M-100,100 Q250,50 500,100 T1100,100 L1100,-50 L-100,-50 Z" fill="url(%23grad1)"/><circle cx="150" cy="200" r="60" fill="%23059669" opacity="0.08"/><circle cx="850" cy="300" r="100" fill="%2310b981" opacity="0.06"/><path d="M300,500 L320,550 L340,500 Z" fill="%23047857" opacity="0.1"/><path d="M700,520 L730,570 L760,520 Z" fill="%23047857" opacity="0.08"/></svg>'),
                radial-gradient(circle at 20% 30%, rgba(29, 69, 51, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 80% 70%, rgba(29, 69, 51, 0.03) 0%, transparent 50%);
            background-size: 1000px 600px, auto, auto;
            background-position: 0 0, 0 0, 0 0;
            background-repeat: repeat, no-repeat, no-repeat;
            z-index: 0;
            animation: slideInBg 15s ease-in-out infinite;
        }

        /* Hide SVG background on mobile */
        @media (max-width: 500px) {
            .hero::before {
                display: none;
            }
        }

        /* Decorative white circles */
        .hero::after {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255,255,255,0.8) 0%, rgba(255,255,255,0.4) 50%, transparent 100%);
            border-radius: 50%;
            z-index: 0;
            animation: float 8s ease-in-out infinite;
        }

        .hero .circle-1 {
            position: absolute;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.3) 60%, transparent 100%);
            border-radius: 50%;
            bottom: -30px;
            left: 5%;
            z-index: 0;
            animation: float 10s ease-in-out infinite reverse;
        }

        .hero .circle-2 {
            position: absolute;
            width: 150px;
            height: 150px;
            background: radial-gradient(circle, rgba(255,255,255,0.7) 0%, rgba(255,255,255,0.2) 60%, transparent 100%);
            border-radius: 50%;
            top: 10%;
            left: 15%;
            z-index: 0;
            animation: float 12s ease-in-out infinite;
        }

        .hero .circle-3 {
            position: absolute;
            width: 180px;
            height: 180px;
            background: radial-gradient(circle, rgba(255,255,255,0.8) 0%, rgba(255,255,255,0.25) 60%, transparent 100%);
            border-radius: 50%;
            top: 5%;
            right: 10%;
            z-index: 0;
            animation: float 14s ease-in-out infinite reverse;
        }

        .hero .circle-4 {
            position: absolute;
            width: 120px;
            height: 120px;
            background: radial-gradient(circle, rgba(255,255,255,0.75) 0%, rgba(255,255,255,0.15) 60%, transparent 100%);
            border-radius: 50%;
            bottom: 15%;
            right: 8%;
            z-index: 0;
            animation: float 9s ease-in-out infinite;
        }

        /* Hide decorative circles on mobile */
        @media (max-width: 500px) {
            .hero::after,
            .hero .circle-1,
            .hero .circle-2,
            .hero .circle-3,
            .hero .circle-4 {
                display: none;
            }
        }
        
        @keyframes slideInBg {
            0%, 100% { background-position: 0 0, 0 0, 0 0; }
            50% { background-position: -30px 0, 0 0, 0 0; }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) scale(1); }
            50% { transform: translateY(-20px) scale(1.05); }
        }
        
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--brand-light);
            color: var(--brand);
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .hero h1 {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-size: clamp(1.5rem, 5vw, 3.5rem);
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
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-size: 1.1rem;
            color: var(--slate);
            max-width: 700px;
            margin: 0 auto 2rem;
            line-height: 1.6;
        }
        
        /* Feature Cards - Responsive Grid */
        .features {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            padding: 3rem 4% 4rem;
            position: relative;
            background: linear-gradient(to bottom, #ffffff 0%, #f0fdf4 100%);
        }
        
        /* Tablet: 2 columns */
        @media (min-width: 501px) and (max-width: 768px) {
            .features {
                grid-template-columns: repeat(2, 1fr);
                gap: 1.5rem;
                padding: 2.5rem 3% 3rem;
            }
        }
        
        /* Desktop: 3 columns */
        @media (min-width: 769px) {
            .features {
                grid-template-columns: repeat(3, 1fr);
                gap: 1.5rem;
            }
        }
        
        /* Large Desktop: 3-4 columns maintained */
        @media (min-width: 1440px) {
            .features {
                grid-template-columns: repeat(3, 1fr);
                gap: 2rem;
            }
        }

        /* Mobile: Enhanced background styling */
        @media (max-width: 500px) {
            .features {
                background: #ffffff;
                padding: 2.5rem 2%;
                gap: 1.5rem;
                position: relative;
            }
            
            .features::before {
                display: block;
                background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 60"><defs><linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="0%"><stop offset="0%" style="stop-color:%231D4533;stop-opacity:0.05"/><stop offset="50%" style="stop-color:%231D4533;stop-opacity:0.08"/><stop offset="100%" style="stop-color:%231D4533;stop-opacity:0.03"/></linearGradient></defs><path d="M0,30 Q125,10 250,30 T500,30 L500,60 L0,60" fill="url(%23grad)"/></svg>');
                background-size: 500px 60px;
                background-repeat: repeat-x;
                top: -30px;
                height: 60px;
            }
        }
        
        .features::before {
            content: '';
            position: absolute;
            top: -50px;
            left: 0;
            right: 0;
            height: 100px;
            background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100"><defs><linearGradient id="wave" x1="0%" y1="0%" x2="100%" y2="0%"><stop offset="0%" style="stop-color:%23d1fae5;stop-opacity:0.6"/><stop offset="50%" style="stop-color:%2310b981;stop-opacity:0.4"/><stop offset="100%" style="stop-color:%23d1fae5;stop-opacity:0.6"/></linearGradient></defs><path d="M0,50 Q250,30 500,50 T1000,50 L1000,100 L0,100 Z" fill="url(%23wave)"/></svg>');
            background-repeat: repeat-x;
            background-size: 1000px 100px;
            z-index: 0;
            pointer-events: none;
        }
        
        .features > * {
            position: relative;
            z-index: 1;
        }
        
        .feature-card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 8px 32px rgba(29, 69, 51, 0.12), 0 2px 8px rgba(29, 69, 51, 0.08);
            transition: all 0.3s;
            text-decoration: none !important;
            color: inherit;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            position: relative;
            border: none;
        }
        
        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 16px 48px rgba(29, 69, 51, 0.16), 0 4px 12px rgba(29, 69, 51, 0.12);
            text-decoration: none !important;
        }
        
        .feature-card h3,
        .feature-card p {
            text-decoration: none !important;
        }
        
        .feature-number {
            position: absolute;
            top: 1rem;
            right: 1rem;
            font-size: 2rem;
            font-weight: 800;
            color: #e5e7eb;
            line-height: 1;
        }
        
        .feature-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            background: linear-gradient(135deg, var(--brand-light) 0%, #e8f5e9 100%);
            color: var(--brand);
        }
        
        .feature-card h3 {
            font-size: 1.3rem;
            font-weight: 700;
            margin: 0;
            color: var(--dark);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        
        .feature-card p {
            font-size: 0.9rem;
            line-height: 1.6;
            opacity: 0.8;
            margin: 0;
            color: var(--slate);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        
        /* Tooltip Styles */
        .feature-card {
            position: relative;
        }
        
        .feature-card::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 110%;
            left: 50%;
            transform: translateX(-50%) scale(0.9);
            background: var(--brand);
            color: white;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 500;
            white-space: normal;
            width: 220px;
            text-align: center;
            line-height: 1.4;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 10;
            pointer-events: none;
            box-shadow: 0 4px 12px rgba(29, 69, 51, 0.2);
        }
        
        .feature-card::before {
            content: '';
            position: absolute;
            bottom: 105%;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 6px solid transparent;
            border-right: 6px solid transparent;
            border-top: 6px solid var(--brand);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 10;
            pointer-events: none;
        }
        
        .feature-card:hover::after {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) scale(1);
        }
        
        .feature-card:hover::before {
            opacity: 1;
            visibility: visible;
        }
        
        @media (max-width: 768px) {
            .feature-card::after {
                width: 180px;
                font-size: 0.8rem;
                padding: 0.6rem 0.8rem;
            }
        }
        
        /* Ticker */
        .ticker {
            background: white;
            padding: 1.5rem 4%;
            overflow: hidden;
            border-top: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
            margin: 3rem 0;
        }
        
        .ticker-content {
            display: flex;
            gap: 3rem;
            animation: scroll 30s linear infinite;
        }
        
        .ticker-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            white-space: nowrap;
            font-size: 0.85rem;
            color: var(--slate);
            font-weight: 500;
        }
        
        @keyframes scroll {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        
        /* Services Section */
        .services {
            padding: 4rem 1% 3rem;
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
        }
        
        .services::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 300px;
            height: 300px;
            background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><circle cx="100" cy="100" r="80" fill="%2310b981" opacity="0.05"/><circle cx="100" cy="100" r="60" fill="%23059669" opacity="0.08"/><path d="M100,40 L130,110 L100,140 L70,110 Z" fill="%23047857" opacity="0.1"/></svg>');
            background-repeat: no-repeat;
            z-index: 0;
            pointer-events: none;
        }
        
        .services::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 250px;
            height: 250px;
            background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><path d="M20,180 Q60,150 100,180 T180,180" fill="none" stroke="%2310b981" stroke-width="3" opacity="0.1"/><circle cx="40" cy="160" r="15" fill="%23059669" opacity="0.08"/><circle cx="160" cy="170" r="20" fill="%23047857" opacity="0.06"/></svg>');
            background-repeat: no-repeat;
            z-index: 0;
            pointer-events: none;
        }
        
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }
        
        .service-card {
            text-align: center;
            padding: 2rem;
        }
        
        .service-icon {
            width: 60px;
            height: 60px;
            background: var(--brand-light);
            color: var(--brand);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin: 0 auto 1rem;
        }
        
        .service-card h3 {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            color: var(--dark);
        }
        
        .service-card p {
            font-size: 0.9rem;
            color: var(--slate);
            line-height: 1.6;
        }
        
        /* Stats Section */
        .stats {
            background: var(--light);
            padding: 3rem 4%;
            text-align: center;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            max-width: 900px;
            margin: 0 auto;
        }
        
        .stat-item h2 {
            font-size: 3rem;
            font-weight: 800;
            color: var(--brand);
            margin-bottom: 0.5rem;
        }
        
        .stat-item p {
            font-size: 0.85rem;
            color: var(--slate);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 600;
        }
        
        /* CTA Section */
        .cta {
            background: linear-gradient(135deg, var(--brand) 0%, #2d5a47 100%);
            color: white;
            padding: 4rem 4%;
            text-align: center;
        }
        
        .cta h2 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
        }
        
        .cta .btn {
            background: white;
            color: var(--brand);
            font-size: 1rem;
            padding: 0.9rem 2rem;
        }
        
        .cta .btn:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
        }
        
        /* Testimonials */

        /* Footer */
        footer {
            background: var(--brand);
            color: rgba(255, 255, 255, 0.9);
            padding: 2rem 4%;
            text-align: center;
        }
        
        footer h3 {
            color: white;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        footer p {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.6;
            margin: 0.5rem 0;
        }
        
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto 1.5rem;
        }
        
        .footer-section h3 {
            color: white;
            font-size: 0.9rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .footer-section ul {
            list-style: none;
        }
        
        .footer-section ul li {
            margin-bottom: 0.5rem;
        }
        
        .footer-section a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-size: 0.85rem;
            transition: color 0.3s;
        }
        
        .footer-section a:hover {
            color: white;
        }
        
        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            padding-top: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .footer-bottom p {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.8);
        }
        
        .social-links {
            display: flex;
            gap: 1rem;
        }
        
        .social-links a {
            width: 35px;
            height: 35px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .social-links a:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
        }
        
        /* Mobile Menu */
        .menu-toggle {
            display: none;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 0;
            border-radius: 8px;
            transition: background-color 0.3s;
            z-index: 1002;
            position: relative;
        }

        @media (max-width: 768px) {
            nav {
                display: none;
            }

            .nav-buttons {
                display: none;
            }

            .menu-toggle {
                display: flex;
            }
        }
        
        .menu-toggle:hover {
            background-color: rgba(29, 69, 51, 0.1);
        }
        
        .menu-toggle:focus-visible {
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
        
        /* When menu is open, transform to X */
        .menu-toggle.open .hamburger-line:nth-child(1) {
            transform: rotate(45deg) translate(6px, 6px);
        }
        
        .menu-toggle.open .hamburger-line:nth-child(2) {
            opacity: 0;
            transform: translateX(-20px);
        }
        
        .menu-toggle.open .hamburger-line:nth-child(3) {
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
        
        .mobile-menu-content {
            padding: 0;
            display: flex;
            flex-direction: column;
            flex: 1;
            justify-content: center;
        }
        
        .close-menu {
            display: none;
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
            width: 100%;
            justify-content: center;
            padding: 1rem 2rem;
            font-size: 1.1rem;
        }
        
        /* Floating Chat Widget */
        .floating-chat-btn {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--brand) 0%, var(--brand-dark) 100%);
            border: none;
            border-radius: 50%;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            box-shadow: 0 4px 20px rgba(29, 69, 51, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 998;
            transition: all 0.5s ease;
            padding-top: 0.5rem;
            animation: breathe 2.5s ease-in-out infinite;
        }
        
        @keyframes breathe {
            0%, 100% {
                box-shadow: 0 4px 20px rgba(29, 69, 51, 0.3), 0 0 0 0 rgba(29, 69, 51, 0.3);
                transform: scale(1);
            }
            50% {
                box-shadow: 0 4px 20px rgba(29, 69, 51, 0.3), 0 0 0 12px rgba(29, 69, 51, 0);
                transform: scale(1.03);
            }
        }
        
        .floating-chat-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 30px rgba(29, 69, 51, 0.4);
            animation: none;
        }
        
        .floating-chat-btn:active {
            transform: scale(0.95);
        }
        
        /* Support Modal */
        .support-modal {
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
        }
        
        .support-modal.active {
            display: flex;
        }
        
        .support-modal-content {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            max-width: 450px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            position: relative;
            animation: slideUp 0.3s ease;
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
        
        .close-support-modal {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--slate);
            cursor: pointer;
            padding: 0;
            transition: color 0.3s;
        }
        
        .close-support-modal:hover {
            color: var(--brand);
        }
        
        .support-modal-header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .support-modal-header i {
            font-size: 2.5rem;
            color: var(--brand);
            margin-bottom: 0.75rem;
        }
        
        .support-modal-header h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark);
            margin: 0.5rem 0;
        }
        
        .support-modal-header p {
            font-size: 0.9rem;
            color: var(--slate);
            margin: 0.5rem 0 0;
        }
        
        .support-form {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .form-group label {
            font-weight: 600;
            color: var(--dark);
            font-size: 0.9rem;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 0.75rem 1rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.9rem;
            color: #000000 !important;
            background-color: #ffffff !important;
            transition: all 0.3s;
        }

        .form-group input::placeholder,
        .form-group textarea::placeholder {
            color: #9ca3af !important;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--brand);
            box-shadow: 0 0 0 3px rgba(29, 69, 51, 0.1);
            background-color: #ffffff !important;
            color: #000000 !important;
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 100px;
            color: #000000 !important;
            background-color: #ffffff !important;
        }

        .form-group select {
            color: #000000 !important;
            background-color: #ffffff !important;
        }

        .form-group select option {
            color: #000000 !important;
            background-color: #ffffff !important;
        }
        
        /* Response Modal */
        .response-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        
        .response-modal.active {
            display: flex;
        }
        
        .response-modal-content {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            max-width: 400px;
            width: 90%;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            animation: slideUp 0.3s ease;
        }
        
        .response-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .response-icon.success::before {
            content: '✓';
            color: #10b981;
        }
        
        .response-icon.error::before {
            content: '✕';
            color: #ef4444;
        }
        
        .response-modal-content h2 {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.75rem;
        }
        
        .response-modal-content p {
            font-size: 0.9rem;
            color: var(--slate);
            line-height: 1.6;
        }

        /* Chat Modal Backdrop */
        .chat-modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #f0fdf4 0%, #e0f2fe 50%, #fef3c7 100%);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 2040;
            pointer-events: none;
            overflow: hidden;
        }

        .chat-modal-backdrop::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(29, 69, 51, 0.08) 0%, transparent 70%);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .chat-modal-backdrop::after {
            content: '';
            position: absolute;
            bottom: -20%;
            left: -5%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.06) 0%, transparent 70%);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite reverse;
        }

        .chat-modal-backdrop.active {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) translateX(0px); }
            25% { transform: translateY(-20px) translateX(10px); }
            50% { transform: translateY(-40px) translateX(0px); }
            75% { transform: translateY(-20px) translateX(-10px); }
        }

        /* Interactive Chat Modal */
        .chat-modal {
            position: fixed;
            bottom: 0;
            right: 0;
            width: 100%;
            height: 0;
            max-width: 420px;
            max-height: 600px;
            background: white;
            border-radius: 16px 16px 0 0;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.15);
            z-index: 2050;
            transition: all 0.3s ease;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        .chat-modal.active {
            height: 600px;
            border-radius: 16px;
            bottom: auto;
            top: 50%;
            left: 50%;
            right: auto;
            transform: translate(-50%, -50%);
            margin: 0;
            width: calc(100% - 2rem);
            max-width: 420px;
            z-index: 2051;
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        @media (max-width: 480px) {
            .chat-modal.active {
                width: calc(100% - 1rem);
                height: 500px;
                max-height: 80vh;
            }
        }

        .chat-modal-content {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .chat-header {
            background: linear-gradient(135deg, var(--brand) 0%, #2d5a47 100%);
            color: white;
            padding: 1.25rem;
            border-radius: 16px 16px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chat-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .chat-title i {
            font-size: 1.5rem;
        }

        .chat-title h3 {
            font-size: 1rem;
            font-weight: 700;
            margin: 0;
        }

        .chat-title p {
            font-size: 0.75rem;
            margin: 0;
            opacity: 0.9;
        }

        .chat-close {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            font-size: 1.25rem;
            cursor: pointer;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.3s;
        }

        .chat-close:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            background: #f9fafb;
        }

        .chat-message {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .chat-message.user-message {
            align-items: flex-end;
        }

        .chat-message.bot-message {
            align-items: flex-start;
        }

        .message-content {
            background: white;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            max-width: 85%;
            word-wrap: break-word;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .user-message .message-content {
            background: var(--brand);
            color: white;
            border-radius: 12px 4px 12px 12px;
        }

        .bot-message .message-content {
            background: #e5e7eb;
            color: var(--dark);
            border-radius: 4px 12px 12px 12px;
        }

        .message-content p {
            margin: 0;
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .message-time {
            font-size: 0.7rem;
            color: var(--slate-400);
            padding: 0 1rem;
        }

        .chat-input-area {
            padding: 1rem;
            border-top: 1px solid #e2e8f0;
            background: white;
        }

        .chat-form {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .chat-input {
            flex: 1;
            padding: 0.65rem 1rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.9rem;
            transition: border-color 0.2s;
        }

        .chat-input:focus {
            outline: none;
            border-color: var(--brand);
            box-shadow: 0 0 0 3px rgba(29, 69, 51, 0.1);
        }

        .chat-send-btn {
            background: var(--brand);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.65rem 1rem;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .chat-send-btn:hover {
            background: var(--brand-dark);
            transform: translateY(-2px);
        }

        .chat-send-btn:active {
            transform: translateY(0);
        }

        .chat-hint {
            font-size: 0.7rem;
            color: var(--slate-500);
            margin: 0;
            text-align: center;
        }

        /* Loading State Styles */
        .chat-send-btn.loading {
            opacity: 0.7;
            pointer-events: none;
        }

        .chat-send-btn.loading i {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Result Modal Styles */
        .result-modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #f0fdf4 0%, #e0f2fe 50%, #fef3c7 100%);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 2040;
            pointer-events: none;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .result-modal-backdrop.active {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        .result-modal-content {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            max-width: 420px;
            width: 90%;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            animation: slideUp 0.3s ease;
            position: relative;
            z-index: 2051;
        }

        .result-icon {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            animation: fadeIn 0.5s ease;
        }

        .result-icon.success {
            color: #10b981;
        }

        .result-icon.error {
            color: #ef4444;
        }

        .result-modal-content h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.75rem;
        }

        .result-modal-content p {
            font-size: 0.95rem;
            color: var(--slate);
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .result-button {
            background: var(--brand);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 0.9rem;
        }

        .result-button:hover {
            background: var(--brand-dark);
            transform: translateY(-2px);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.8); }
            to { opacity: 1; transform: scale(1); }
        }

        .chat-loading {
            display: inline-block;
            width: 8px;
            height: 8px;
            background: var(--brand);
            border-radius: 50%;
            animation: blink 1.4s infinite;
        }

        .chat-loading:nth-child(2) {
            animation-delay: 0.2s;
        }

        .chat-loading:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes blink {
            0%, 60%, 100% { opacity: 0.3; }
            30% { opacity: 1; }
        }
        
        /* Mobile Responsive */
        @media (max-width: 768px) {
            .floating-chat-btn {
                width: 50px;
                height: 50px;
                bottom: 1.5rem;
                right: 1.5rem;
                font-size: 1.2rem;
            }
            
            .support-modal-content {
                max-width: 95%;
                padding: 1.5rem;
            }
        }
        /* Hero Section - Responsive Breakpoints */
        
        /* Mobile: 320px-500px */
        @media (max-width: 500px) {
            .hero {
                padding: 1.5rem 2%;
            }
            
            .hero h1 {
                font-size: 1.5rem;
                color: var(--brand);
                text-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
                position: relative;
                z-index: 2;
            }
            
            .hero h1 span {
                color: var(--brand);
                text-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            }
            
            .hero p {
                font-size: 0.95rem;
                color: var(--dark);
                text-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
                position: relative;
                z-index: 2;
            }
            
            .hero-badge {
                font-size: 0.7rem;
                padding: 0.3rem 0.8rem;
                position: relative;
                z-index: 2;
            }
        }
        
        /* Tablet: 501px-768px */
        @media (min-width: 501px) and (max-width: 768px) {
            .hero {
                padding: 2.5rem 3%;
            }
            
            .hero h1 {
                font-size: 2rem;
            }
            
            .hero p {
                font-size: 1rem;
            }
        }
        
        /* Desktop: 769px+ */
        @media (min-width: 769px) {
            .hero {
                padding: 4rem 4% 3rem;
            }
            
            .hero h1 {
                font-size: clamp(2.5rem, 5vw, 3.5rem);
            }
            
            .hero p {
                font-size: 1.1rem;
            }
        }
        
        @media (max-width: 768px) {
            .emergency-banner {
                font-size: 0.7rem;
                padding: 0.5rem 2%;
            }
            
            header {
                padding: 1rem 2%;
            }
            
            nav {
                display: none !important;
            }
            
            .nav-buttons {
                display: none !important;
            }
            
            .menu-toggle {
                display: flex;
            }
            
            .features {
                grid-template-columns: 1fr;
                padding: 0 2%;
                gap: 2.5rem;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .cta h2 {
                font-size: 1.8rem;
            }
            
            .footer-content {
                grid-template-columns: 1fr;
            }
            
            .footer-bottom {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>

    <!-- Language Provider Script -->
    @include('components.language-provider')
</head>
<body>
    <!-- Header -->
    <header>
        <a href="{{ route('welcome') }}" class="logo">
            <img src="{{ asset('images/hsc-logo.png') }}" alt="HSC Logo" fetchpriority="high">
            <span class="logo-text">HEALTH SERVICE COMMISSION</span>
        </a>
        
        <nav>
            <a href="{{ route('resources') }}">Resources</a>
            <a href="{{ route('safety_protocols') }}">Safety Protocols</a>
            <a href="javascript:void(0);" onclick="openSupportModal()">Help Desk</a>
        </nav>
        
        <div class="nav-buttons">
            <a href="{{ route('login') }}" class="btn btn-primary">Log In</a>
            <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
        </div>
        
        <button class="menu-toggle" onclick="toggleMobileMenu()">
            <div class="hamburger-icon">
                <div class="hamburger-line"></div>
                <div class="hamburger-line"></div>
                <div class="hamburger-line"></div>
            </div>
        </button>
    </header>

    <!-- Mobile Menu Backdrop -->
    <div class="mobile-menu-backdrop" onclick="toggleMobileMenu()"></div>
    
    <!-- Mobile Menu -->
    <div class="mobile-menu">
        <div class="mobile-menu-header">
            <span style="font-weight: 700; color: var(--brand); font-size: 1.2rem;">Menu</span>
            <button class="mobile-menu-close" onclick="toggleMobileMenu()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <nav class="mobile-menu-nav">
            <a href="{{ route('welcome') }}">Home</a>
            <a href="{{ route('resources') }}">Resources</a>
            <a href="{{ route('safety_protocols') }}">Safety Protocols</a>
            <a href="javascript:void(0);" onclick="openSupportModal(); toggleMobileMenu();">Help Desk</a>
        </nav>
        <div class="mobile-menu-buttons">
            <a href="{{ route('login') }}" class="btn btn-primary">Log In</a>
            <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="hero">
        <!-- Decorative circles -->
        <div class="circle-1"></div>
        <div class="circle-2"></div>
        <div class="circle-3"></div>
        <div class="circle-4"></div>

        <div class="hero-badge">
            <i class="fas fa-shield-alt"></i>
            SECURE & CONFIDENTIAL PORTAL
        </div>
        
        <h1>Occupational <span>Health</span><br>Reimagined.</h1>
        
        <p>Empowering Zimbabwe's healthcare workforce with a modern, secure, and comprehensive occupational health, safety, and wellness management system.</p>
    </section>

    <!-- Feature Cards -->
    <section class="features">
        <a href="{{ route('incidents.create') }}" class="feature-card" data-tooltip="Click to start reporting a new workplace incident or injury">
            <span class="feature-number">01</span>
            <div class="feature-icon">
                <i class="fas fa-file-medical-alt"></i>
            </div>
            <h3>Report Incident</h3>
            <p>Quickly and securely report workplace incidents, exposures, or injuries through our streamlined digital form.</p>
        </a>
        
        <a href="{{ route('incidents.track') }}" class="feature-card" data-tooltip="Click to find and track your reported incidents, view status, and upload documents">
            <span class="feature-number">02</span>
            <div class="feature-icon">
                <i class="fas fa-upload"></i>
            </div>
            <h3>Track & Upload</h3>
            <p>Monitor the progress of your submitted incidents and upload supporting documentation when required.</p>
        </a>
        
        <a href="{{ route('first_aid.token_form') }}" class="feature-card" data-tooltip="Click to log minor first aid incidents quickly without full incident reporting">
            <span class="feature-number">03</span>
            <div class="feature-icon">
                <i class="fas fa-first-aid"></i>
            </div>
            <h3>First Aid Log</h3>
            <p>Record and log minor first aid events without creating full incident reports for minor injuries.</p>
        </a>
    </section>

    <!-- Ticker -->
    <div class="ticker">
        <div class="ticker-content">
            <span class="ticker-item">To provide a competent and motivated health workforce and systems for a healthy Zimbabwe</span>
            <span class="ticker-item">A responsive Health Service for a highly productive Zimbabwe</span>
            <span class="ticker-item">Working together for people's health</span>
            <span class="ticker-item">To provide a competent and motivated health workforce and systems for a healthy Zimbabwe</span>
        </div>
    </div>

    <!-- Support Modal Backdrop -->
    <div class="chat-modal-backdrop" id="supportModalBackdrop"></div>

    <!-- Support Ticket Form Modal -->
    <div class="chat-modal" id="supportModal">
        <div class="chat-modal-content">
            <div class="chat-header">
                <div class="chat-title">
                    <i class="fas fa-headset"></i>
                    <div>
                        <h3>IT Help Desk</h3>
                        <p>Submit your support request</p>
                    </div>
                </div>
                <button class="chat-close" id="closeSupportBtn">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="chat-messages" style="background: white; padding: 1.5rem; overflow-y: auto;">
                <form id="supportForm" class="support-form">
                    <div class="form-group">
                        <label for="support_name">Full Name *</label>
                        <input type="text" id="support_name" name="name" placeholder="Your name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="support_email">Email Address *</label>
                        <input type="email" id="support_email" name="email" placeholder="your.email@example.com" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="support_phone">Phone Number (Optional)</label>
                        <input type="tel" id="support_phone" name="phone" placeholder="+263 (optional)">
                    </div>
                    
                    <div class="form-group">
                        <label for="support_category">Category *</label>
                        <select id="support_category" name="category" required>
                            <option value="">Select a category</option>
                            <option value="technical">Technical Problem</option>
                            <option value="account">Account Issue</option>
                            <option value="incident">Report Incident</option>
                            <option value="general">General Inquiry</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="support_message">Issue Description *</label>
                        <textarea id="support_message" name="description" placeholder="Please describe your issue in detail..." required minlength="10"></textarea>
                    </div>
                </form>
            </div>
            
            <div class="chat-input-area">
                <button type="submit" form="supportForm" class="chat-send-btn" style="width: 100%; justify-content: center; font-size: 1rem;">
                    <i class="fas fa-paper-plane" style="margin-right: 0.5rem;"></i> Submit Request
                </button>
                <p class="chat-hint">Your request will be forwarded to the IT Department</p>
            </div>
        </div>
    </div>

    <!-- Chat Modal Backdrop -->
    <div class="chat-modal-backdrop" id="chatModalBackdrop"></div>

    <!-- Interactive Chatbot Modal -->
    <div class="chat-modal" id="chatModal">
        <div class="chat-modal-content">
            <div class="chat-header">
                <div class="chat-title">
                    <i class="fas fa-robot"></i>
                    <div>
                        <h3>HSC-OHS Assistant</h3>
                        <p>Online</p>
                    </div>
                </div>
                <button class="chat-close" id="closeChatBtn">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="chat-messages" id="chatMessages">
                <div class="chat-message bot-message">
                    <div class="message-content">
                        <p>👋 Hello! I'm your HSC-OHS assistant. I'm here to help you with questions about incident reporting, account access, system features, and more. What can I help you with today?</p>
                    </div>
                    <span class="message-time">now</span>
                </div>
            </div>
            
            <div class="chat-input-area">
                <form id="chatForm" class="chat-form">
                    <input 
                        type="text" 
                        id="chatInput" 
                        class="chat-input" 
                        placeholder="Type your message..." 
                        autocomplete="off"
                        required
                    >
                    <button type="submit" class="chat-send-btn" title="Send message">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
                <p class="chat-hint">💡 Try asking: "How do I report an incident?" or "I forgot my password"</p>
            </div>
        </div>
    </div>

    <!-- Result Modal Backdrop -->
    <div class="result-modal-backdrop" id="resultModalBackdrop"></div>

    <!-- Result Modal -->
    <div id="resultModal" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 2051; display: none;">
        <div class="result-modal-content">
            <div class="result-icon" id="resultIcon"></div>
            <h2 id="resultTitle"></h2>
            <p id="resultMessage"></p>
            <button class="result-button" id="resultButton">Close</button>
        </div>
    </div>

<!-- Footer -->
    <footer>
        <h3>Health Service Commission</h3>
        <p>Working Together for People's Health</p>
        <p style="font-size: 0.75rem; margin-top: 0.75rem;">
            © 2026 Health Service Commission. All rights reserved.
        </p>
    </footer>

    <!-- Accessibility Features -->
    @include('components.accessibility-toolbar')

    <!-- Mobile Menu Toggle Script -->
    <script>
        // Mobile menu toggle
        function toggleMobileMenu() {
            const menu = document.querySelector('.mobile-menu');
            const backdrop = document.querySelector('.mobile-menu-backdrop');
            const toggle = document.querySelector('.menu-toggle');
            
            menu.classList.toggle('active');
            backdrop.classList.toggle('active');
            toggle.classList.toggle('open');
            
            document.body.style.overflow = menu.classList.contains('active') ? 'hidden' : '';
        }

        // Support Modal Functions
        function openSupportModal() {
            const supportModal = document.getElementById('supportModal');
            const supportBackdrop = document.getElementById('supportModalBackdrop');
            supportModal.classList.add('active');
            supportBackdrop.classList.add('active');
        }

        function closeSupportModal() {
            const supportModal = document.getElementById('supportModal');
            const supportBackdrop = document.getElementById('supportModalBackdrop');
            supportModal.classList.remove('active');
            supportBackdrop.classList.remove('active');
        }

        // Ensure modal is closed on page load unless explicitly shown
        document.addEventListener('DOMContentLoaded', () => {
            // Check if user just logged in (set by LoginController)
            const justLoggedIn = {{ session('just_logged_in') ? 'true' : 'false' }};
            
            if (justLoggedIn) {
                // Only show modal on fresh login
                openSupportModal();
                
                // Clear the session flag via AJAX so it doesn't show again
                fetch('{{ route("welcome") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ clear_login_flag: true })
                }).catch(err => console.error('Error clearing login flag:', err));
            } else {
                // Otherwise keep modal closed
                closeSupportModal();
            }
        });

        // Support Modal Event Listeners
        document.getElementById('closeSupportBtn')?.addEventListener('click', closeSupportModal);
        document.getElementById('supportModalBackdrop')?.addEventListener('click', closeSupportModal);
        
        // Handle support form submission
        const setupFormSubmission = () => {
            const form = document.getElementById('supportForm');
            if (!form) return;
            
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                
                const submitBtn = document.querySelector('button[form="supportForm"]');
                const formData = new FormData(form);
                const data = {
                    name: formData.get('name'),
                    email: formData.get('email'),
                    phone: formData.get('phone'),
                    category: formData.get('category'),
                    description: formData.get('description'),
                };

                // Show loading state
                submitBtn.classList.add('loading');
                submitBtn.disabled = true;

                try {
                    const response = await fetch('{{ route("support.public_request") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify(data),
                    });

                    // Hide loading state
                    submitBtn.classList.remove('loading');
                    submitBtn.disabled = false;

                    if (response.ok) {
                        const result = await response.json();
                        showResultModal('success', 'Request Submitted!', 'Your support request has been submitted successfully.\n\nReference: ' + (result.ticket_number || 'Pending') + '\n\nOur IT team will respond within 24-48 hours.');
                        form.reset();
                        
                        // Close support modal after 1.5 seconds
                        setTimeout(() => {
                            closeSupportModal();
                        }, 1500);
                    } else {
                        const result = await response.json();
                        showResultModal('error', 'Submission Failed', result.message || 'An error occurred while submitting your request. Please try again.');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    submitBtn.classList.remove('loading');
                    submitBtn.disabled = false;
                    showResultModal('error', 'Error', 'An error occurred while submitting your request. Please check your connection and try again.');
                }
            });
        };

        // Wait for DOM to be ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', setupFormSubmission);
        } else {
            setupFormSubmission();
        }

        // Show result modal
        function showResultModal(type, title, message) {
            const backdrop = document.getElementById('resultModalBackdrop');
            const modal = document.getElementById('resultModal');
            const icon = document.getElementById('resultIcon');
            const titleEl = document.getElementById('resultTitle');
            const msgEl = document.getElementById('resultMessage');
            const btn = document.getElementById('resultButton');

            icon.className = 'result-icon ' + type;
            icon.innerHTML = type === 'success' ? '✓' : '✕';
            titleEl.textContent = title;
            msgEl.textContent = message;
            
            modal.style.display = 'block';
            backdrop.classList.add('active');

            btn.onclick = () => {
                modal.style.display = 'none';
                backdrop.classList.remove('active');
            };

            // Close on backdrop click
            backdrop.onclick = () => {
                modal.style.display = 'none';
                backdrop.classList.remove('active');
            };

            // Close on Escape key
            const escapeHandler = (e) => {
                if (e.key === 'Escape') {
                    modal.style.display = 'none';
                    backdrop.classList.remove('active');
                    document.removeEventListener('keydown', escapeHandler);
                }
            };
            document.addEventListener('keydown', escapeHandler);
        }

        // Escape key closes modals
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeSupportModal();
            }
        });
    </script>
    
    <!-- Botpress Chatbot Widget -->
    <script src="https://cdn.botpress.cloud/webchat/v5.0/inject.js"></script>
    <script src="https://files.bpcontent.cloud/2026/10/21/16/20261021161004-5GQMAHNX.js" defer></script>
    
    <!-- Customize Botpress Widget Colors -->
    <style>
        /* Customize Botpress chat button to match brand colors */
        #bp-web-widget-container button[aria-label*="button" i],
        #bp-web-widget-container .bpHeader,
        #bp-web-widget-container .bpButton {
            background-color: #1D4533 !important;
        }
        
        #bp-web-widget-container button[aria-label*="button" i]:hover {
            background-color: #0f2818 !important;
        }
        
        /* Custom styling for the widget */
        #bp-web-widget-container {
            --primary-color: #1D4533;
            --primary-hover: #0f2818;
        }
        
        /* Style the floating chat button */
        div[id^="bp-"] > button {
            background-color: #1D4533 !important;
            box-shadow: 0 4px 12px rgba(29, 69, 51, 0.3) !important;
        }
        
        div[id^="bp-"] > button:hover {
            background-color: #0f2818 !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 20px rgba(29, 69, 51, 0.4) !important;
        }
        
        /* Hide "⚡ by Botpress" branding */
        #bp-web-widget-container a[href*="botpress"],
        #bp-web-widget-container [class*="powered"],
        #bp-web-widget-container [class*="branding"],
        div[id^="bp-"] a[href*="botpress"],
        div[id^="bp-"] [class*="powered"],
        div[id^="bp-"] [class*="branding"] {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
            height: 0 !important;
            overflow: hidden !important;
        }
        
        /* Also hide any footer with Botpress link */
        #bp-web-widget-container footer,
        div[id^="bp-"] footer {
            display: none !important;
        }
    </style>

    <!-- Accessibility Toolbar Script -->
    <script src="{{ asset('js/accessibility.js') }}"></script>
</body>
</html>