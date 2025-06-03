{!! view_render_event('bagisto.seller.product.edit.form.channel.before', ['product' => $product]) !!}

@if (! $product->parent_id)
    @if ($channels->count() == 1)
        <input type="hidden" name="channels[]" value="{{ $channels->first()->id }}">
    @else
        <!-- Panel -->
        <div class="rounded-xl border bg-white p-4">
            <!-- Panel Header -->
            <p class="mb-4 flex justify-between text-base font-semibold text-gray-800">
                @lang('marketplace::app.seller.products.edit.channels')
            </p>

            <!-- Panel Content -->
            <div class="text-sm text-gray-600">
                @php
                    $selectedChannelsId = old('channels') ?? $product->channels->pluck('id')->toArray()
                @endphp
                
                @foreach (core()->getAllChannels() as $channel)
                    <x-marketplace::seller.form.control-group class="!mb-2 flex items-center gap-2.5 last:!mb-0">
                        <x-marketplace::seller.form.control-group.control
                            type="checkbox"
                            :id="'channels_' . $channel->id" 
                            name="channels[]"
                            rules="required"
                            :value="$channel->id"
                            :for="'channels_' . $channel->id" 
                            :label="trans('marketplace::app.seller.products.edit.channels')"
                            :checked="in_array($channel->id, $product->channels->pluck('id')->toArray())"
                        />

                        <label
                            class="cursor-pointer text-sm text-gray-600 hover:text-gray-800"
                            for="channels_{{ $channel->id }}"
                        >
                            {{ $channel->name }} 
                        </label>
                    </x-marketplace::seller.form.control-group>
                @endforeach

                <x-marketplace::seller.form.control-group.error control-name="channels[]" />
            </div>
        </div>
    @endif
@endif

{!! view_render_event('bagisto.seller.product.edit.form.channel.after', ['product' => $product]) !!}
