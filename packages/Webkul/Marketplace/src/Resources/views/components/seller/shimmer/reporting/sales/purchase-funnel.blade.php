<!-- Purchase Funnel Section -->
<div class="relative border rounded-xl bg-white py-5 px-[30px]">
    <!-- Header -->
    <div class="shimmer mb-4 h-7 w-40"></div>

    <div class="grid grid-cols-4 gap-6">
        @foreach (range(1, 4) as $i)
            <div class="flex flex-col gap-4">
                <div class="grid gap-0.5">
                    <div class="shimmer h-5 w-10"></div>
                    <div class="shimmer h-3.5 w-20"></div>
                </div>

                <div class="shimmer relative aspect-[0.5/1] w-full rounded-lg"></div>

                <div class="shimmer h-5 w-full"></div>
            </div>
        @endforeach
    </div>

    <div class="mt-6 flex justify-center gap-5">
        <div class="flex items-center gap-1">
            <div class="shimmer h-3.5 w-3.5 rounded-md"></div>
            <div class="shimmer h-[18px] w-[143px]"></div>
        </div>
    </div>
</div>