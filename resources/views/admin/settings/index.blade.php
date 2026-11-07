@extends('admin.layout')

@section('title', 'Site Settings')

@section('content')
<div class="max-w-5xl mx-auto" x-data="{
    showCreate: false,
    showEdit: false,
    editingId: null,
    submitting: false,
    formData: { key: '', group: 'general', type: 'text', value: '', alt: '' },
    
    openEdit(id) {
        fetch(`/admin/site-settings/${id}/edit`, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json())
            .then(data => {
                this.formData = { key: data.key, group: data.group, type: data.type, value: data.value ?? '', alt: '' };
                this.editingId = id;
                this.showEdit = true;
            }).catch(() => alert('Failed to load setting.'));
    },
    
    closeModal() {
        this.showCreate = false;
        this.showEdit = false;
        this.editingId = null;
        this.formData = { key: '', group: 'general', type: 'text', value: '', alt: '' };
    },
    
    async submitForm() {
        this.submitting = true;
        const url = this.showEdit ? `/admin/site-settings/${this.editingId}` : '/admin/site-settings';
        const method = this.showEdit ? 'PUT' : 'POST';
        
        const formData = new FormData();
        if (method === 'PUT') formData.append('_method', 'PUT');
        formData.append('key', this.formData.key);
        formData.append('group', this.formData.group);
        formData.append('type', this.formData.type);
        
        const csrfToken = document.querySelector('input[name=\'_token\']')?.value ?? '';
        
        if (this.formData.type === 'image') {
            formData.append('value', JSON.stringify({ url: this.formData.value, alt: this.formData.alt }));
        } else if (this.formData.type === 'toggle') {
            formData.append('value', this.formData.value ? 'true' : 'false');
        } else {
            formData.append('value', this.formData.value);
        }
        
        try {
            const response = await fetch(url, {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            });
            if (response.ok) { window.location.reload(); }
            else { const data = await response.json(); alert(data.message ?? 'Failed to save setting.'); }
        } catch (error) {
            alert('Network error. Please try again.');
        } finally {
            this.submitting = false;
        }
    }
}">
    <div class="mb-8 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Site Settings</h1>
        <button type="button" class="btn-primary" @click="showCreate = true">Add Setting</button>
    </div>
    
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Key</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Group</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($groups as $group)
                    <tr class="bg-gray-50">
                        <td class="px-6 py-3 text-sm font-semibold text-gray-900 uppercase tracking-wider" colspan="5">{{ $group }}</td>
                    </tr>
                    @foreach($settings[$group] as $setting)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-mono text-gray-900">{{ $setting->key }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $setting->group }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-gray-100 text-gray-700">{{ $setting->type }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                            @if($setting->type === 'image')
                            @php $val = json_decode($setting->value, true); @endphp
                            @if($val && isset($val['url']))
                            <img src="{{ $val['url'] }}" alt="" class="max-h-12 rounded">
                            @else
                            -
                            @endif
                            @elseif($setting->type === 'json')
                            <pre class="font-mono text-xs max-h-24 overflow-auto">{{ json_encode(json_decode($setting->value, true), JSON_PRETTY_PRINT) }}</pre>
                            @else
                            {{ Str::limit($setting->value, 100) }}
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right text-sm">
                            <button type="button" class="text-accent-primary hover:underline" @click="openEdit({{ $setting->id }})">Edit</button>
                        </td>
                    </tr>
                    @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    {{-- Create/Edit Modal --}}
    <div x-data="settingsModal()" x-show="showCreate || showEdit" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
        <div class="fixed inset-0 bg-black/50" @click="closeModal()"></div>
        
        <div class="relative bg-white rounded-lg shadow-xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900" x-text="showCreate ? 'Create Setting' : 'Edit Setting'"></h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" @click="closeModal()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <form @submit.prevent="submitForm" class="p-6 space-y-6">
                <div>
                    <label for="key" class="block text-sm font-medium text-gray-700 mb-2">Key <span class="text-red-500">*</span></label>
                    <input type="text" id="key" name="key" required class="form-input w-full" x-model="formData.key" @input="formData.key = formData.key.toLowerCase().replace(/[^a-z0-9_]/g, '')" :readonly="showEdit">
                </div>
                
                <div>
                    <label for="group" class="block text-sm font-medium text-gray-700 mb-2">Group <span class="text-red-500">*</span></label>
                    <select id="group" name="group" required class="form-input w-full" x-model="formData.group">
                        @foreach($groups as $g)
                        <option value="{{ $g }}">{{ $g }}</option>
                        @endforeach
                        <option value="custom">Custom...</option>
                    </select>
                </div>
                
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type <span class="text-red-500">*</span></label>
                    <select id="type" name="type" required class="form-input w-full" x-model="formData.type">
                        <option value="text">Text</option>
                        <option value="textarea">Textarea</option>
                        <option value="image">Image</option>
                        <option value="json">JSON</option>
                        <option value="toggle">Toggle (Boolean)</option>
                        <option value="number">Number</option>
                    </select>
                </div>
                
                <template x-if="formData.type === 'image'">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Image URL</label>
                        <input type="url" name="value" class="form-input w-full" x-model="formData.value" placeholder="https://example.com/image.jpg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alt Text</label>
                        <input type="text" name="alt" class="form-input w-full" x-model="formData.alt" placeholder="Alt text">
                    </div>
                </template>
                
                <template x-if="formData.type === 'json'">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">JSON Value</label>
                        <textarea name="value" rows="6" class="form-input w-full font-mono text-sm" x-model="formData.value" placeholder='{"key": "value"}'></textarea>
                    </div>
                </template>
                
                <template x-if="formData.type === 'textarea'">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Value</label>
                        <textarea name="value" rows="4" class="form-input w-full" x-model="formData.value"></textarea>
                    </div>
                </template>
                
                <template x-if="formData.type === 'toggle'">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="value" class="form-checkbox h-5 w-5" x-model="formData.value">
                        <label class="text-sm text-gray-700">Enabled</label>
                    </div>
                </template>
                
                <template x-if="formData.type === 'number'">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Value</label>
                        <input type="number" name="value" class="form-input w-full" x-model.number="formData.value">
                    </div>
                </template>
                
                <template x-if="formData.type === 'text'">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Value</label>
                        <input type="text" name="value" class="form-input w-full" x-model="formData.value">
                    </div>
                </template>
                
                <div class="pt-4 border-t border-gray-200 flex justify-end gap-3">
                    <button type="button" class="btn-secondary" @click="closeModal()">Cancel</button>
                    <button type="submit" class="btn-primary" :disabled="submitting">
                        <span x-text="showCreate ? 'Create' : 'Update'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function editSetting(id) {
            fetch(`/admin/site-settings/${id}`)
                .then(r => r.json())
                .then(data => {
                    this.formData = { ...data };
                    this.editingId = id;
                    this.showEdit = true;
                });
        }
        
        function closeModal() {
            this.showCreate = false;
            this.showEdit = false;
            this.editingId = null;
            this.formData = { key: '', group: 'general', type: 'text', value: '' };
        }
        
        async function submitForm() {
            this.submitting = true;
            const url = this.showEdit ? `/admin/site-settings/${this.editingId}` : '/admin/site-settings';
            const method = this.showEdit ? 'PUT' : 'POST';
            
            const formData = new FormData();
            formData.append('key', this.formData.key);
            formData.append('group', this.formData.group);
            formData.append('type', this.formData.type);
            
            if (this.formData.type === 'image') {
                formData.append('value', JSON.stringify({ url: this.formData.value, alt: this.formData.alt }));
            } else if (this.formData.type === 'json') {
                formData.append('value', this.formData.value);
            } else if (this.formData.type === 'toggle') {
                formData.append('value', this.formData.value ? 'true' : 'false');
            } else {
                formData.append('value', this.formData.value);
            }
            
            try {
                const response = await fetch(url, {
                    method: method,
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                if (response.ok) {
                    window.location.reload();
                }
            } catch (error) {
                console.error(error);
            } finally {
                this.submitting = false;
            }
        }
    </script>
@endsection