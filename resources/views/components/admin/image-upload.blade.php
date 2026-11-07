@props([
    'name' => 'featured_image',
    'label' => 'Featured Image',
    'value' => null,
    'required' => false,
    'help' => 'Choose a photo from your computer (PNG, JPG, WEBP, SVG)',
])

@php
    $currentVal = old($name, $value);
@endphp

<div class="space-y-2" 
     x-data="adminImagePicker({
         name: '{{ $name }}',
         initialUrl: '{{ $currentVal ? addslashes($currentVal) : '' }}',
         uploadUrl: '{{ route('admin.media.upload') }}',
         csrfToken: '{{ csrf_token() }}'
     })">

    <label class="block text-sm font-medium text-gray-700">
        {{ $label }}
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>

    {{-- Hidden input holding the actual image URL to be submitted with form --}}
    <input type="hidden" name="{{ $name }}" x-model="imageUrl" @if($required) required @endif>

    {{-- Hidden file input for native file browser dialog --}}
    <input type="file" 
           name="{{ $name }}_file" 
           x-ref="fileInput" 
           accept="image/*" 
           class="hidden" 
           @change="handleFileSelected($event)">

    {{-- State 1: Image exists or is selected --}}
    <div x-show="imageUrl" class="relative group" style="display: none;">
        <div class="flex items-center gap-4 p-3 bg-gray-50 border border-gray-200 rounded-lg">
            <div class="relative w-24 h-20 bg-gray-900 rounded-md overflow-hidden shrink-0 flex items-center justify-center border border-gray-200 shadow-2xs">
                <img :src="imageUrl" alt="{{ $label }}" class="w-full h-full object-cover">
                <div x-show="isUploading" class="absolute inset-0 bg-black/60 flex items-center justify-center" style="display: none;">
                    <svg class="animate-spin w-5 h-5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                </div>
            </div>

            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-4xs font-bold tracking-wide uppercase"
                          :class="isUploading ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800'">
                        <span x-text="isUploading ? 'Uploading...' : 'Image Ready'"></span>
                    </span>
                    <span x-show="fileName" x-text="fileName" class="text-xs text-gray-500 truncate"></span>
                </div>
                <p class="text-xs text-gray-500 truncate font-mono mt-1" x-text="imageUrl"></p>

                <div class="flex items-center gap-2 mt-2">
                    <button type="button" 
                            @click="$refs.fileInput.click()" 
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50 transition shadow-2xs">
                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        <span>Change Photo</span>
                    </button>

                    <button type="button" 
                            @click="removeImage()" 
                            class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold text-red-600 bg-white border border-red-200 rounded hover:bg-red-50 transition shadow-2xs">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <span>Remove</span>
                    </button>

                    <button type="button" 
                            @click="manualUrlMode = !manualUrlMode" 
                            class="text-4xs text-gray-500 hover:text-gray-700 underline ml-2">
                        <span x-text="manualUrlMode ? 'Hide URL' : 'Edit URL'"></span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Optional manual URL editor toggle --}}
        <div x-show="manualUrlMode" class="mt-2" style="display: none;">
            <input type="text" x-model="imageUrl" class="form-input w-full text-xs font-mono" placeholder="https://... or /images/...">
        </div>
    </div>

    {{-- State 2: No image chosen (Dropzone / Click to choose) --}}
    <div x-show="!imageUrl" style="display: block;">
        <div class="border-2 border-dashed border-gray-300 hover:border-gray-900 rounded-lg p-6 text-center transition cursor-pointer bg-gray-50 hover:bg-gray-100/70"
             :class="{ 'border-gray-900 bg-gray-100': isDragging }"
             @click="$refs.fileInput.click()"
             @dragover.prevent="isDragging = true"
             @dragleave.prevent="isDragging = false"
             @drop.prevent="isDragging = false; handleDrop($event)">

            <div x-show="!isUploading">
                <div class="mx-auto w-12 h-12 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-600 shadow-2xs mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>

                <div class="text-sm font-semibold text-gray-900">
                    <span class="text-gray-900 underline decoration-gray-400">Click to choose image</span> from your device
                </div>
                <p class="text-xs text-gray-500 mt-1">or drag and drop file here (PNG, JPG, WEBP, SVG up to 12MB)</p>
            </div>

            <div x-show="isUploading" style="display: none;" class="py-4">
                <svg class="animate-spin mx-auto w-8 h-8 text-gray-900 mb-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <p class="text-xs font-semibold text-gray-700">Uploading image to server...</p>
            </div>
        </div>

        <div class="flex items-center justify-between text-4xs text-gray-400 mt-1.5 px-1">
            <span>{{ $help }}</span>
            <button type="button" @click.stop="manualUrlMode = !manualUrlMode" class="text-gray-500 hover:text-gray-700 underline">
                or enter image URL
            </button>
        </div>

        <div x-show="manualUrlMode" class="mt-2" style="display: none;">
            <input type="text" x-model="imageUrl" class="form-input w-full text-xs font-mono" placeholder="https://... or /images/...">
        </div>
    </div>

    {{-- Error message if upload fails --}}
    <div x-show="uploadError" class="text-xs text-red-600 bg-red-50 p-2 rounded border border-red-200 mt-1" x-text="uploadError" style="display: none;"></div>
</div>

<script>
if (typeof window.adminImagePicker === 'undefined') {
    window.adminImagePicker = function(config) {
        return {
            name: config.name,
            imageUrl: config.initialUrl || '',
            fileName: '',
            isDragging: false,
            isUploading: false,
            uploadError: null,
            manualUrlMode: false,

            handleFileSelected(event) {
                const file = event.target.files?.[0];
                if (file) {
                    this.uploadFile(file);
                }
            },

            handleDrop(event) {
                const file = event.dataTransfer?.files?.[0];
                if (file && file.type.startsWith('image/')) {
                    this.uploadFile(file);
                }
            },

            uploadFile(file) {
                this.fileName = file.name;
                this.uploadError = null;
                this.isUploading = true;

                // Immediate local preview while uploading
                const localUrl = URL.createObjectURL(file);
                this.imageUrl = localUrl;

                const formData = new FormData();
                formData.append('image', file);

                fetch(config.uploadUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': config.csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(res => {
                    if (!res.ok) {
                        return res.json().then(err => { throw new Error(err.message || 'Upload failed'); });
                    }
                    return res.json();
                })
                .then(data => {
                    this.isUploading = false;
                    if (data.success && data.url) {
                        this.imageUrl = data.url;
                        this.fileName = data.name || file.name;
                    } else {
                        throw new Error('Server returned invalid upload response');
                    }
                })
                .catch(err => {
                    this.isUploading = false;
                    this.uploadError = 'Upload failed: ' + (err.message || 'Please try another image.');
                    console.error('Image upload error:', err);
                });
            },

            removeImage() {
                this.imageUrl = '';
                this.fileName = '';
                this.uploadError = null;
                if (this.$refs.fileInput) {
                    this.$refs.fileInput.value = '';
                }
            }
        };
    };
}
</script>

