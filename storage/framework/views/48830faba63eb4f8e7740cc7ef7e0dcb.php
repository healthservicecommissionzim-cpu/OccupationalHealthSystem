<!-- Language Provider Component - Loads translations -->
<script>
// Multilingual translations
window.translations = {
    en: {
        // Common
        language: 'Language',
        selectLanguage: 'Select Language',
        english: 'English',
        shona: 'Shona (Chishona)',
        ndebele: 'Ndebele (Sindebele)',
        search: 'Search',
        filter: 'Filter',
        clear: 'Clear',
        learnMore: 'Learn more about',
        downloadGuide: 'Download comprehensive guide about',
        viewDetails: 'View detailed information about',
        readMore: 'Read more about',
        
        // Welcome page
        welcome: 'Welcome to HSC-OHS',
        reportIncidents: 'Report Workplace Incidents',
        accessServices: 'Access Occupational Health Services',
        
        // FAQ
        frequentlyAskedQuestions: 'Frequently Asked Questions',
        findAnswers: 'Find answers to common questions about our services',
        
        // Resources
        searchResources: 'Search Resources',
        findInformation: 'Find the information you need quickly',
        categories: 'Categories',
        allCategories: 'All Categories',
        documentType: 'Document Type',
        
        // Safety Protocols
        safetyProtocols: 'Safety Protocols',
        protocolsForWorkplace: 'Essential protocols for workplace safety',
        
        // Support
        needHelp: 'Need Help?',
        contactSupport: 'Contact our support team',
        supportEmail: 'Support Email',
        supportPhone: 'Support Phone',
        liveChat: 'Live Chat',
    },
    sn: {
        // Common
        language: 'Mutauro',
        selectLanguage: 'Sarudza Mutauro',
        english: 'Chirungu',
        shona: 'Chishona',
        ndebele: 'Sindebele',
        search: 'Tsvaga',
        filter: 'Fungidzira',
        clear: 'Edzira',
        learnMore: 'Zani zvakawanda',
        downloadGuide: ' Downloads muenzaniso',
        viewDetails: 'Ona zvakanyanya',
        readMore: 'Verenga zvakawanda',
        
        // Welcome page
        welcome: 'Wanamatira ku HSC-OHS',
        reportIncidents: 'Ripota Zvakaitika Mundebwire',
        accessServices: 'Pinda mu Zvirongwa zveOccupational Health',
        
        // FAQ
        frequentlyAskedQuestions: 'Mibvunzo Inowedzerwa Kazhinji',
        findAnswers: 'Tsvaga mhinduro dzeRibvunzo dzinowedzerwa kazhinji',
        
        // Resources
        searchResources: 'Tsvaga Zvigadziko',
        findInformation: 'Tsvaga ruzivo rwaunoda haraka',
        categories: 'Magaro',
        allCategories: 'Magaro Ese',
        documentType: 'Marudzi e Zuva',
        
        // Safety Protocols
        safetyProtocols: 'Mabiko eKudzivira',
        protocolsForWorkplace: 'Mabiko akakosha ekudzivira mundebwire',
        
        // Support
        needHelp: 'Unoda Rubatsiro?',
        contactSupport: 'Xaneta neimwe team',
        supportEmail: 'Email yeSupport',
        supportPhone: 'Foni yeSupport',
        liveChat: 'Kudzivirirana Live',
    },
    nd: {
        // Common
        language: 'Ulimi',
        selectLanguage: 'Khetha Ulimi',
        english: 'IsiNgisi',
        shona: 'Chishona',
        ndebele: 'Sindebele',
        search: 'Sesha',
        filter: 'Sihlukanisa',
        clear: 'Sula',
        learnMore: 'Funda okwengeziwe',
        downloadGuide: 'Dakela iGawidi',
        viewDetails: 'Buka emininingwane',
        readMore: 'Funda okwengeziwe',
        
        // Welcome page
        welcome: 'Wamukela ku-HSC-OHS',
        reportIncidents: 'Bika Izenzakalo Zomsebenzi',
        accessServices: 'Finyelela Ezikonzweni Zezempilo',
        
        // FAQ
        frequentlyAskedQuestions: 'Imibuzo Edinga Njalo',
        findAnswers: 'Thola amapendulo emibuzo edinga njalo',
        
        // Resources
        searchResources: 'Sesha Izinto Zazisi',
        findInformation: 'Thola inzuzo oyifunayo ngokushesha',
        categories: 'Izigaba',
        allCategories: 'Zonke Izigaba',
        documentType: 'Uhlobo Lwedokhumenti',
        
        // Safety Protocols
        safetyProtocols: 'Iziqiniselo Zokuphepha',
        protocolsForWorkplace: 'Iziqiniselo ezibalulekile zokuphepha emsebenzini',
        
        // Support
        needHelp: 'Udinga Okusize?',
        contactSupport: 'Xhumana neqembu lethu',
        supportEmail: 'I-imeyili yokusize',
        supportPhone: 'Ifomu yokusiza',
        liveChat: 'Ukuxhumana kokuqala',
    }
};

// Get current language
function getCurrentLanguage() {
    return localStorage.getItem('accessibility-language') || document.documentElement.lang || 'en';
}

// Translate text
function t(key) {
    const lang = getCurrentLanguage();
    return window.translations[lang]?.[key] || window.translations.en[key] || key;
}

// Translate element
function translateElement(element, key) {
    if (element) {
        element.textContent = t(key);
    }
}

// Translate HTML content recursively
function translatePageContent() {
    const lang = getCurrentLanguage();
    
    // Set page direction (RTL for some languages if needed)
    if (lang === 'ar') {
        document.documentElement.dir = 'rtl';
    } else {
        document.documentElement.dir = 'ltr';
    }
    
    // Update data-translate attributes
    document.querySelectorAll('[data-translate]').forEach(element => {
        const key = element.dataset.translate;
        translateElement(element, key);
    });
    
    // Update placeholders
    document.querySelectorAll('[data-translate-placeholder]').forEach(element => {
        const key = element.dataset.translatePlaceholder;
        element.placeholder = t(key);
    });
    
    // Update aria-labels
    document.querySelectorAll('[data-translate-aria]').forEach(element => {
        const key = element.dataset.translateAria;
        element.setAttribute('aria-label', t(key));
    });
}

// Listen for language changes
window.addEventListener('languageChange', (e) => {
    translatePageContent();
});

// Translate on page load
document.addEventListener('DOMContentLoaded', () => {
    translatePageContent();
});
</script>
<?php /**PATH D:\OccupationalHealth System\resources\views/components/language-provider.blade.php ENDPATH**/ ?>