<div id="subscriptionBanner" class="hidden" x-data="subscriptionBanner()" x-show="show" x-cloak>
    <!-- Mobile: Bottom Sheet (Thumb Zone) -->
    <div class="md:hidden fixed bottom-0 left-0 right-0 z-50 transform transition-transform duration-500"
         x-bind:class="show ? 'translate-y-0' : 'translate-y-full'">
        <div class="bg-gradient-to-r from-primary-500 to-primary-600 text-white p-6 rounded-t-3xl shadow-2xl">
            <button @click="close()" class="absolute top-4 {{ \App\Helpers\LocaleHelper::isRtl() ? 'left-4' : 'right-4' }} text-white/80 hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            
            <div class="mb-4">
                <h3 class="text-xl font-bold mb-2">{{ __('Free Kurdish PDF for Kids!') }}</h3>
                <p class="text-sm text-white/90">{{ __('Subscribe to our newsletter and get a free educational PDF in Kurdish') }}</p>
            </div>
            
            <form @submit.prevent="subscribe()" class="space-y-3">
                <input 
                    type="email" 
                    x-model="email"
                    placeholder="{{ __('Your email') }}"
                    required
                    class="w-full px-4 py-3 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-white">
                
                <label class="flex items-start gap-2 text-xs text-white/80">
                    <input type="checkbox" required class="mt-1">
                    <span>{{ __('I accept the') }} <a href="#" class="underline">{{ __('Privacy Policy') }}</a></span>
                </label>
                
                <button 
                    type="submit"
                    class="w-full py-3 bg-white text-primary-600 font-bold rounded-lg hover:bg-gray-100 transition-colors">
                    {{ __('Subscribe Now') }}
                </button>
            </form>
        </div>
    </div>

    <!-- Desktop: Floating Bottom Right -->
    <div class="hidden md:block fixed bottom-6 {{ \App\Helpers\LocaleHelper::isRtl() ? 'left-6' : 'right-6' }} z-50 w-96 transform transition-all duration-500"
         x-bind:class="show ? 'translate-y-0 opacity-100' : 'translate-y-full opacity-0'">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-primary-500 to-primary-600 text-white p-6">
                <button @click="close()" class="absolute top-4 {{ \App\Helpers\LocaleHelper::isRtl() ? 'left-4' : 'right-4' }} text-white/80 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                
                <h3 class="text-2xl font-bold mb-2">🎁 {{ __('Special Gift!') }}</h3>
                <p class="text-white/90">{{ __('Get a free Kurdish educational PDF') }}</p>
            </div>
            
            <form @submit.prevent="subscribe()" class="p-6 space-y-4">
                <input 
                    type="email" 
                    x-model="email"
                    placeholder="{{ __('Your email address') }}"
                    required
                    class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-primary-500 focus:outline-none transition-colors">
                
                <label class="flex items-start gap-2 text-xs text-gray-600">
                    <input type="checkbox" required class="mt-1">
                    <span>{{ __('I accept the') }} <a href="#" class="text-primary-600 underline">{{ __('Privacy Policy') }}</a> {{ __('and agree to receive newsletters') }}</span>
                </label>
                
                <button 
                    type="submit"
                    class="w-full py-3 bg-gradient-to-r from-primary-500 to-primary-600 text-white font-bold rounded-lg hover:from-primary-600 hover:to-primary-700 transition-all">
                    {{ __('Get Free PDF') }}
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function subscriptionBanner() {
    return {
        show: false,
        email: '',
        
        init() {
            // Check if popup was closed in last 30 days
            const closedAt = localStorage.getItem('zaro_sub_popup');
            if (closedAt) {
                const daysSinceClosed = (Date.now() - parseInt(closedAt)) / (1000 * 60 * 60 * 24);
                if (daysSinceClosed < 30) {
                    return; // Don't show
                }
            }
            
            // Show after 10 seconds
            setTimeout(() => {
                this.show = true;
            }, 10000);
        },
        
        close() {
            this.show = false;
            localStorage.setItem('zaro_sub_popup', Date.now().toString());
        },
        
        subscribe() {
            // Send subscription request
            fetch('{{ url(app()->getLocale() . "/subscribe") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    email: this.email
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('{{ __('Thank you! Check your email for the free PDF.') }}');
                    this.close();
                } else {
                    alert(data.message || '{{ __('Subscription failed. Please try again.') }}');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('{{ __('An error occurred. Please try again.') }}');
            });
        }
    }
}
</script>
@endpush
