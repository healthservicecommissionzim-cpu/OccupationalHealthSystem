<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#174c3b">
    <meta name="description" content="HSC Occupational Health System documentation and user guides.">
    <title>{{ $currentDoc ? $currentDoc['title'] . ' | ' : '' }}HSC Occupational Health System — Documentation</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
</head>
<body style="margin:0">
<style>
    :root {
        --doc-brand: #174c3b;
        --doc-brand-deep: #10382d;
        --doc-brand-soft: #eaf6ef;
        --doc-ink: #152b25;
        --doc-muted: #64756f;
        --doc-line: #e2ebe6;
        --doc-canvas: #f5f8f6;
        --doc-card: #ffffff;
        --doc-shadow: 0 8px 30px rgba(18, 57, 43, .055);
        --doc-radius: 18px;
    }

    .doc-shell, .doc-shell * { box-sizing: border-box; }
    .doc-shell {
        color: var(--doc-ink);
        background:
            radial-gradient(ellipse at 90% 0%, rgba(214, 239, 224, .38), transparent 34rem),
            var(--doc-canvas);
        min-height: 100vh;
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }
    .doc-layout { display: grid; grid-template-columns: 274px minmax(0, 1fr); min-height: 100vh; }
    .doc-sidebar {
        position: sticky; top: 0; align-self: start; height: 100vh;
        overflow-y: auto; padding: 22px 16px 18px; background: rgba(255,255,255,.92);
        border-right: 1px solid var(--doc-line); backdrop-filter: blur(14px); z-index: 20;
    }
    .doc-back {
        display: inline-flex; align-items: center; gap: 9px; padding: 9px 11px; margin: 0 0 20px;
        border-radius: 10px; color: var(--doc-brand); text-decoration: none; font-size: .82rem; font-weight: 700;
        transition: background .2s, transform .2s;
    }
    .doc-back:hover { background: var(--doc-brand-soft); transform: translateX(-2px); }
    .doc-branding { display: flex; align-items: center; gap: 12px; padding: 0 10px 22px; border-bottom: 1px solid var(--doc-line); }
    .doc-brand-mark {
        width: 45px; height: 45px; flex: 0 0 45px; display: grid; place-items: center;
        color: #fff; border-radius: 14px; font-size: 1.25rem;
        background: linear-gradient(145deg, #28795b, #123e31); box-shadow: 0 7px 16px rgba(23,76,59,.2);
    }
    .doc-branding h1 { margin: 0; color: var(--doc-ink); font-size: 1rem; line-height: 1.35; font-weight: 800; letter-spacing: -.025em; }
    .doc-branding p { margin: 4px 0 0; color: var(--doc-muted); font-size: .73rem; }
    .doc-nav-label { padding: 22px 12px 9px; color: #8a9b93; font-size: .67rem; font-weight: 800; text-transform: uppercase; letter-spacing: .13em; }
    .doc-nav { display: grid; gap: 4px; }
    .doc-nav-item {
        position: relative; display: flex; align-items: center; gap: 12px; min-height: 44px; padding: 10px 12px;
        border: 1px solid transparent; border-radius: 12px; color: #52665e; text-decoration: none;
        font-size: .82rem; font-weight: 600; transition: color .18s, background .18s, border-color .18s, transform .18s;
    }
    .doc-nav-item i { width: 18px; text-align: center; font-size: .95rem; color: #7b8e85; }
    .doc-nav-item:hover { background: #f5faf6; color: var(--doc-brand); transform: translateX(2px); }
    .doc-nav-item.active { color: var(--doc-brand); background: var(--doc-brand-soft); border-color: #d7eadf; font-weight: 800; }
    .doc-nav-item.active i { color: var(--doc-brand); }
    .doc-sidebar-note {
        margin: 28px 4px 0; padding: 18px; overflow: hidden; position: relative;
        border: 1px solid #dceee3; border-radius: 16px;
        background: linear-gradient(145deg, #f2faf5, #e4f3e9);
    }
    .doc-sidebar-note:after { content: ""; position: absolute; width: 100px; height: 100px; border-radius: 50%; right: -42px; bottom: -54px; background: rgba(43,124,88,.09); }
    .doc-sidebar-note .note-icon { display: grid; place-items: center; width: 36px; height: 36px; border-radius: 11px; color: white; background: var(--doc-brand); margin-bottom: 13px; }
    .doc-sidebar-note strong { display: block; font-size: .85rem; margin-bottom: 6px; }
    .doc-sidebar-note p { margin: 0; color: var(--doc-muted); font-size: .76rem; line-height: 1.65; }
    .doc-main { min-width: 0; padding: clamp(20px, 3.1vw, 46px); }
    .doc-content { max-width: 1180px; margin: 0 auto; }
    .doc-toolbar { display: flex; align-items: center; justify-content: space-between; gap: 16px; margin-bottom: 24px; }
    .doc-crumbs { display: flex; flex-wrap: wrap; align-items: center; gap: 8px; color: #8a9992; font-size: .78rem; }
    .doc-crumbs strong { color: var(--doc-brand); font-weight: 800; }
    .doc-tools { display: flex; align-items: center; gap: 10px; }
    .doc-search { position: relative; width: min(320px, 34vw); }
    .doc-search i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #82938b; }
    .doc-search input {
        width: 100%; height: 42px; padding: 0 14px 0 40px; border: 1px solid #dfe9e3; border-radius: 12px;
        outline: none; color: var(--doc-ink); background: rgba(255,255,255,.88); font: inherit; font-size: .8rem;
        transition: border .2s, box-shadow .2s;
    }
    .doc-search input:focus { border-color: #76ae91; box-shadow: 0 0 0 4px rgba(61,139,96,.1); }
    .doc-menu-toggle { display: none; width: 42px; height: 42px; border: 1px solid var(--doc-line); border-radius: 12px; color: var(--doc-brand); background: white; }
    .doc-hero {
        position: relative; isolation: isolate; overflow: hidden; display: flex; align-items: center; justify-content: space-between; gap: 24px;
        padding: clamp(26px, 4vw, 48px); margin-bottom: 22px; border-radius: 22px; color: white;
        background: linear-gradient(118deg, #123e31 0%, #1d6549 62%, #2c805c 100%);
        box-shadow: 0 16px 34px rgba(23,76,59,.16);
    }
    .doc-hero:before, .doc-hero:after { content: ""; position: absolute; z-index: -1; border: 1px solid rgba(255,255,255,.1); border-radius: 50%; }
    .doc-hero:before { width: 360px; height: 360px; right: 8%; top: -240px; box-shadow: 0 0 0 32px rgba(255,255,255,.025), 0 0 0 68px rgba(255,255,255,.025); }
    .doc-hero:after { width: 250px; height: 250px; right: -65px; bottom: -190px; box-shadow: 0 0 0 28px rgba(255,255,255,.04), 0 0 0 60px rgba(255,255,255,.025); }
    .doc-hero-copy { max-width: 720px; }
    .doc-eyebrow { display: inline-flex; align-items: center; gap: 8px; padding: 7px 11px; border: 1px solid rgba(255,255,255,.2); border-radius: 999px; background: rgba(255,255,255,.1); color: #d8f3e3; font-size: .72rem; font-weight: 800; letter-spacing: .04em; }
    .doc-hero h2 { margin: 17px 0 12px; color: white; font-size: clamp(1.8rem, 3.2vw, 2.75rem); line-height: 1.12; letter-spacing: -.045em; font-weight: 800; }
    .doc-hero p { max-width: 650px; margin: 0; color: rgba(255,255,255,.8); font-size: .96rem; line-height: 1.8; }
    .doc-hero-symbol { flex: 0 0 auto; width: 104px; height: 104px; display: grid; place-items: center; border: 1px solid rgba(255,255,255,.18); border-radius: 28px; background: rgba(255,255,255,.1); color: rgba(255,255,255,.94); font-size: 3.1rem; transform: rotate(3deg); }
    .doc-meta-row { display: flex; flex-wrap: wrap; gap: 10px 20px; margin-top: 22px; color: #d9f0e2; font-size: .75rem; }
    .doc-meta-row span { display: inline-flex; align-items: center; gap: 7px; }
    .doc-meta-row i { color: #a6e1bd; }
    .doc-paper {
        background: var(--doc-card); border: 1px solid #e5ede8; border-radius: var(--doc-radius);
        padding: clamp(22px, 3vw, 38px); box-shadow: var(--doc-shadow); overflow-wrap: anywhere;
    }
    .doc-paper h1, .doc-paper h2, .doc-paper h3, .doc-paper h4 { color: var(--doc-ink); letter-spacing: -.025em; line-height: 1.35; }
    .doc-paper h1 { font-size: 1.8rem; font-weight: 800; }
    .doc-paper h2 { font-size: 1.45rem; font-weight: 800; }
    .doc-paper h3 { font-size: 1.15rem; font-weight: 800; margin: 2rem 0 1rem; padding: 0 0 12px; border-bottom: 1px solid var(--doc-line); }
    .doc-paper h3:first-child { margin-top: 0; }
    .doc-paper h4 { font-size: .98rem; font-weight: 800; margin: 1.35rem 0 .65rem; }
    .doc-paper p { color: #596c63; font-size: .91rem; line-height: 1.85; margin: 0 0 1rem; }
    .doc-paper ul, .doc-paper ol { color: #596c63; padding-left: 1.45rem; margin: .75rem 0 1.4rem; }
    .doc-paper li { padding-left: .2rem; margin-bottom: .55rem; font-size: .9rem; line-height: 1.75; }
    .doc-paper li::marker { color: #4a9670; font-weight: 800; }
    .doc-paper strong { color: #233d31; font-weight: 800; }
    .doc-paper a { color: #23734f; font-weight: 700; text-underline-offset: 3px; }
    .doc-paper a:hover { color: #10382d; }
    .doc-paper table { width: 100%; border-collapse: separate; border-spacing: 0; margin: 1.25rem 0 1.6rem; font-size: .85rem; border: 1px solid var(--doc-line); border-radius: 12px; overflow: hidden; }
    .doc-paper th { padding: 13px 15px; text-align: left; background: #eff7f1; color: #244c38; font-weight: 800; }
    .doc-paper td { padding: 12px 15px; border-top: 1px solid var(--doc-line); color: #596c63; vertical-align: top; }
    .doc-paper tr:nth-child(even) td { background: #fbfdfb; }
    .doc-paper blockquote { margin: 1.2rem 0; padding: 15px 18px; border-left: 4px solid #4e9a70; border-radius: 0 12px 12px 0; background: #f0f8f3; color: #456153; }
    .doc-paper code { padding: .15rem .4rem; border-radius: 5px; background: #eff5f1; color: #24583e; font-size: .85em; }
    .doc-paper pre { padding: 18px; overflow: auto; border-radius: 12px; background: #122c22; color: #e4f4e9; }
    .doc-paper img { max-width: 100%; height: auto; border-radius: 12px; }
    .role-card { margin: 1.25rem 0; padding: 24px; border: 1px solid #e1ebe4; border-radius: 16px; background: linear-gradient(145deg, #fff, #fbfdfb); transition: border-color .2s, box-shadow .2s, transform .2s; }
    .role-card:hover { border-color: #b7d8c3; box-shadow: 0 12px 28px rgba(23,76,59,.08); transform: translateY(-2px); }
    .role-card-header { display: flex; align-items: center; gap: 14px; margin-bottom: 18px; }
    .role-card-icon { width: 48px; height: 48px; display: grid; place-items: center; flex: 0 0 48px; border-radius: 14px; color: var(--doc-brand); background: var(--doc-brand-soft); font-size: 1.2rem; }
    .role-card-title { min-width: 0; }
    .role-card-title h3 { margin: 0 0 4px !important; padding: 0 !important; border: 0 !important; font-size: 1.08rem !important; }
    .role-purpose { margin: 0 !important; color: #788a81 !important; font-size: .82rem !important; }
    .role-card h4 { margin-top: 1rem; color: var(--doc-brand); font-size: .77rem; text-transform: uppercase; letter-spacing: .09em; }
    .role-card ul { margin-bottom: .6rem; }
    .doc-alert { display: flex; gap: 12px; align-items: flex-start; margin: 1.4rem 0; padding: 16px 18px; border: 1px solid #d9e9df; border-radius: 13px; background: #f3faf5; color: #345c45; }
    .doc-alert i { margin-top: 3px; color: #358257; }
    .doc-navigation { display: flex; justify-content: space-between; align-items: center; gap: 14px; margin-top: 22px; }
    .doc-nav-btn { display: inline-flex; align-items: center; justify-content: center; gap: 9px; min-height: 44px; padding: 0 17px; border: 1px solid #dce8e0; border-radius: 12px; color: #4c6256; background: white; text-decoration: none; font-size: .82rem; font-weight: 800; transition: all .2s; }
    .doc-nav-btn:hover { color: var(--doc-brand); border-color: #a9ceb7; background: #f4faf6; transform: translateY(-1px); }
    .doc-nav-btn.primary { color: white; border-color: var(--doc-brand); background: var(--doc-brand); box-shadow: 0 5px 12px rgba(23,76,59,.13); }
    .doc-nav-btn.primary:hover { background: var(--doc-brand-deep); }
    .doc-help { display: flex; align-items: center; justify-content: space-between; gap: 20px; margin-top: 22px; padding: 20px 22px; border: 1px solid #dcebe1; border-radius: 16px; background: linear-gradient(110deg, #edf8f1, #f8fbf8); }
    .doc-help-copy { display: flex; align-items: center; gap: 14px; }
    .doc-help-icon { display: grid; place-items: center; width: 44px; height: 44px; flex: 0 0 44px; border-radius: 14px; color: white; background: var(--doc-brand); font-size: 1.1rem; }
    .doc-help strong { display: block; margin-bottom: 4px; font-size: .9rem; }
    .doc-help p { margin: 0; color: var(--doc-muted); font-size: .79rem; line-height: 1.6; }
    .doc-help a { flex: 0 0 auto; padding: 11px 15px; border-radius: 10px; color: white; background: var(--doc-brand); text-decoration: none; font-size: .78rem; font-weight: 800; }
    .doc-help a:hover { background: var(--doc-brand-deep); }
    .doc-empty { text-align: center; padding: 72px 24px; }
    .doc-empty-icon { width: 72px; height: 72px; display: grid; place-items: center; margin: 0 auto 20px; border-radius: 22px; background: var(--doc-brand-soft); color: var(--doc-brand); font-size: 1.8rem; }
    .doc-empty h2 { margin: 0 0 8px; font-size: 1.3rem; }
    .doc-empty p { color: var(--doc-muted); margin: 0; font-size: .88rem; }
    .doc-footer { display: flex; justify-content: space-between; gap: 12px; flex-wrap: wrap; padding: 24px 2px 4px; color: #87968e; font-size: .72rem; }
    .doc-footer strong { color: #526a5c; }
    .doc-no-results { display: none; margin: 8px 8px 0; color: #87968e; font-size: .76rem; }
    .doc-nav-item[hidden] { display: none !important; }
    @media (max-width: 1020px) {
        .doc-layout { grid-template-columns: 245px minmax(0, 1fr); }
        .doc-main { padding: 24px; }
        .doc-hero-symbol { width: 80px; height: 80px; border-radius: 22px; font-size: 2.4rem; }
    }
    @media (max-width: 780px) {
        .doc-layout { display: block; }
        .doc-sidebar { display: none; position: relative; top: auto; height: auto; max-height: 70vh; border-right: 0; border-bottom: 1px solid var(--doc-line); box-shadow: 0 12px 24px rgba(18,57,43,.07); }
        .doc-sidebar.is-open { display: block; }
        .doc-main { padding: 18px 14px 26px; }
        .doc-toolbar { align-items: flex-start; }
        .doc-menu-toggle { display: inline-grid; place-items: center; flex: 0 0 42px; }
        .doc-search { width: min(290px, calc(100vw - 100px)); }
        .doc-hero { padding: 27px 23px; border-radius: 18px; }
        .doc-hero-symbol { display: none; }
        .doc-hero p { font-size: .88rem; }
        .doc-meta-row { gap: 10px 14px; }
        .doc-navigation { margin-top: 18px; }
        .doc-help { align-items: flex-start; flex-direction: column; }
        .doc-help a { margin-left: 58px; }
    }
    @media (max-width: 480px) {
        .doc-crumbs { font-size: .7rem; }
        .doc-search input { height: 40px; font-size: .74rem; }
        .doc-hero h2 { font-size: 1.8rem; }
        .doc-paper { padding: 20px 17px; border-radius: 15px; }
        .doc-paper h3 { font-size: 1.05rem; }
        .role-card { padding: 18px; }
        .role-card-header { align-items: flex-start; }
        .doc-nav-btn { padding: 0 12px; font-size: .76rem; }
        .doc-footer { flex-direction: column; }
    }
    @media (prefers-reduced-motion: reduce) {
        .doc-shell *, .doc-shell *::before, .doc-shell *::after { scroll-behavior: auto !important; transition-duration: .01ms !important; animation-duration: .01ms !important; }
    }
</style>

<div class="doc-shell">
    <div class="doc-layout">
        <aside class="doc-sidebar" id="docSidebar" aria-label="Documentation navigation">
            <a href="{{ route('dashboards.super_admin') }}" class="doc-back">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                <span>Back to dashboard</span>
            </a>

            <div class="doc-branding">
                <div class="doc-brand-mark"><i class="fa-solid fa-book-open" aria-hidden="true"></i></div>
                <div>
                    <h1>Documentation</h1>
                    <p>HSC Occupational Health</p>
                </div>
            </div>

            <div class="doc-nav-label">Knowledge centre</div>
            <nav class="doc-nav" id="docNav">
                @foreach($docs as $doc)
                    <a href="{{ route('system.documentation.show', $doc['slug']) }}"
                       class="doc-nav-item {{ $currentDoc && $currentDoc['slug'] === $doc['slug'] ? 'active' : '' }}"
                       data-doc-nav aria-current="{{ $currentDoc && $currentDoc['slug'] === $doc['slug'] ? 'page' : 'false' }}">
                        <i class="fa-solid {{ $doc['icon'] }}" aria-hidden="true"></i>
                        <span>{{ $doc['title'] }}</span>
                    </a>
                @endforeach
            </nav>
            <p class="doc-no-results" id="docNoResults">No topics match your search.</p>

            <div class="doc-sidebar-note">
                <div class="note-icon"><i class="fa-solid fa-shield-heart" aria-hidden="true"></i></div>
                <strong>Safer workplaces start with knowledge.</strong>
                <p>Use these guides to work confidently, protect information, and support healthier workplaces.</p>
            </div>
        </aside>

        <main class="doc-main">
            <div class="doc-content">
                <div class="doc-toolbar">
                    <div class="doc-crumbs">
                        <span>HSC Occupational Health</span><i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                        <strong>Documentation</strong>
                        @if($currentDoc)
                            <i class="fa-solid fa-chevron-right" aria-hidden="true"></i><span>{{ $currentDoc['title'] }}</span>
                        @endif
                    </div>
                    <div class="doc-tools">
                        <label class="doc-search">
                            <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                            <input type="search" id="docSearch" placeholder="Search documentation..." aria-label="Search documentation topics">
                        </label>
                        <button type="button" class="doc-menu-toggle" id="docMenuToggle" aria-label="Toggle documentation menu" aria-expanded="false" aria-controls="docSidebar">
                            <i class="fa-solid fa-bars" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>

                <section class="doc-hero" aria-labelledby="docHeroTitle">
                    <div class="doc-hero-copy">
                        <span class="doc-eyebrow"><i class="fa-solid fa-sparkles" aria-hidden="true"></i> YOUR HSC KNOWLEDGE CENTRE</span>
                        <h2 id="docHeroTitle">{{ $currentDoc ? $currentDoc['title'] : 'HSC Occupational Health System' }}</h2>
                        <p>Clear, practical guidance to help you navigate the system, understand your responsibilities, and make the most of its occupational health and safety tools.</p>
                        <div class="doc-meta-row">
                            <span><i class="fa-solid fa-circle-check" aria-hidden="true"></i> Step-by-step guidance</span>
                            <span><i class="fa-solid fa-bookmark" aria-hidden="true"></i> Practical reference</span>
                            <span><i class="fa-solid fa-shield-halved" aria-hidden="true"></i> Built for HSC</span>
                        </div>
                    </div>
                    <div class="doc-hero-symbol" aria-hidden="true"><i class="fa-solid fa-book-open-reader"></i></div>
                </section>

                @if($currentDoc)
                    <article class="doc-paper" id="docArticle">
                        {!! $currentDoc['content'] !!}
                    </article>

                    @php
                        $currentIndex = array_search($currentDoc, $docs);
                        $prevDoc = $currentIndex !== false && $currentIndex > 0 ? $docs[$currentIndex - 1] : null;
                        $nextDoc = $currentIndex !== false && $currentIndex < count($docs) - 1 ? $docs[$currentIndex + 1] : null;
                    @endphp

                    <nav class="doc-navigation" aria-label="Documentation page navigation">
                        @if($prevDoc)
                            <a href="{{ route('system.documentation.show', $prevDoc['slug']) }}" class="doc-nav-btn">
                                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i><span>Previous topic</span>
                            </a>
                        @else
                            <span></span>
                        @endif
                        @if($nextDoc)
                            <a href="{{ route('system.documentation.show', $nextDoc['slug']) }}" class="doc-nav-btn primary">
                                <span>Next topic</span><i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                            </a>
                        @else
                            <span></span>
                        @endif
                    </nav>
                @else
                    <section class="doc-paper doc-empty">
                        <div class="doc-empty-icon"><i class="fa-solid fa-book-open" aria-hidden="true"></i></div>
                        <h2>Explore the system guide</h2>
                        <p>Select a topic from the navigation to find step-by-step instructions and helpful information.</p>
                    </section>
                @endif

                <section class="doc-help">
                    <div class="doc-help-copy">
                        <div class="doc-help-icon"><i class="fa-solid fa-headset" aria-hidden="true"></i></div>
                        <div>
                            <strong>Need a hand? IT Support is here to help.</strong>
                            <p>For assistance with access, troubleshooting, or using the system, contact the support team.</p>
                        </div>
                    </div>
                    <a href="mailto:support@hsc.org.zw"><i class="fa-regular fa-envelope" aria-hidden="true"></i> Contact support</a>
                </section>

                <footer class="doc-footer">
                    <span><strong>HSC Occupational Health System</strong> &nbsp;·&nbsp; Documentation portal</span>
                    <span><i class="fa-solid fa-shield-heart" aria-hidden="true"></i> Health &nbsp;·&nbsp; Safety &nbsp;·&nbsp; Excellence</span>
                </footer>
            </div>
        </main>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const search = document.getElementById('docSearch');
    const nav = document.getElementById('docNav');
    const noResults = document.getElementById('docNoResults');
    const menuToggle = document.getElementById('docMenuToggle');
    const sidebar = document.getElementById('docSidebar');

    if (search && nav) {
        const links = Array.from(nav.querySelectorAll('[data-doc-nav]'));
        search.addEventListener('input', function () {
            const query = this.value.trim().toLocaleLowerCase();
            let visible = 0;
            links.forEach(function (link) {
                const matches = link.textContent.toLocaleLowerCase().includes(query);
                link.hidden = !matches;
                if (matches) visible++;
            });
            if (noResults) noResults.style.display = visible === 0 ? 'block' : 'none';
        });
        search.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                this.value = '';
                this.dispatchEvent(new Event('input'));
                this.blur();
            }
        });
    }

    if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', function () {
            const open = sidebar.classList.toggle('is-open');
            menuToggle.setAttribute('aria-expanded', String(open));
            const icon = menuToggle.querySelector('i');
            if (icon) icon.className = open ? 'fa-solid fa-xmark' : 'fa-solid fa-bars';
        });
    }

    // Present role headings as readable cards on the roles documentation page.
    const article = document.getElementById('docArticle');
    if (article) {
        const roleNames = ['Worker', 'Supervisor', 'HR (', 'Super Admin'];
        const roleIcons = {
            'Worker': 'fa-person',
            'Supervisor': 'fa-user-tie',
            'HR': 'fa-briefcase',
            'Super Admin': 'fa-shield-halved'
        };
        Array.from(article.querySelectorAll('h3')).forEach(function (heading) {
            const roleKey = roleNames.find(function (name) { return heading.textContent.includes(name); });
            if (!roleKey) return;

            const card = document.createElement('section');
            card.className = 'role-card';
            const header = document.createElement('div');
            header.className = 'role-card-header';
            const icon = document.createElement('div');
            icon.className = 'role-card-icon';
            const iconElement = document.createElement('i');
            const matchedIcon = Object.keys(roleIcons).find(function (name) { return heading.textContent.includes(name); });
            iconElement.className = 'fa-solid ' + (roleIcons[matchedIcon] || 'fa-user');
            iconElement.setAttribute('aria-hidden', 'true');
            icon.appendChild(iconElement);

            const title = document.createElement('div');
            title.className = 'role-card-title';
            const titleElement = document.createElement('h3');
            titleElement.textContent = heading.textContent.replace(/^\d+\.\s*/, '');
            title.appendChild(titleElement);

            let next = heading.nextElementSibling;
            if (next && next.tagName === 'P') {
                const purpose = document.createElement('p');
                purpose.className = 'role-purpose';
                purpose.textContent = next.textContent;
                title.appendChild(purpose);
            }
            header.appendChild(icon);
            header.appendChild(title);
            card.appendChild(header);

            let current = heading.nextElementSibling;
            if (current && current.tagName === 'P') current = current.nextElementSibling;
            while (current && current.tagName !== 'H3') {
                const following = current.nextElementSibling;
                card.appendChild(current);
                current = following;
            }
            heading.replaceWith(card);
        });
    }
});
</script>
</body>
</html>
