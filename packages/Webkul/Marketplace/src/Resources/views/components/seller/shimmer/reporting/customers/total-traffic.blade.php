<!-- Total Traffic Shimmer -->
<div class="relative border rounded-xl bg-white py-6 px-[30px]">
    <!-- Header -->
    <div class="shimmer h-7 w-40 mb-4"></div>

    <div class="grid gap-4">
        <div class="flex justify-between gap-4">
            <div class="grid gap-1">
                <div class="flex items-center gap-1">
                    <div class="shimmer h-7 w-20"></div>
                    <div class="shimmer h-5 w-12"></div>
                </div>

                <div class="shimmer h-3.5 w-24"></div>
            </div>

            <div class="grid gap-1">
                <div class="flex items-center gap-1">
                    <div class="shimmer h-7 w-20"></div>
                    <div class="shimmer h-5 w-12"></div>
                </div>

                <div class="shimmer h-3.5 w-24"></div>
            </div>
        </div>

        <div class="shimmer h-5 w-[150px]"></div>

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