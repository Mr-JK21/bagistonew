@props([
    'name'              => 'documents',
    'accepts'           => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'csv'],
    'allowMultiple'     => false,
    'showPlaceholders'  => false,
    'uploadedDocuments' => [],
    'width'             => '120px',
    'height'            => '120px',
])

<v-media-documents
    name="{{ $name }}"
    :accepts='{{ json_encode($accepts) }}'
    v-bind:allow-multiple="{{ $allowMultiple ? 'true' : 'false' }}"
    v-bind:show-placeholders="{{ $showPlaceholders ? 'true' : 'false' }}"
    :uploaded-documents='{{ json_encode($uploadedDocuments) }}'
    width="{{ $width }}"
    height="{{ $height }}"
    :errors="errors"
>
    <x-marketplace::seller.shimmer.media class="h-[120px] w-[200px]"/>
</v-media-documents>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-media-documents-template"
    >
        <div class="grid">
            <div class="flex flex-wrap gap-1">
                <!-- Upload Document Button -->
                <label
                    class="grid h-[120px] max-h-[120px] w-full min-w-[200px] max-w-[200px] cursor-pointer items-center justify-items-center rounded border border-dashed border-gray-300 transition-all hover:border-gray-400"
                    :style="{'max-width': width, 'max-height': height}"
                    :for="$.uid + '_documentInput'"
                    v-if="allowMultiple || documents.length == 0"
                >
                    <div class="flex flex-col items-center">
                        <span class="mp-receive-orders-icon text-2xl"></span>

                        <p class="grid text-center text-sm font-semibold text-gray-800">
                            @lang('marketplace::app.components.seller.media.documents.add-document-btn')
                            
                            <span class="text-xs">
                                @lang('marketplace::app.components.seller.media.documents.allowed-types', [
                                    'types' => implode(', ', $accepts)
                                ])
                            </span>
                        </p>

                        <input
                            type="file"
                            class="hidden"
                            :id="$.uid + '_documentInput'"
                            accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.csv"
                            :multiple="allowMultiple"
                            :ref="$.uid + '_documentInput'"
                            @change="add"
                        />
                    </div>
                </label>

                <!-- Uploaded Documents -->
                <draggable
                    class="flex flex-wrap gap-1"
                    ghost-class="draggable-ghost"
                    v-bind="{animation: 200}"
                    :list="documents"
                    item-key="id"
                >
                    <template #item="{ element, index }">
                        <v-media-document-item
                            :name="name"
                            :index="index"
                            :document="element"
                            :width="width"
                            :height="height"
                            :valid-mime-types="validMimeTypes"
                            @onRemove="remove($event)"
                        ></v-media-document-item>
                    </template>
                </draggable>

                <!-- Placeholders -->
                <template v-if="showPlaceholders && !documents.length">
                    <div
                        class="relative h-[120px] max-h-[120px] w-full min-w-[120px] max-w-[120px] rounded border border-dashed border-gray-300"
                        v-for="placeholder in placeholders"
                    >
                        <img :src="placeholder.image">

                        <p class="absolute bottom-4 w-full text-center text-xs font-semibold text-gray-400">
                            @{{ placeholder.label }}
                        </p>
                    </div>
                </template>
            </div>
        </div>
    </script>

    <script
        type="text/x-template"
        id="v-media-document-item-template"
    >
        <div class="group relative grid h-[120px] max-h-[120px] min-w-[200px] max-w-[200px] justify-items-center overflow-hidden rounded border border-gray-300 transition-all hover:border-gray-400">
            <!-- Document Preview -->
            <div class="flex flex-col gap-4 items-center justify-between w-full p-4 text-sm bg-gray-100 border rounded-lg shadow-sm">
                <span class="text-gray-700 font-semibold break-all">@{{ document.name }}</span>
    
                <div class="flex justify-between w-full">
                    <span
                        class="mp-delete-icon cursor-pointer rounded-md p-2 text-xl hover:bg-gray-200"
                        @click="remove"
                    ></span>

                    <label
                        class="mp-upload-icon cursor-pointer rounded-md p-1.5 text-2xl hover:bg-gray-200"
                        :for="$.uid + '_documentInput' + index"
                    ></label>

                    <input
                        type="hidden"
                        :name="name + '[' + document.id + ']'"
                        v-if="! document.is_new"
                    />

                    <input
                        type="file"
                        :name="name + '[]'"
                        class="hidden"
                        accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.csv"
                        :id="$.uid + '_documentInput' + index"
                        :ref="$.uid + '_documentInput' + index"
                        @change="edit"
                    />
                </div>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-media-documents', {
            template: '#v-media-documents-template',

            props: {
                name: {
                    type: String, 
                    default: 'documents',
                },

                accepts: {
                    type: Array,
                    default: () => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'csv']
                },

                allowMultiple: {
                    type: Boolean,
                    default: false,
                },

                showPlaceholders: {
                    type: Boolean,
                    default: false,
                },

                uploadedDocuments: {
                    type: Array,
                    default: () => []
                },

                width: {
                    type: String,
                    default: '120px'
                },

                height: {
                    type: String,
                    default: '120px'
                },

                errors: {
                    type: Object,
                    default: () => {}
                }
            },

            data() {
                return {
                    documents: [],

                    placeholders: [
                        {
                            label: "@lang('marketplace::app.components.seller.media.placeholders.pdf')",
                            image: "{{ bagisto_asset('images/documents-placeholders/pdf.svg', 'marketplace') }}"
                        }, {
                            label: "@lang('marketplace::app.components.seller.media.placeholders.word')",
                            image: "{{ bagisto_asset('images/documents-placeholders/word.svg', 'marketplace') }}"
                        }, {
                            label: "@lang('marketplace::app.components.seller.media.placeholders.excel')",
                            image: "{{ bagisto_asset('images/documents-placeholders/excel.svg', 'marketplace') }}"
                        }, {
                            label: "@lang('marketplace::app.components.seller.media.placeholders.powerpoint')",
                            image: "{{ bagisto_asset('images/documents-placeholders/powerpoint.svg', 'marketplace') }}"
                        }, {
                            label: "@lang('marketplace::app.components.seller.media.placeholders.text')",
                            image: "{{ bagisto_asset('images/documents-placeholders/text.svg', 'marketplace') }}"
                        }, {
                            label: "@lang('marketplace::app.components.seller.media.placeholders.csv')",
                            image: "{{ bagisto_asset('images/documents-placeholders/csv.svg', 'marketplace') }}"
                        }
                    ],

                    allMimeTypes: {
                        pdf: 'application/pdf',
                        doc: 'application/msword',
                        docx: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        xls: 'application/vnd.ms-excel',
                        xlsx: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        ppt: 'application/vnd.ms-powerpoint',
                        pptx: 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                        txt: 'text/plain',
                        csv: 'text/csv',
                    }
                }
            },

            mounted() {
                this.documents = this.uploadedDocuments;                
            },

            computed: {
                validMimeTypes() {
                    return this.accepts.map(type => this.allMimeTypes[type]);
                }
            },

            methods: {
                add() {
                    let documentInput = this.$refs[this.$.uid + '_documentInput'];

                    if (! documentInput.files) {
                        return;
                    }

                    const validFiles = Array.from(documentInput.files).every(file =>
                        this.validMimeTypes.includes(file.type)
                    );

                    if (! validFiles) {
                        this.$emitter.emit('add-flash', {
                            type: 'warning',
                            message: "@lang('marketplace::app.components.seller.media.documents.not-allowed-error')".replace(':types', this.accepts.join(', '))
                        });
                        return;
                    }

                    Array.from(documentInput.files).forEach(file => {
                        this.documents.push({
                            id: 'document_' + this.documents.length,
                            name: file.name,
                            file: file
                        });
                    });
                },

                remove(document) {
                    let index = this.documents.indexOf(document);
                    this.documents.splice(index, 1);
                },
            }
        });

        app.component('v-media-document-item', {
            template: '#v-media-document-item-template',

            props: ['index', 'document', 'name', 'width', 'height', 'validMimeTypes'],

            methods: {
                edit() {
                    let documentInput = this.$refs[this.$.uid + '_documentInput' + this.index];

                    if (! documentInput.files) {
                        return;
                    }

                    const validFiles = Array.from(documentInput.files).every(file =>
                        this.validMimeTypes.includes(file.type)
                    );

                    if (! validFiles) {
                        this.$emitter.emit('add-flash', {
                            type: 'warning',
                            message: "@lang('marketplace::app.components.seller.media.documents.not-allowed-error')"
                        });
                        
                        return;
                    }

                    this.document.name = documentInput.files[0].name;
                    this.document.file = documentInput.files[0];
                },

                remove() {
                    this.$emit('onRemove', this.document);
                },
            }
        });
    </script>
@endPushOnce
