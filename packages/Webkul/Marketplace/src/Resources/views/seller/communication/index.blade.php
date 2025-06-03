<x-marketplace::seller.layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('marketplace::app.seller.communication.index.title')
    </x-slot>

    <!-- Breadcrumbs -->
    @section('breadcrumbs')
        <x-marketplace::seller.breadcrumbs name="communication" />
    @endSection

    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-medium">
            @lang('marketplace::app.seller.communication.index.title')
        </h2>
    </div>

    {!! view_render_event('bagisto.seller.communication.before') !!}

    <v-seller-communication></v-seller-communication>

    {!! view_render_event('bagisto.seller.communication.after') !!}

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-seller-communication-template"
        >
            <div class="flex flex-col border border-gray-200 rounded-lg h-[calc(100vh-210px)] mt-7">
                <div class="flex py-3 px-5 max-sm:px-3 bg-gray-100 justify-between gap-5 flex-wrap">
                    <div class="flex gap-x-4">
                        <img
                            src="{{ bagisto_asset('images/admin-logo.png', 'marketplace') }}"
                            class="w-10 h-10"
                            alt="Admin Logo"
                        />
    
                        <div class="flex flex-col gap-y-0.5">
                            <span class="text-base font-medium leading-5">
                                {{ config('app.name') }}
                            </span>
    
                            <span class="text-sm font-normal leading-4">
                                @lang('marketplace::app.seller.communication.index.admin')
                            </span>
                        </div>
                    </div>

                    <div class="flex gap-x-2.5 items-center">
                        <div class="relative flex items-center md:w-[370px] md:max-w-[370px] md:max-lg:w-[350px]">
                            <i class="icon-search absolute top-2 flex items-center text-2xl ltr:left-2 rtl:right-2"></i>
                    
                            <input 
                                type="text" 
                                class="block w-full rounded-lg border px-11 py-3 text-xs font-medium text-gray-900 transition-all hover:border-gray-400 focus:border-gray-400"
                                placeholder="@lang('marketplace::app.seller.communication.index.search-message')"
                                @input="searchMessage($event)"
                            >
                        </div>    
    
                        <div class="flex">
                            <!-- Dropdown -->
                            <x-shop::dropdown position="bottom-{{ core()->getCurrentLocale()->direction === 'ltr' ? 'right' : 'left' }}">
                                <x-slot:toggle>
                                    <div class="flex h-9 w-9 items-center justify-center hover:rounded-md hover:bg-gray-100 hover:dark:bg-gray-700">
                                        <i class="mp-history-icon text-2xl text-gray-500 dark:text-white"></i>
                                    </div>
                                </x-slot>
    
                                <x-slot:content class="!p-0">
                                    <div class="flex flex-col gap-1 py-2">
                                        <div 
                                            v-for="(value, key) in filters" 
                                            :key="key"
                                            class="cursor-pointer px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 hover:rounded-lg"
                                            @click="getMessages(key)"
                                        >
                                            <span class="text-sm text-gray-600 dark:text-gray-300">
                                                @{{ value }}
                                            </span>
                                        </div>
                                    </div>
                                </x-slot>
                            </x-shop::dropdown>
                        </div>
                    </div>
                </div>

                <!-- Chat Messages -->
                <div
                    class="journal-scroll flex flex-1 flex-col gap-y-2.5 overflow-y-auto bg-white p-5"
                    ref="chatMessages"
                >
                    <div
                        v-for="(message, messageIndex) in messages"
                        :key="message.id"
                    >
                        <!-- Admin Message -->
                        <div
                            v-if="message.sender_type === 'seller'"
                            class="mb-4 flex gap-x-4 justify-end"
                        >        
                            <div class="flex flex-col gap-y-1 items-end">            
                                <div class="flex max-w-lg flex-col gap-y-2.5 rounded-l-2xl rounded-br-2xl bg-gray-100 p-4 items-end">        
                                    <template v-if="message.attachment_url">
                                        <template v-if="message.attachment_type === 'image'">
                                            <img
                                                :src="message.attachment_url"
                                                class="h-20 w-20 rounded-lg object-contain"
                                            >
                                        </template>

                                        <template v-else>
                                            <img
                                                src="{{ bagisto_asset('images/file-icon.png', 'marketplace') }}"
                                                class="h-10 w-10 rounded-lg object-contain"
                                            >
                                        </template>
                                    </template>

                                    <a
                                        v-if="
                                            message.attachment_name
                                            && message.attachment_type != 'image'
                                        "
                                        :href="message.attachment_url"
                                        target="_blank"
                                        class="text-sm leading-4 underline text-blue-500"
                                    >
                                        @{{ message.attachment_name }}
                                    </a>

                                    <p
                                        v-if="message.text"
                                        class="chat-message text-sm leading-4"
                                        :class="[highlightedIndex === messageIndex ? 'bg-yellow-200' : '']"
                                    >
                                        @{{ message.text }}
                                    </p>
                                </div>

                                <p class="text-xs text-gray-600">
                                    @{{ message.timestamp }}
                                </p>
                            </div>

                            <template v-if="seller.logo_url">
                                <img
                                    :src="seller.logo_url"
                                    class="h-10 w-10 flex-shrink-0 rounded-full"
                                >
                            </template>

                            <template v-else>
                                <div class="flex h-10 min-w-10 flex-shrink-0 items-center justify-center rounded-full border bg-blue-400 font-medium text-white hover:bg-blue-500">
                                    @{{ seller.name[0] }}
                                </div>
                            </template>
                        </div>

                        <!-- Support Message -->
                        <div
                            v-else
                            class="mb-4 flex gap-x-4"
                        >
                            <img
                                src="{{ bagisto_asset('images/admin-logo.png', 'marketplace') }}"
                                class="w-10 h-10"
                                alt="Admin Logo"
                            />

                            <div class="flex flex-col gap-y-1">            
                                <div class="flex max-w-lg flex-col gap-y-2.5 rounded-r-2xl rounded-bl-2xl bg-gray-100 p-4">        
                                    <template v-if="message.attachment_url">
                                        <template v-if="message.attachment_type === 'image'">
                                            <img
                                                :src="message.attachment_url"
                                                class="h-20 w-20 rounded-lg object-contain"
                                            >
                                        </template>

                                        <template v-else>
                                            <img
                                                src="{{ bagisto_asset('images/file-icon.png', 'marketplace') }}"
                                                class="h-10 w-10 rounded-lg object-contain"
                                            >
                                        </template>
                                    </template>

                                    <a
                                        v-if="
                                            message.attachment_name
                                            && message.attachment_type != 'image'
                                        "
                                        :href="message.attachment_url"
                                        target="_blank"
                                        class="text-sm leading-4 underline text-blue-500"
                                    >
                                        @{{ message.attachment_name }}
                                    </a>

                                    <p
                                        v-if="message.text"
                                        class="chat-message text-sm leading-4"
                                        :class="[highlightedIndex === messageIndex ? 'bg-yellow-200' : '']"
                                    >
                                        @{{ message.text }}
                                    </p>
                                </div>

                                <p class="text-xs text-gray-600">
                                    @{{ message.timestamp }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($isBlocked)
                    <div class="flex items-center justify-center border-t border-gray-200 py-2">
                        <span class="text-sm font-medium text-red-500">
                            @lang('marketplace::app.seller.communication.index.blocked-message')
                        </span>
                    </div>
                @else
                    <!-- Chat Input -->
                    <x-shop::form
                        v-slot="{ meta, errors, handleSubmit }"
                        as="div"
                    >
                        <template v-if="uploadedFile">
                            <div class="flex items-center justify-between gap-4 border-t border-gray-200 px-4 py-2">
                                <span class="truncate text-sm font-medium text-gray-800">
                                    @{{ uploadedFile.name }}
                                </span>
                        
                                <div 
                                    class="flex h-6 w-6 cursor-pointer items-center justify-center rounded-full bg-red-500"
                                    @click="removeFile"
                                    aria-label="Remove file"
                                >
                                    <i class="icon-cancel text-base leading-4 text-white"></i>
                                </div>
                            </div>
                        </template>

                        <form
                            @submit="handleSubmit($event, sendMessage)"
                            ref="communicationForm"
                            class="flex items-center justify-between gap-4 border-t bg-white px-4 py-2 flex-nowrap"
                        >
                            <v-field
                                type="text"
                                name="message"
                                v-slot="{ field }"
                                class="flex-1"
                            >
                                <input
                                    type="text"
                                    class="w-full text-sm text-gray-600 focus:outline-none"
                                    name="message"
                                    v-bind="field"
                                    placeholder="@lang('marketplace::app.seller.communication.index.message-placeholder')"
                                />
                            </v-field>

                            <div class="flex items-center gap-2 flex-shrink-0">
                                <input
                                    type="file"
                                    name="attachment"
                                    @change="handleFileUpload"
                                    class="hidden"
                                    ref="fileInput"
                                />

                                <div
                                    class="flex h-10 w-10 cursor-pointer items-center justify-center"
                                    @click="triggerFileInput"
                                >
                                    <i class="mp-attach-icon text-2xl leading-6 text-gray-500"></i>
                                </div>

                                <template v-if="sendingMessage">
                                    <div class="flex items-center justify-center">
                                        <div class="h-5 w-5 animate-spin rounded-full border-4 border-solid border-green-500 border-r-transparent motion-reduce:animate-[spin_1.5s_linear_infinite]"></div>
                                    </div>
                                </template>

                                <template v-else>
                                    <button
                                        type="submit"
                                        class="flex h-10 w-10 items-center justify-center rounded-full bg-green-500"
                                    >
                                        <i class="mp-reply-icon text-2xl leading-6 text-white"></i>
                                    </button>
                                </template>
                            </div>
                        </form>
                    </x-shop::form>
                @endif
            </div>
        </script>

        <script type="module">
            app.component('v-seller-communication', {
                template: '#v-seller-communication-template',
    
                data() {
                    return {
                        messages: @json($messages),

                        sendingMessage: false,

                        uploadedFile: null,

                        highlightedIndex: -1,

                        filters: {
                            'prev-seven-days': "@lang('marketplace::app.seller.communication.index.filters.prev-seven-days')",

                            'prev-fifteen-days': "@lang('marketplace::app.seller.communication.index.filters.prev-fifteen-days')",

                            'prev-thirty-days': "@lang('marketplace::app.seller.communication.index.filters.prev-thirty-days')",

                            'prev-six-months': "@lang('marketplace::app.seller.communication.index.filters.prev-six-months')",

                            'all': "@lang('marketplace::app.seller.communication.index.filters.all-messages')",
                        },

                        seller: @json(seller()->user()),
                    }
                },

                mounted() {
                    this.scrollToBottom();
                },

                methods: {
                    sendMessage(params, { resetForm, setValues }) {
                        if (
                            ! params.message?.trim()
                            && ! this.uploadedFile
                        ) {
                            return;
                        }

                        this.sendingMessage = true;

                        let formData = this.$refs.communicationForm;

                        this.$axios.post("{{ route('seller.communication.send_message') }}", formData)
                            .then(response => {
                                this.messages.push(response.data.new_message);

                                this.scrollToBottom();

                                this.$emitter.emit('add-flash', {
                                    type: 'success',
                                    message: response.data.message
                                });
                            })
                            .catch(error => {                                
                                const errors = error.response.data?.errors; 

                                const message = errors 
                                    ? Object.values(errors).flat()[0] 
                                    : error.response.data.message;

                                this.$emitter.emit('add-flash', {
                                    type: 'error',
                                    message
                                });
                            })
                            .finally(() => {
                                resetForm();

                                this.removeFile();

                                this.sendingMessage = false;
                            });
                    },

                    triggerFileInput() {
                        this.$refs.fileInput.click();
                    },

                    handleFileUpload(event) {
                        const file = event.target.files[0];

                        if (file) {
                            this.uploadedFile = file;

                            this.scrollToBottom();
                        }
                    },

                    removeFile() {
                        this.uploadedFile = null;

                        this.$refs.fileInput.value = '';
                    },

                    scrollToBottom() {
                        this.$nextTick(() => {
                            const messages = this.$refs.chatMessages;

                            if (messages) {
                                messages.scrollTop = messages.scrollHeight;
                            }
                        });
                    },

                    searchMessage(event) {
                        this.highlightedIndex = -1;                            

                        let term = event.target.value.trim();

                        if (! term) {
                            return;
                        }

                        const chatMessages = document.querySelectorAll(".chat-message");

                        this.highlightedIndex = Array.from(chatMessages).findIndex((msg) =>
                            msg.textContent.toLowerCase().includes(term.toLowerCase())
                        );

                        if (this.highlightedIndex !== -1) {
                            chatMessages[this.highlightedIndex].scrollIntoView({
                                behavior: "smooth",
                                block: "center",
                            });
                        }
                    },

                    getMessages(filter) {
                        this.$axios.get(`{{ route('seller.communication.messages', '') }}`, {
                            params: { days: filter }
                        })
                            .then(response => {
                                this.messages = response.data.messages;
                            })
                            .catch(error => {
                                console.log(error);
                            });
                    },
                }
            });
        </script>
    @endpushOnce
</x-marketplace::seller.layouts>
