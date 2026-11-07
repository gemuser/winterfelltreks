@props([
    'name' => 'featured_image',
    'label' => 'Featured Image',
    'value' => null,
    'required' => false,
    'aspectRatio' => '16/9',
    'help' => null,
])

@php
    $currentVal = old($name, $value);

    $ratioMap = [
        '16/9' => ['val' => 16 / 9, 'label' => '16:9 (Landscape)'],
        '16:9' => ['val' => 16 / 9, 'label' => '16:9 (Landscape)'],
        '1/1'  => ['val' => 1.0,    'label' => '1:1 (Square)'],
        '1:1'  => ['val' => 1.0,    'label' => '1:1 (Square)'],
        '4/3'  => ['val' => 4 / 3,  'label' => '4:3 (Standard)'],
        '4:3'  => ['val' => 4 / 3,  'label' => '4:3 (Standard)'],
        '3/2'  => ['val' => 1.5,    'label' => '3:2 (Classic)'],
        '3:2'  => ['val' => 1.5,    'label' => '3:2 (Classic)'],
        'free' => ['val' => 0,      'label' => 'Free (Unconstrained)'],
    ];

    $ratioConfig = $ratioMap[$aspectRatio] ?? ['val' => 16 / 9, 'label' => $aspectRatio];
    $numericRatio = $ratioConfig['val'];
    $ratioLabel = $ratioConfig['label'];

    if (!$help) {
        $help = "Supported Aspect Ratio: {$ratioLabel}. Crop available on upload.";
    }
@endphp

