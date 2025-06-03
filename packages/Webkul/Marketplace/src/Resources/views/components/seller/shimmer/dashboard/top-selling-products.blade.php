<div class="grid gap-4 rounded-xl border p-7">
    <div class="flex items-center justify-between">
        <div class="shimmer h-8 w-44"></div>

        <div class="shimmer h-11 w-24 rounded-xl"></div>
    </div>

    @foreach (range(1, 5) as $i)
        <div class="grid grid-cols-2">
            <div class="lex flex-col flex-1 gap-1">
                <div class="shimmer h-5 w-48"></div>

                <div class="shimmer h-5 w-48"></div>
                
                <div class="shimmer h-5 w-48"></div>
            </div>

            <div class="flex gap-4">
                <div class="shimmer h-15 w-15 rounded-xl"></div>

                <div class="flex flex-col gap-1">
                    <div class="shimmer h-6 w-16"></div>

                    <div class="shimmer h-6 w-16"></div>
                </div>

                <div class="shimmer h-6 w-6 rounded-md place-self-center"></div>
            </div>
        </div>
    @endforeach
</div>