@push('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Show birthday popup (can be triggered based on user data or special dates)
function showBirthdayPopup() {
    Swal.fire({
        title: '🎉 {{ __('Happy Birthday!') }}',
        html: `
            <div class="text-center">
                <div class="text-6xl mb-4">🎂</div>
                <p class="text-lg text-gray-700 mb-4">{{ __('Special birthday discount just for you!') }}</p>
                <p class="text-3xl font-bold text-primary-600 mb-4">20% {{ __('OFF') }}</p>
                <p class="text-sm text-gray-600">{{ __('Use code:') }} <strong class="text-primary-600">BIRTHDAY20</strong></p>
            </div>
        `,
        icon: false,
        showCloseButton: true,
        confirmButtonText: '{{ __('Start Shopping') }}',
        confirmButtonColor: '#f18f1e',
        customClass: {
            popup: 'rounded-3xl',
            confirmButton: 'rounded-lg px-8 py-3 font-bold'
        },
        backdrop: `
            rgba(0,0,0,0.4)
            url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100'%3E%3Ctext x='50%25' y='50%25' font-size='50' text-anchor='middle' dominant-baseline='middle'%3E🎈%3C/text%3E%3C/svg%3E")
            left top
            no-repeat
        `
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '{{ url(app()->getLocale() . "/products") }}';
        }
    });
}

// Trigger birthday popup if user's birthday (example: check user data or special date)
// showBirthdayPopup();
</script>
@endpush