<div class="space-y-2" 
     x-data="adminImagePicker({
         name: '{{ $name }}',
         initialUrl: '{{ $currentVal ? addslashes($currentVal) : '' }}',
         uploadUrl: '{{ route('admin.media.upload') }}',
         csrfToken: '{{ csrf_token() }}',
         targetRatio: {{ $numericRatio }},
         ratioLabel: '{{ $ratioLabel }}'
     })"
     x-id="['image-crop-modal']">

    <div class="flex items-center justify-between">
        <label class="block text-sm font-medium text-gray-700">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
        @if($numericRatio > 0)
        <span class="inline-flex items-center gap-1 text-4xs font-bold text-gray-500 bg-gray-100 border border-gray-200 px-2 py-0.5 rounded tracking-wide uppercase">
            <svg class="w-2.5 h-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span>Ratio: {{ $ratioLabel }}</span>
        </span>
        @endif
    </div>

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
            <div class="relative w-28 h-20 bg-gray-950 rounded-md overflow-hidden shrink-0 flex items-center justify-center border border-gray-300 shadow-2xs">
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

                <div class="flex flex-wrap items-center gap-2 mt-2">
                    {{-- Choose / Replace button --}}
                    <button type="button" 
                            @click="$refs.fileInput.click()" 
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50 transition shadow-2xs">
                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        <span>Change Image</span>
                    </button>

                    {{-- Crop Existing Image button --}}
                    <button type="button" 
                            @click="startCropCurrent()" 
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold text-indigo-700 bg-indigo-50 border border-indigo-200 rounded hover:bg-indigo-100 transition shadow-2xs"
                            title="Crop and reframe this photo">
                        <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 10-4.243-4.243 3 3 0 004.243 4.243z" />
                        </svg>
                        <span>Crop / Reframe</span>
                    </button>

                    {{-- Remove button --}}
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
                            class="text-4xs text-gray-500 hover:text-gray-700 underline ml-1">
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
                <p class="text-xs text-gray-500 mt-1">or drag & drop file here (PNG, JPG, WEBP, SVG up to 12MB)</p>
                <div class="inline-flex items-center gap-1.5 mt-2 px-2.5 py-0.5 rounded-full text-4xs font-bold text-gray-600 bg-white border border-gray-200">
                    <span>Target Aspect Ratio: <strong>{{ $ratioLabel }}</strong></span>
                </div>
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

    {{-- ======================================================== --}}
    {{-- MODAL: Interactive Cropper Dialog (Locked to Aspect Ratio) --}}
    {{-- ======================================================== --}}
    <div x-show="showCropModal" 
         class="fixed inset-0 z-[999999] bg-black/85 backdrop-blur-2xs flex items-center justify-center p-4 overflow-y-auto"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;">

        <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full overflow-hidden flex flex-col max-h-[92vh] border border-gray-200"
             @click.outside="closeCropModal()">

            {{-- Modal Header --}}
            <div class="p-4 px-6 border-b border-gray-200 flex items-center justify-between bg-gray-50">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gray-900 text-white flex items-center justify-center shadow-2xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 10-4.243-4.243 3 3 0 004.243 4.243z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Crop & Position Image</h3>
                        <p class="text-2xs text-gray-500">The crop box is fixed to the exact supported ratio: <strong class="text-gray-800" x-text="ratioLabel"></strong></p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-1 text-2xs font-bold rounded-md uppercase tracking-wider bg-indigo-100 text-indigo-900 border border-indigo-200" x-text="ratioLabel"></span>
                    <button type="button" @click="closeCropModal()" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg hover:bg-gray-200 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Modal Body: Image Viewport --}}
            <div class="relative bg-gray-950 flex items-center justify-center overflow-hidden" style="height: 52vh; min-height: 340px;">
                <div class="w-full h-full flex items-center justify-center p-2">
                    <img x-ref="cropperImg" class="max-w-full max-h-full block opacity-0" alt="Crop preview">
                </div>
            </div>

            {{-- Modal Toolbar: Zoom & Rotate Controls --}}
            <div class="px-6 py-2.5 bg-gray-100 border-t border-b border-gray-200 flex flex-wrap items-center justify-between gap-3 text-xs">
                <div class="flex items-center gap-1.5">
                    <button type="button" @click="cropperZoom(0.1)" class="btn-secondary px-2.5 py-1 text-xs inline-flex items-center gap-1" title="Zoom In">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Zoom In</span>
                    </button>
                    <button type="button" @click="cropperZoom(-0.1)" class="btn-secondary px-2.5 py-1 text-xs inline-flex items-center gap-1" title="Zoom Out">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                        </svg>
                        <span>Zoom Out</span>
                    </button>
                    <button type="button" @click="cropperRotate(-90)" class="btn-secondary px-2.5 py-1 text-xs inline-flex items-center gap-1" title="Rotate Left 90°">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                        </svg>
                        <span>-90°</span>
                    </button>
                    <button type="button" @click="cropperRotate(90)" class="btn-secondary px-2.5 py-1 text-xs inline-flex items-center gap-1" title="Rotate Right 90°">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10H11a8 8 0 00-8 8v2m18-10l-6 6m6-6l-6-6" />
                        </svg>
                        <span>+90°</span>
                    </button>
                    <button type="button" @click="cropperReset()" class="btn-secondary px-2.5 py-1 text-xs" title="Reset View">
                        Reset
                    </button>
                </div>

                <div class="flex items-center gap-2">
                    <span class="text-3xs text-gray-500 font-medium">Aspect Ratio:</span>
                    <button type="button" 
                            @click="setCropRatio(targetRatio)" 
                            :class="currentActiveRatio > 0 ? 'bg-gray-900 text-white font-bold' : 'bg-white text-gray-700'"
                            class="px-2.5 py-0.5 rounded text-3xs border border-gray-300 shadow-2xs">
                        <span x-text="ratioLabel"></span>
                    </button>
                    <button type="button" 
                            @click="setCropRatio(0)" 
                            :class="currentActiveRatio === 0 ? 'bg-gray-900 text-white font-bold' : 'bg-white text-gray-700'"
                            class="px-2 py-0.5 rounded text-3xs border border-gray-300 shadow-2xs">
                        Free
                    </button>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="p-4 px-6 bg-white flex items-center justify-between gap-3">
                <div>
                    <button type="button" 
                            x-show="pendingFile" 
                            @click="useOriginalFile()" 
                            class="text-xs font-semibold text-gray-600 hover:text-gray-900 underline">
                        Skip Cropping (Use Original)
                    </button>
                </div>

                <div class="flex items-center gap-3">
                    <button type="button" @click="closeCropModal()" class="btn-secondary text-xs">
                        Cancel
                    </button>
                    <button type="button" 
                            @click="applyCropAndUpload()" 
                            class="btn-primary text-xs inline-flex items-center gap-1.5 px-5 py-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Crop & Save Image</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
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

            // Cropper state
            targetRatio: config.targetRatio > 0 ? config.targetRatio : 0,
            currentActiveRatio: config.targetRatio > 0 ? config.targetRatio : 0,
            ratioLabel: config.ratioLabel || '16:9',
            showCropModal: false,
            cropperInstance: null,
            pendingFile: null,
            cropSourceUrl: null,

            handleFileSelected(event) {
                const file = event.target.files?.[0];
                if (!file) return;

                this.pendingFile = file;
                this.fileName = file.name;
                this.uploadError = null;

                // Create local temporary URL to feed into Cropper
                const localUrl = URL.createObjectURL(file);
                this.openCropModal(localUrl);
            },

            handleDrop(event) {
                const file = event.dataTransfer?.files?.[0];
                if (file && file.type.startsWith('image/')) {
                    this.pendingFile = file;
                    this.fileName = file.name;
                    this.uploadError = null;

                    const localUrl = URL.createObjectURL(file);
                    this.openCropModal(localUrl);
                }
            },

            startCropCurrent() {
                if (!this.imageUrl) return;
                this.pendingFile = null;
                this.openCropModal(this.imageUrl);
            },

            openCropModal(sourceUrl) {
                this.cropSourceUrl = sourceUrl;
                this.showCropModal = true;
                this.currentActiveRatio = this.targetRatio;

                this.$nextTick(() => {
                    const img = this.$refs.cropperImg;
                    if (!img) return;

                    img.onload = () => {
                        img.style.opacity = '1';
                        if (this.cropperInstance) {
                            this.cropperInstance.destroy();
                        }

                        if (typeof Cropper !== 'undefined') {
                            this.cropperInstance = new Cropper(img, {
                                aspectRatio: this.targetRatio > 0 ? this.targetRatio : NaN,
                                viewMode: 1,
                                dragMode: 'move',
                                autoCropArea: 0.95,
                                responsive: true,
                                restore: false,
                                guides: true,
                                center: true,
                                highlight: false,
                                cropBoxMovable: true,
                                cropBoxResizable: true,
                                toggleDragModeOnDblclick: false,
                            });
                        }
                    };

                    img.src = sourceUrl;
                });
            },

            setCropRatio(ratio) {
                this.currentActiveRatio = ratio;
                if (this.cropperInstance) {
                    this.cropperInstance.setAspectRatio(ratio > 0 ? ratio : NaN);
                }
            },

            cropperZoom(delta) {
                if (this.cropperInstance) {
                    this.cropperInstance.zoom(delta);
                }
            },

            cropperRotate(degrees) {
                if (this.cropperInstance) {
                    this.cropperInstance.rotate(degrees);
                }
            },

            cropperReset() {
                if (this.cropperInstance) {
                    this.cropperInstance.reset();
                }
            },

            closeCropModal() {
                this.showCropModal = false;
                if (this.cropperInstance) {
                    this.cropperInstance.destroy();
                    this.cropperInstance = null;
                }
                if (this.$refs.fileInput) {
                    this.$refs.fileInput.value = '';
                }
            },

            useOriginalFile() {
                if (this.pendingFile) {
                    const fileToUpload = this.pendingFile;
                    this.closeCropModal();
                    this.uploadFile(fileToUpload);
                } else {
                    this.closeCropModal();
                }
            },

            applyCropAndUpload() {
                if (!this.cropperInstance) {
                    this.useOriginalFile();
                    return;
                }

                // Render cropped image to high-quality canvas
                const canvas = this.cropperInstance.getCroppedCanvas({
                    maxWidth: 2560,
                    maxHeight: 1800,
                    imageSmoothingEnabled: true,
                    imageSmoothingQuality: 'high'
                });

                if (!canvas) {
                    this.useOriginalFile();
                    return;
                }

                canvas.toBlob((blob) => {
                    if (!blob) {
                        this.useOriginalFile();
                        return;
                    }

                    const safeBaseName = (this.fileName || 'cropped-image').replace(/\.[^/.]+$/, '');
                    const croppedFile = new File([blob], `${safeBaseName}-cropped.jpg`, { type: 'image/jpeg' });

                    this.closeCropModal();
                    this.uploadFile(croppedFile);
                }, 'image/jpeg', 0.92);
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
