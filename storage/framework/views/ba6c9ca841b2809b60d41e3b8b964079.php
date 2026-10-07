<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>HSC-OHS | Occupational Health & Safety Portal - Zimbabwe Healthcare Workers</title>
    <meta name="description" content="Report workplace incidents, exposures, and access occupational health services for Zimbabwe healthcare workers. Secure, fast, and confidential.">
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
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
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
        
        /* Header */
        header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            padding: 1rem 4%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 1000;
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
        
        .btn-outline {
            background: transparent;
            color: var(--brand);
            border: 1.5px solid var(--brand);
        }
        
        .btn-outline:hover {
            background: var(--brand);
            color: white;
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
        }
        
        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%);
            padding: 4rem 4% 3rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
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
        
        @keyframes slideInBg {
            0%, 100% { background-position: 0 0, 0 0, 0 0; }
            50% { background-position: -30px 0, 0 0, 0 0; }
        }
        
        .hero > * {
            position: relative;
            z-index: 1;
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
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
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
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
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
            text-decoration: none;
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
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        
        .feature-card p {
            font-size: 0.9rem;
            line-height: 1.6;
            opacity: 0.8;
            margin: 0;
            color: var(--slate);
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
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
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--brand);
            cursor: pointer;
            padding: 0.5rem;
            z-index: 1002;
        }
        
        .mobile-menu {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
        
        .mobile-menu.active {
            display: block;
            z-index: 999;
        }
        
        .mobile-menu-content {
            position: fixed;
            top: 0;
            right: 0;
            width: 280px;
            height: 100%;
            background: white;
            z-index: 1000;
            padding: 2rem 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            box-shadow: -4px 0 12px rgba(0, 0, 0, 0.15);
            animation: slideInRight 0.3s ease;
            overflow-y: auto;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
            }
            to {
                transform: translateX(0);
            }
        }
        
        .close-menu {
            align-self: flex-end;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--brand);
            cursor: pointer;
            padding: 0;
            margin-bottom: 1rem;
        }
        
        .mobile-menu-nav {
            display: flex !important;
            flex-direction: column;
            gap: 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 1.5rem;
            margin-bottom: 1rem;
            width: 100%;
        }
        
        .mobile-menu-nav a {
            text-decoration: none;
            color: var(--dark);
            font-weight: 600;
            font-size: 1.1rem;
            transition: color 0.3s;
            padding: 0.75rem 0;
            display: block !important;
            visibility: visible !important;
        }
        
        .mobile-menu-nav a:hover {
            color: var(--brand);
        }
        
        .mobile-menu-buttons {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }
        
        .mobile-menu-buttons .btn {
            width: 100%;
            justify-content: center;
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
            color: var(--dark);
            transition: all 0.3s;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--brand);
            box-shadow: 0 0 0 3px rgba(29, 69, 51, 0.1);
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 100px;
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
            }
            
            .hero p {
                font-size: 0.95rem;
            }
            
            .hero-badge {
                font-size: 0.7rem;
                padding: 0.3rem 0.8rem;
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
                display: block;
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
</head>
<body>
    <!-- Header -->
    <header>
        <a href="<?php echo e(route('welcome')); ?>" class="logo">
            <img src="<?php echo e(asset('images/hsc-logo.png')); ?>" alt="HSC Logo">
            <span class="logo-text">HEALTH SERVICE COMMISSION</span>
        </a>
        
        <nav>
            <a href="<?php echo e(route('resources')); ?>">Resources</a>
            <a href="<?php echo e(route('safety_protocols')); ?>">Safety Protocols</a>
            <a href="javascript:void(0);" onclick="document.getElementById('floatingChatBtn').click()">Help Desk</a>
        </nav>
        
        <div class="nav-buttons">
            <a href="<?php echo e(route('login')); ?>" class="btn btn-primary">Log In</a>
            <a href="<?php echo e(route('register')); ?>" class="btn btn-primary">Register</a>
        </div>
        
        <button class="menu-toggle">
            <i class="fas fa-bars"></i>
        </button>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-badge">
            <i class="fas fa-shield-alt"></i>
            SECURE & CONFIDENTIAL PORTAL
        </div>
        
        <h1>Occupational <span>Health</span><br>Reimagined.</h1>
        
        <p>Empowering Zimbabwe's healthcare workforce with a modern, secure, and comprehensive occupational health, safety, and wellness management system.</p>
    </section>

    <!-- Feature Cards -->
    <section class="features">
        <a href="<?php echo e(route('incidents.create')); ?>" class="feature-card" data-tooltip="Click to start reporting a new workplace incident or injury">
            <span class="feature-number">01</span>
            <div class="feature-icon">
                <i class="fas fa-file-medical-alt"></i>
            </div>
            <h3>Report Incident</h3>
            <p>Quickly and securely report workplace incidents, exposures, or injuries through our streamlined digital form.</p>
        </a>
        
        <a href="<?php echo e(route('incidents.track')); ?>" class="feature-card" data-tooltip="Click to find and track your reported incidents, view status, and upload documents">
            <span class="feature-number">02</span>
            <div class="feature-icon">
                <i class="fas fa-upload"></i>
            </div>
            <h3>Track & Upload</h3>
            <p>Monitor the progress of your submitted incidents and upload supporting documentation when required.</p>
        </a>
        
        <a href="<?php echo e(route('first_aid.token_form')); ?>" class="feature-card" data-tooltip="Click to log minor first aid incidents quickly without full incident reporting">
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

    <!-- Mobile Menu -->
    <div class="mobile-menu">
        <div class="mobile-menu-content">
            <button class="close-menu">
                <i class="fas fa-times"></i>
            </button>
            
            <nav class="mobile-menu-nav">
                <a href="<?php echo e(route('resources')); ?>">Resources</a>
                <a href="<?php echo e(route('safety_protocols')); ?>">Safety Protocols</a>
                <a href="javascript:void(0);" onclick="document.getElementById('floatingChatBtn').click()">Help Desk</a>
            </nav>
            
            <div class="mobile-menu-buttons">
                <a href="<?php echo e(route('login')); ?>" class="btn btn-outline">Log In</a>
                <a href="<?php echo e(route('register')); ?>" class="btn btn-primary">Register</a>
            </div>
        </div>
    </div>

    <!-- Floating Chat Widget -->
    <button class="floating-chat-btn" id="floatingChatBtn">
        <i class="fas fa-comments"></i>
        <span>Chat</span>
    </button>

    <!-- Support Request Modal -->
    <div class="support-modal" id="supportModal">
        <div class="support-modal-content">
            <button class="close-support-modal" id="closeSupportModal">
                <i class="fas fa-times"></i>
            </button>
            
            <div class="support-modal-header">
                <i class="fas fa-headset"></i>
                <h2>Contact Support</h2>
                <p>We're here to help. Tell us how we can assist you.</p>
            </div>
            
            <form id="supportForm" class="support-form">
                <div class="form-group">
                    <label for="name">Full Name *</label>
                    <input type="text" id="name" name="name" required placeholder="Your full name">
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" id="email" name="email" required placeholder="your@email.com">
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone Number *</label>
                    <input type="tel" id="phone" name="phone" required placeholder="+263...">
                </div>
                
                <div class="form-group">
                    <label for="category">Issue Category *</label>
                    <select id="category" name="category" required>
                        <option value="">Select a category</option>
                        <option value="technical">Technical Issue</option>
                        <option value="account">Account Help</option>
                        <option value="incident">Incident Report Question</option>
                        <option value="general">General Inquiry</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="description">Message *</label>
                    <textarea id="description" name="description" required placeholder="Please describe your issue..." rows="4"></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%;">Submit Request</button>
            </form>
        </div>
    </div>

    <!-- Response Modal -->
    <div class="response-modal" id="responseModal">
        <div class="response-modal-content">
            <div class="response-icon" id="responseIcon"></div>
            <h2 id="responseTitle"></h2>
            <p id="responseMessage"></p>
            <button class="btn btn-primary" id="closeResponseModal" style="width: 100%; margin-top: 1.5rem;">Close</button>
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

    <!-- Mobile Menu Toggle Script -->
    <script>
        const menuToggle = document.querySelector('.menu-toggle');
        const mobileMenu = document.querySelector('.mobile-menu');
        const closeMenu = document.querySelector('.close-menu');
        const mobileMenuNav = document.querySelector('.mobile-menu-nav');
        const mobileMenuButtons = document.querySelector('.mobile-menu-buttons');

        // Open menu
        menuToggle.addEventListener('click', () => {
            mobileMenu.classList.add('active');
        });

        // Close menu when X button is clicked
        closeMenu.addEventListener('click', () => {
            mobileMenu.classList.remove('active');
        });

        // Close menu when clicking outside (on the overlay)
        mobileMenu.addEventListener('click', (e) => {
            if (e.target === mobileMenu) {
                mobileMenu.classList.remove('active');
            }
        });

        // Close menu when clicking on a link
        if (mobileMenuNav) {
            mobileMenuNav.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', () => {
                    mobileMenu.classList.remove('active');
                });
            });
        }

        // Close menu when clicking on a button
        if (mobileMenuButtons) {
            mobileMenuButtons.querySelectorAll('a').forEach(btn => {
                btn.addEventListener('click', () => {
                    mobileMenu.classList.remove('active');
                });
            });
        }

        // Floating Chat Widget Script
        const floatingChatBtn = document.getElementById('floatingChatBtn');
        const supportModal = document.getElementById('supportModal');
        const closeSupportModal = document.getElementById('closeSupportModal');
        const header = document.querySelector('header');
        const supportForm = document.getElementById('supportForm');
        const responseModal = document.getElementById('responseModal');
        const closeResponseModal = document.getElementById('closeResponseModal');

        // Open support modal
        floatingChatBtn.addEventListener('click', () => {
            supportModal.classList.add('active');
            if (header) header.style.display = 'none';
        });

        // Close support modal
        closeSupportModal.addEventListener('click', () => {
            supportModal.classList.remove('active');
            if (header) header.style.display = '';
        });

        // Close support modal when clicking outside
        supportModal.addEventListener('click', (e) => {
            if (e.target === supportModal) {
                supportModal.classList.remove('active');
                if (header) header.style.display = '';
            }
        });

        // Handle form submission
        supportForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData(supportForm);
            const data = {
                name: formData.get('name'),
                email: formData.get('email'),
                phone: formData.get('phone'),
                category: formData.get('category'),
                description: formData.get('description'),
            };

            try {
                // Submit to public support endpoint
                const response = await fetch('<?php echo e(route("support.public_request")); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(data),
                });

                // Close support modal
                supportModal.classList.remove('active');

                // Show response modal
                responseModal.classList.add('active');

                if (response.ok) {
                    const result = await response.json();
                    document.getElementById('responseIcon').className = 'response-icon success';
                    document.getElementById('responseTitle').textContent = 'Request Submitted Successfully!';
                    document.getElementById('responseMessage').textContent = 'Thank you for contacting us. Our support team will get back to you shortly at ' + data.email + '.';
                } else {
                    const result = await response.json();
                    document.getElementById('responseIcon').className = 'response-icon error';
                    document.getElementById('responseTitle').textContent = 'Submission Failed';
                    document.getElementById('responseMessage').textContent = result.message || 'An error occurred while submitting your request. Please try again.';
                }
            } catch (error) {
                console.error('Error:', error);
                supportModal.classList.remove('active');
                responseModal.classList.add('active');
                document.getElementById('responseIcon').className = 'response-icon error';
                document.getElementById('responseTitle').textContent = 'Error';
                document.getElementById('responseMessage').textContent = 'An error occurred. Please try again later.';
            }
        });

        // Close response modal
        closeResponseModal.addEventListener('click', () => {
            responseModal.classList.remove('active');
            if (header) header.style.display = '';
            // Reset form
            supportForm.reset();
        });

        // Close response modal when clicking outside
        responseModal.addEventListener('click', (e) => {
            if (e.target === responseModal) {
                responseModal.classList.remove('active');
                supportForm.reset();
            }
        });
    </script>
</body>
</html>
<?php /**PATH D:\OccupationalHealth System\resources\views/welcome.blade.php ENDPATH**/ ?>