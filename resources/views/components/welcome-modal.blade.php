<!-- Welcome Modal - Shows once after login -->
<div 
    x-data="{ 
        show: {{ session('just_logged_in') ? 'true' : 'false' }},
        greeting: '',
        
        init() {
            if (this.show) {
                const hour = new Date().getHours();
                
                // Set greeting based on time
                if (hour >= 5 && hour < 12) {
                    this.greeting = 'Good Morning';
                } else if (hour >= 12 && hour < 17) {
                    this.greeting = 'Good Afternoon';
                } else if (hour >= 17 && hour < 21) {
                    this.greeting = 'Good Evening';
                } else {
                    this.greeting = 'Good Evening';
                }
                
                // Auto-close after 3 seconds
                setTimeout(() => {
                    this.show = false;
                }, 3000);
            }
        }
    }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform scale-90"
    x-transition:enter-end="opacity-100 transform scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform scale-100"
    x-transition:leave-end="opacity-0 transform scale-90"
    class="fixed inset-0 z-[100] flex items-center justify-center p-4"
    style="display: none;"
>
    <!-- Backdrop -->
    <div 
        @click="show = false"
        class="absolute inset-0 bg-black/30 backdrop-blur-sm"
    ></div>
    
    <!-- Modal Content -->
    <div class="relative bg-white rounded-2xl shadow-2xl max-w-sm w-full overflow-hidden">
        <!-- Decorative Top Bar -->
        <div class="h-1.5 bg-gradient-to-r from-brand via-brand-light to-brand"></div>
        
        <!-- Content -->
        <div class="p-8 text-center">
            <!-- Icon -->
            <div class="mb-4 flex justify-center">
                <div class="w-16 h-16 bg-gradient-to-br from-brand to-brand-light rounded-full flex items-center justify-center shadow-lg">
                    <i class="fa-solid fa-hand-sparkles text-white text-2xl"></i>
                </div>
            </div>
            
            <!-- Greeting -->
            <h2 class="text-2xl font-bold text-navy mb-2" x-text="greeting + ','" x-cloak></h2>
            <p class="text-xl font-semibold text-brand">{{ Auth::user()->name }}!</p>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>
