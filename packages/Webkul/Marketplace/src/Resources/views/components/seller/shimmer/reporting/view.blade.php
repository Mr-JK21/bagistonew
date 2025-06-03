<!-- Page Header -->
<div class="mb-5 flex items-center justify-between gap-4 max-sm:flex-wrap">
    <!-- Title -->
    <div class="grid gap-1.5">
        <div class="shimmer h-6 w-[150px]"></div>
    </div>

    <!-- Actions -->
    <div class="flex gap-2.5 items-center">
        <div class="shimmer h-8 w-8 rounded-lg"></div>

        @if (core()->getAllChannels()->count() > 1)
            <div class="shimmer h-11 w-36 rounded-lg"></div>
        @endif
        
        <div class="shimmer h-11 w-36 rounded-lg"></div>
        <div class="shimmer h-11 w-36 rounded-lg"></div>
        <div class="shimmer h-11 w-36 rounded-lg"></div>
    </div>
</div>

<div class="table-responsive box-shadow grid w-full overflow-hidden rounded bg-white border">
    <x-shop::shimmer.datagrid.table.head />

    <x-shop::shimmer.datagrid.table.body />
</div>