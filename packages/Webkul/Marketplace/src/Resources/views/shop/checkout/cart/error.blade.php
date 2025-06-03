@php
    $errors = Cart::getErrors();
@endphp

@if (
    ! empty($errors)
    && $errors['error_code'] === 'MARKETPLACE_MINIMUM_ORDER_AMOUNT'
)
    @foreach ($errors['errors'] as $error)
        <div class="mt-5 w-full gap-12 rounded-lg bg-[#FFF3CD] px-5 py-3 text-[#383D41] max-sm:px-3 max-sm:py-2 max-sm:text-sm">
            {{ $error['message'] }}: {{ $error['amount'] }}
        </div>
    @endforeach
@endif
