<!-- Total Sales Shimmer -->
<div class="relative border rounded-xl bg-white py-6 px-[30px]">
    <!-- Header -->
    <div class="mb-4 flex items-center justify-between">
        <div class="shimmer h-7 w-28"></div>

        <div class="shimmer h-6 w-24"></div>
    </div>

    <div class="grid gap-4">
        <div class="flex place-content-start gap-4">
            <div class="flex items-center gap-4">
                <div class="shimmer h-7 w-24"></div>
                <div class="shimmer h-5 w-10"></div>
            </div>
        </div>

        <div class="shimmer h-5 w-28"></div>

        <!-- Graph Shimmer -->
        <x-marketplace::seller.shimmer.reporting.graph />

        <!-- Date Range -->
        <div class="flex justify-center gap-5">
            <div class="flex items-center gap-1">
                <div class="shimmer h-3.5 w-3.5 rounded-md"></div>
                <div class="shimmer h-[17px] w-[143px]"></div>
            </div>
            
            <div class="flex items-center gap-1">
                <div class="shimmer h-3.5 w-3.5 rounded-md"></div>
                <div class="shimmer h-[17px] w-[143px]"></div>
            </div>
        </div>
    </div>
</div>