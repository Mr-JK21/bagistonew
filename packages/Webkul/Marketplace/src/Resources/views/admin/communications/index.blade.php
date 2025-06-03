<x-admin::layouts>
    <x-slot:title>
        @lang('marketplace::app.admin.communications.index.title')
    </x-slot:title>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="py-2.5 text-xl font-bold text-gray-800 dark:text-white">
            @lang('marketplace::app.admin.communications.index.title')
        </p>
    </div>

    <div class="box-shadow mt-3.5 rounded border bg-white">
        <v-communication></v-communication>
    </div>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-communication-template"
        >
            <div class="flex h-[calc(100vh-148px)]">
                <!-- Sidebar -->
                <div class="w-80 border-r bg-white dark:border-gray-700 dark:bg-gray-900">
                    <div class="flex flex-col gap-2.5 border-b px-4 pb-2 pt-4 dark:border-gray-700">
                        <div class="flex gap-x-1.5 items-center">
                            <span class="text-base font-semibold leading-5 text-gray-600 dark:text-gray-300">
                                @lang('marketplace::app.admin.communications.index.sellers')
                            </span>
    
                            <span 
                                v-if="unreadSellerCount"
                                class="flex items-center justify-center h-5 min-w-5 rounded-full bg-blue-600 text-white text-xs font-medium leading-3"
                            >
                                @{{ unreadSellerCount }}
                            </span>
                        </div>
            
                        <div class="relative flex items-center">
                            <i class="icon-search absolute top-1.5 flex items-center text-2xl leading-6 text-gray-600 dark:text-gray-300 ltr:left-3 rtl:right-3"></i>
            
                            <input 
                                type="text" 
                                class="block h-9 w-full rounded-md border px-10 py-1 leading-6 text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                                placeholder="@lang('marketplace::app.admin.communications.index.search')"
                                v-model="searchQuery.seller"
                            >
                        </div>
                    </div>
            
                    <!-- Seller List -->
                    <div class="journal-scroll max-h-[calc(100vh-248px)] overflow-y-auto p-2">
                        <div 
                            v-for="(seller, index) in sellers" 
                            :key="index"
                            class="flex cursor-pointer items-center gap-x-2.5 rounded-lg p-2 hover:bg-gray-100 dark:hover:bg-gray-700"
                            :class="[currentSeller && currentSeller.id === seller.id ? 'bg-gray-200 dark:bg-gray-600' : '']"
                            @click="currentSeller = seller"
                        >
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
            
                            <div class="min-w-0 flex-1">
                                <div class="flex items-baseline justify-between">
                                    <span class="max-w-[120px] truncate text-sm font-medium leading-5 text-gray-600 dark:text-gray-300">
                                        @{{ seller.name }}
                                    </span>
            
                                    <span
                                        class="flex-shrink-0 text-sm font-medium leading-5"
                                        :class="[seller.communication?.admin_unread_count ? 'text-blue-600' : 'text-gray-500 dark:text-gray-400']"
                                    >
                                        @{{ seller.communication?.timestamp }}
                                    </span>
                                </div>
            
                                <div class="flex items-center justify-between gap-x-1.5">
                                    <p class="truncate text-sm font-normal leading-4 text-gray-600 dark:text-gray-300">
                                        @{{ seller.communication?.subject }}
                                    </p>
            
                                    <span 
                                        v-if="seller.communication?.admin_unread_count"
                                        class="flex items-center justify-center h-5 min-w-5 rounded-full bg-blue-600 text-white text-xs font-medium leading-3"
                                    >
                                        @{{ seller.communication.admin_unread_count }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
                <template v-if="currentSeller">
                    <!-- Main Chat Area -->
                    <div class="flex flex-1 flex-col">
                        <!-- Chat Header -->
                        <div class="flex items-center justify-between border-b bg-white px-4 py-2 dark:border-gray-700 dark:bg-gray-900">
                            <div class="flex items-center gap-x-2.5">
                                <template v-if="currentSeller.logo_url">
                                    <img
                                        :src="currentSeller.logo_url"
                                        class="h-10 w-10 flex-shrink-0 rounded-full"
                                    >
                                </template>
            
                                <template v-else>
                                    <div class="flex h-10 min-w-10 flex-shrink-0 items-center justify-center rounded-full border bg-blue-400 font-medium text-white hover:bg-blue-500">
                                        @{{ currentSeller.name[0] }}
                                    </div>
                                </template>
            
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium leading-5 text-gray-600 dark:text-gray-200">
                                        @{{ currentSeller.name }}
                                    </span>
            
                                    <p class="text-sm font-normal leading-4 text-gray-500 dark:text-gray-300">
                                        @{{ currentSeller.email }}
                                    </p>
                                </div>
                            </div>
            
                            <div class="flex gap-x-2.5">
                                <div class="relative flex w-[200px] max-w-[200px] items-center">
                                    <i class="icon-search absolute top-1.5 flex items-center text-2xl leading-6 text-gray-600 dark:text-gray-300 ltr:left-3 rtl:right-3"></i>
            
                                    <input 
                                        type="text" 
                                        class="block h-9 w-full rounded-md border px-10 py-1 leading-6 text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                                        placeholder="@lang('marketplace::app.admin.communications.index.search-message')"
                                        @input="searchMessage($event)"
                                    >
                                </div>
            
                                <div class="flex">
                                    <!-- Dropdown -->
                                    <x-admin::dropdown position="bottom-{{ core()->getCurrentLocale()->direction === 'ltr' ? 'right' : 'left' }}">
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
                                                    class="cursor-pointer px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700"
                                                    @click="getMessages(key)"
                                                >
                                                    <span class="text-sm text-gray-600 dark:text-gray-300">
                                                        @{{ value }}
                                                    </span>
                                                </div>
                                            </div>
                                        </x-slot>
                                    </x-admin::dropdown>

                                    <!-- Dropdown -->
                                    <x-admin::dropdown position="bottom-{{ core()->getCurrentLocale()->direction === 'ltr' ? 'right' : 'left' }}">
                                        <x-slot:toggle>
                                            <div class="flex h-9 w-9 items-center justify-center hover:rounded-md hover:bg-gray-100 hover:dark:bg-gray-700">
                                                <i class="icon-dots rotate-90 text-2xl text-gray-500 dark:text-white"></i>
                                            </div>
                                        </x-slot>
            
                                        <x-slot:content class="!p-0">
                                            <div class="flex flex-col gap-1 py-2">
                                                <template v-if="currentSeller?.communication">
                                                    <template v-if="currentSeller?.communication?.is_blocked">
                                                        <div
                                                            class="cursor-pointer px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700"
                                                            @click="handleAction('unblock')"
                                                        >
                                                            <span class="text-sm text-gray-600 dark:text-gray-300">
                                                                @lang('marketplace::app.admin.communications.index.unblock-seller')
                                                            </span>
                                                        </div>
                                                    </template>
    
                                                    <template v-else>
                                                        <div
                                                            class="cursor-pointer px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700"
                                                            @click="handleAction('block')"
                                                        >
                                                            <span class="text-sm text-gray-600 dark:text-gray-300">
                                                                @lang('marketplace::app.admin.communications.index.block-seller')
                                                            </span>
                                                        </div>
                                                    </template>
                                                </template>

                                                <div
                                                    class="cursor-pointer px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700"
                                                    @click="clearChatHistory"
                                                >
                                                    <span class="text-sm text-gray-600 dark:text-gray-300">
                                                        @lang('marketplace::app.admin.communications.index.clear-chat-history')
                                                    </span>
                                                </div>

                                                <div
                                                    class="cursor-pointer px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700"
                                                    @click="currentSeller = null"
                                                >
                                                    <span class="text-sm text-gray-600 dark:text-gray-300">
                                                        @lang('marketplace::app.admin.communications.index.exit')
                                                    </span>
                                                </div>
                                            </div>
                                        </x-slot>
                                    </x-admin::dropdown>
                                </div>
                            </div>
                        </div>
            
                        <!-- Chat Messages -->
                        <div
                            class="journal-scroll flex flex-1 flex-col gap-y-2.5 overflow-y-auto bg-white p-4 dark:bg-gray-900"
                            ref="chatMessages"
                        >
                            <div
                                v-for="(message, messageIndex) in messages"
                                :key="message.id"
                            >
                                <!-- Seller Message -->
                                <div
                                    v-if="message.sender_type === 'seller'"
                                    class="mb-4 flex gap-x-4"
                                >
                                    <template v-if="currentSeller.logo_url">
                                        <img
                                            :src="currentSeller.logo_url"
                                            class="h-10 w-10 flex-shrink-0 rounded-full"
                                        >
                                    </template>
            
                                    <template v-else>
                                        <div class="flex h-10 min-w-10 flex-shrink-0 items-center justify-center rounded-full border bg-blue-400 font-medium text-white hover:bg-blue-500">
                                            @{{ currentSeller.name[0] }}
                                        </div>
                                    </template>
            
                                    <div class="flex flex-col gap-y-1">            
                                        <div class="flex max-w-lg flex-col gap-y-2.5 rounded-r-2xl rounded-bl-2xl bg-gray-100 p-4 dark:bg-gray-700 dark:text-gray-300">        
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
                                                :class="[highlightedIndex === messageIndex ? 'bg-yellow-200 dark:text-gray-800' : '']"
                                            >
                                                @{{ message.text }}
                                            </p>
                                        </div>

                                        <p class="text-xs text-gray-600 dark:text-gray-400">
                                            @{{ message.timestamp }}
                                        </p>
                                    </div>
                                </div>
            
                                <!-- Support Message -->
                                <div
                                    v-else
                                    class="mb-4 flex justify-end gap-x-4"
                                >
                                    <div class="flex flex-col items-end gap-y-1">            
                                        <div class="flex max-w-lg flex-col gap-y-2.5 rounded-l-2xl rounded-br-2xl bg-gray-100 p-4 dark:bg-gray-700 dark:text-gray-300 items-end">        
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
                                                :class="[highlightedIndex === messageIndex ? 'bg-yellow-200 dark:text-gray-800' : '']"
                                            >
                                                @{{ message.text }}
                                            </p>
                                        </div>

                                        <p class="text-xs text-gray-600 dark:text-gray-400">
                                            @{{ message.timestamp }}
                                        </p>
                                    </div>
            
                                    <template v-if="admin.image_url">
                                        <img
                                            :src="admin.image_url"
                                            class="h-10 w-10 flex-shrink-0 rounded-full"
                                        >
                                    </template>
            
                                    <template v-else>
                                        <div class="flex h-10 min-w-10 flex-shrink-0 items-center justify-center rounded-full border bg-blue-400 font-medium text-white hover:bg-blue-500">
                                            @{{ admin.name[0] }}
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
            
                        <template v-if="currentSeller.communication?.is_blocked">
                            <div class="flex items-center justify-center bg-gray-100 dark:bg-gray-700 py-2">
                                <p class="text-gray-600 dark:text-gray-300">
                                    @lang('marketplace::app.admin.communications.index.seller-blocked')
                                </p>
                            </div>
                        </template>

                        <template v-else>
                            <!-- Chat Input -->
                            <x-admin::form
                                v-slot="{ meta, errors, handleSubmit }"
                                as="div"
                            >
                                <template v-if="uploadedFile">
                                    <div class="flex items-center justify-between gap-4 border-t border-gray-200 px-4 py-2 dark:border-gray-700 dark:bg-gray-800">
                                        <span class="truncate text-sm font-medium text-gray-800 dark:text-gray-200">
                                            @{{ uploadedFile.name }}
                                        </span>
                                
                                        <div 
                                            class="flex h-6 w-6 cursor-pointer items-center justify-center rounded-full bg-red-500"
                                            @click="removeFile"
                                            aria-label="Remove file"
                                        >
                                            <i class="icon-cross text-base leading-4 text-white"></i>
                                        </div>
                                    </div>
                                </template>

                                <form
                                    @submit="handleSubmit($event, sendMessage)"
                                    ref="communicationForm"
                                    class="flex items-center gap-4 border-t bg-white px-4 py-2 dark:border-gray-700 dark:bg-gray-900"
                                >
                                    <v-field
                                        type="text"
                                        name="message"
                                        v-slot="{ field }"
                                    >
                                        <input
                                            type="text"
                                            class="flex-1 text-sm text-gray-600 dark:bg-gray-900 dark:text-gray-300"
                                            name="message"
                                            v-bind="field"
                                            placeholder="@lang('marketplace::app.admin.communications.index.type-message')"
                                        />
                                    </v-field>
                    
                                    <div class="flex items-center gap-2">
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
                                            <i class="mp-attach-icon text-2xl leading-6 text-gray-500 dark:text-gray-300"></i>
                                        </div>

                                        <template v-if="sendingMessage">
                                            <div class="flex items-center justify-center">
                                                <div class="h-5 w-5 animate-spin rounded-full border-4 border-solid border-blue-500 border-r-transparent motion-reduce:animate-[spin_1.5s_linear_infinite]">
                                                </div>
                                            </div>
                                        </template>

                                        <template v-else>
                                            <button
                                                type="submit"
                                                class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-600"
                                            >
                                                <i class="mp-reply-icon text-2xl leading-6 text-white"></i>
                                            </button>
                                        </template>
                                    </div>
                                </form>
                            </x-admin::form>
                        </template>
                    </div>
                </template>
            
                <template v-else>
                    <div class="flex flex-col gap-y-2.5 flex-1 items-center justify-center bg-white dark:bg-gray-900">
                        <img
                            src="{{ bagisto_asset('images/chat.png', 'marketplace') }}"
                            class="h-40 w-40 dark:mix-blend-exclusion dark:invert"
                        >

                        <p class="font-normal text-gray-600 dark:text-gray-300">
                            @lang('marketplace::app.admin.communications.index.welcome-message')
                        </p>
                    </div>
                </template>
            </div>        
        </script>

        <script type="module">
            app.component('v-communication', {

                template: '#v-communication-template',

                data() {
                    return {
                        sellers: [],

                        currentSeller: null,

                        sendingMessage: false,

                        searchQuery: {
                            seller: '',
                        },

                        uploadedFile: null,

                        highlightedIndex: -1,

                        filters: {
                            'prev-seven-days': "@lang('marketplace::app.admin.communications.index.filters.prev-seven-days')",

                            'prev-fifteen-days': "@lang('marketplace::app.admin.communications.index.filters.prev-fifteen-days')",

                            'prev-thirty-days': "@lang('marketplace::app.admin.communications.index.filters.prev-thirty-days')",

                            'prev-six-months': "@lang('marketplace::app.admin.communications.index.filters.prev-six-months')",

                            'all': "@lang('marketplace::app.admin.communications.index.filters.all-messages')",
                        },

                        admin: @json(auth()->guard('admin')->user()),
                    }
                },

                computed: {
                    messages() {
                        if (this.currentSeller) {
                            return this.currentSeller?.communication?.messages || [];
                        }

                        return [];
                    },

                    unreadSellerCount() {
                        return this.sellers.reduce((count, seller) => {
                            return count + (seller.communication?.admin_unread_count ? 1 : 0);
                        }, 0);
                    }
                },

                mounted() {
                    this.getSellers();
                },

                watch: {
                    'searchQuery.seller': function (value) {
                        this.getSellers();
                    },

                    currentSeller: function (value) {
                        this.markAsRead();

                        this.scrollToBottom();
                    }
                },

                methods: {
                    sendMessage(params, { resetForm, setValues }) {
                        if (! this.currentSeller) {
                            return;
                        }

                        if (
                            ! params.message?.trim()
                            && ! this.uploadedFile
                        ) {
                            return;
                        }

                        this.sendingMessage = true;

                        let formData = this.$refs.communicationForm;

                        this.$axios.post(`{{ route('admin.marketplace.communications.send_message', '') }}/${this.currentSeller.id}`, formData)
                            .then(response => {
                                this.currentSeller.communication = response.data.communication;

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

                    getSellers() {
                        this.$axios.get("{{route('admin.marketplace.communications.index')}}", {
                            params: { query: this.searchQuery.seller.trim() }
                        })
                            .then(response => {
                                this.sellers = response.data.data;
                            })
                            .catch(error => {
                                console.log(error);
                            });
                    },

                    markAsRead() {
                        if (
                            this.currentSeller
                            && this.currentSeller.communication?.admin_unread_count
                        ) {
                            this.$axios.patch(`{{ route('admin.marketplace.communications.mark_as_read', '') }}/${this.currentSeller.id}`)
                                .then(response => {
                                    if (response.data.success) {
                                        this.currentSeller.communication = response.data.communication;
                                    }
                                })
                                .catch(error => {
                                    console.log(error);
                                });
                        }
                    },

                    clearChatHistory() {
                        if (this.currentSeller) {
                            this.$axios.delete(`{{ route('admin.marketplace.communications.clear_chat', '') }}/${this.currentSeller.id}`)
                                .then(response => {
                                    if (response.data.success) {
                                        this.currentSeller.communication = response.data.communication;

                                        this.$emitter.emit('add-flash', {
                                            type: 'success',
                                            message: response.data.message
                                        });
                                    }
                                })
                                .catch(error => {
                                    console.log(error);
                                });
                        }
                    },

                    handleAction(action) {
                        if (this.currentSeller?.communication) {
                            this.$axios.post(`{{ route('admin.marketplace.communications.handle_action', '') }}/${this.currentSeller.communication.id}`, { action })
                                .then(response => {
                                    if (response.data.success) {
                                        this.$emitter.emit('add-flash', {
                                            type: 'success',
                                            message: response.data.message
                                        });

                                        if (action === 'block') {
                                            this.currentSeller.communication.is_blocked = true;
                                        } else {
                                            this.currentSeller.communication.is_blocked = false;
                                        }
                                    }
                                })
                                .catch(error => {
                                    console.log(error);
                                });
                        }
                    },

                    getMessages(filter) {
                        if (this.currentSeller) {
                            this.$axios.get(`{{ route('admin.marketplace.communications.messages', '') }}/${this.currentSeller.id}`, {
                                params: { days: filter }
                            })
                                .then(response => {
                                    this.currentSeller.communication.messages = response.data.messages;
                                })
                                .catch(error => {
                                    console.log(error);
                                });
                        }
                    },

                    searchMessage(event) {
                        if (this.currentSeller) {
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
                        }
                    }
                }
            });
        </script>
    @endPushOnce
</x-admin::layouts>
