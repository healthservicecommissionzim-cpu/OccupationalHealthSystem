<!-- FAQ Accordion Component -->
<section class="faq-section" aria-label="Frequently Asked Questions">
    <div class="faq-header">
        <h2>Frequently Asked Questions</h2>
        <p>Find answers to common questions about our services</p>
    </div>

    <div class="faq-search">
        <input 
            type="text" 
            id="faqSearch" 
            class="faq-search-input" 
            placeholder="Search FAQs..." 
            aria-label="Search frequently asked questions"
        >
    </div>

    <div class="faq-container" id="faqContainer">
        <!-- FAQ items will be generated here or populated from data -->
        @forelse($faqs ?? [] as $index => $faq)
        <div class="faq-item" data-faq-category="{{ $faq['category'] ?? 'general' }}" data-faq-searchable="{{ strtolower($faq['question'] . ' ' . $faq['answer']) }}">
            <button 
                class="faq-question" 
                aria-expanded="false" 
                aria-controls="faq-answer-{{ $index }}"
                id="faq-trigger-{{ $index }}"
            >
                <span class="faq-question-text">{{ $faq['question'] }}</span>
                <i class="fa-solid fa-chevron-down faq-icon"></i>
            </button>
            <div 
                class="faq-answer" 
                id="faq-answer-{{ $index }}" 
                role="region" 
                aria-labelledby="faq-trigger-{{ $index }}"
            >
                <div class="faq-answer-content">
                    {{ $faq['answer'] }}
                </div>
            </div>
        </div>
        @empty
        <p class="no-faqs">No FAQs available at the moment.</p>
        @endforelse
    </div>
</section>

<style>
.faq-section {
    margin: 40px 0;
    max-width: 900px;
    margin-left: auto;
    margin-right: auto;
}

.faq-header {
    text-align: center;
    margin-bottom: 32px;
}

.faq-header h2 {
    font-size: 28px;
    font-weight: 700;
    color: #1D4533;
    margin: 0 0 8px;
}

.faq-header p {
    color: #6b7280;
    font-size: 15px;
    margin: 0;
}

.faq-search {
    margin-bottom: 24px;
    display: flex;
    justify-content: center;
}

.faq-search-input {
    width: 100%;
    max-width: 500px;
    padding: 12px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
    font-family: inherit;
    transition: all 0.3s;
}

.faq-search-input:focus {
    outline: none;
    border-color: #1D4533;
    box-shadow: 0 0 0 3px rgba(29, 69, 51, 0.1);
}

.faq-container {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.faq-item {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    background: white;
    overflow: hidden;
    transition: all 0.3s;
}

.faq-item:hover {
    border-color: #1D4533;
    box-shadow: 0 4px 12px rgba(29, 69, 51, 0.08);
}

.faq-question {
    width: 100%;
    padding: 16px 20px;
    background: white;
    border: none;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    font-size: 15px;
    font-weight: 600;
    color: #1D4533;
    transition: all 0.3s;
    text-align: left;
}

.faq-question:hover {
    background: #f9fafb;
}

.faq-question:focus {
    outline: 2px solid #1D4533;
    outline-offset: -2px;
}

.faq-question-text {
    flex: 1;
    text-align: left;
}

.faq-icon {
    font-size: 16px;
    transition: transform 0.3s;
    color: #6b7280;
    flex-shrink: 0;
}

.faq-question[aria-expanded="true"] {
    background: #f0fdf4;
}

.faq-question[aria-expanded="true"] .faq-icon {
    transform: rotate(180deg);
    color: #1D4533;
}

.faq-answer {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
}

.faq-question[aria-expanded="true"] ~ .faq-answer {
    max-height: 1000px;
}

.faq-answer-content {
    padding: 20px;
    background: #fafbf9;
    color: #4b5563;
    font-size: 14px;
    line-height: 1.7;
    border-top: 1px solid #e5e7eb;
}

.faq-answer-content p {
    margin: 0 0 12px;
}

.faq-answer-content p:last-child {
    margin: 0;
}

.faq-answer-content strong {
    color: #1D4533;
}

.faq-answer-content a {
    color: #1D4533;
    text-decoration: none;
    font-weight: 600;
    border-bottom: 2px solid transparent;
    transition: border 0.3s;
}

.faq-answer-content a:hover {
    border-bottom-color: #1D4533;
}

.faq-answer-content a:focus {
    outline: 2px solid #1D4533;
}

.no-faqs {
    text-align: center;
    color: #9ca3af;
    padding: 32px 16px;
    font-size: 14px;
}

.faq-item.hidden {
    display: none;
}

@media (max-width: 768px) {
    .faq-section {
        margin: 24px 0;
    }

    .faq-header h2 {
        font-size: 22px;
    }

    .faq-question {
        padding: 12px 16px;
        font-size: 14px;
    }

    .faq-answer-content {
        padding: 16px;
        font-size: 13px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const faqItems = document.querySelectorAll('.faq-item');
    const faqSearch = document.getElementById('faqSearch');

    // Toggle FAQ items
    faqItems.forEach(item => {
        const button = item.querySelector('.faq-question');
        
        button.addEventListener('click', () => {
            const isExpanded = button.getAttribute('aria-expanded') === 'true';
            
            // Close other open items
            faqItems.forEach(otherItem => {
                const otherButton = otherItem.querySelector('.faq-question');
                otherButton.setAttribute('aria-expanded', 'false');
            });
            
            // Toggle current item
            button.setAttribute('aria-expanded', !isExpanded);
        });

        // Allow Enter and Space to open/close
        button.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                button.click();
            }
        });
    });

    // Search functionality
    if (faqSearch) {
        faqSearch.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            
            faqItems.forEach(item => {
                const searchable = item.dataset.faqSearchable?.toLowerCase() || '';
                const matches = searchable.includes(searchTerm);
                
                item.classList.toggle('hidden', !matches);
                
                // Collapse non-matching items
                if (!matches) {
                    const button = item.querySelector('.faq-question');
                    button.setAttribute('aria-expanded', 'false');
                }
            });
        });
    }
});
</script>
