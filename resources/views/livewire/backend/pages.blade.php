<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Pages</h3>
            <button class="btn btn-outline-primary btn-sm" wire:click="resetInputFields" data-toggle="modal"
                data-target="#pageModal">
                <i class="fas fa-plus"></i> Tambah
            </button>
        </div>

        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between mb-3">
                <div class="mb-2">
                    <label class="mr-2">Show:</label>
                    <select class="form-control form-control-sm d-inline w-auto" wire:model.live="paginate">
                        <option>5</option>
                        <option>10</option>
                        <option>20</option>
                    </select>
                </div>

                <div class="mb-2">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" placeholder="Search title/slug/content"
                            wire:model.live="search">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                    </div>
                </div>

                <div class="mb-2">
                    <select class="form-control form-control-sm" wire:model.live="filterStatus">
                        <option value="">All Status</option>
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                    </select>
                </div>
            </div>

            <table class="table table-bordered">
                <thead class="text-center">
                    <tr>
                        <th style="width:60px">No</th>
                        <th>Title</th>
                        <th>Page Type</th>
                        <th>Published</th>
                        <th>Updated</th>
                        <th style="width:120px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $i => $p)
                        <tr>
                            <td class="text-center">{{ ($rows->currentPage() - 1) * $rows->perPage() + $i + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if ($p->image)
                                        <img src="{{ asset('storage/' . $p->image) }}" class="rounded mr-2"
                                            style="width:50px;height:50px;object-fit:cover"
                                            onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'bg-secondary rounded mr-2 d-flex align-items-center justify-content-center\' style=\'width:50px;height:50px;min-width:50px;\'><i class=\'fas fa-image text-white\'></i></div><div><div class=\'font-weight-bold\'>{{ $p->title }}</div><div class=\'text-muted small\'>{{ $p->slug }}</div></div>'; ">
                                    @else
                                        <div class="bg-secondary rounded mr-2 d-flex align-items-center justify-content-center"
                                            style="width:50px;height:50px;min-width:50px;">
                                            <i class="fas fa-image text-white"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-weight-bold">{{ $p->title }}</div>
                                        <div class="text-muted small">{{ $p->slug }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">{{ $p->page_type ?: '-' }}</td>
                            <td class="text-center">
                                @if ($p->is_published)
                                    <span class="badge badge-success">Published</span>
                                @else
                                    <span class="badge badge-secondary">Draft</span>
                                @endif
                            </td>
                            <td>{{ $p->updated_at->diffForHumans() }}</td>
                            <td class="text-center">
                                <button class="btn btn-warning btn-sm" wire:click="edit({{ $p->id }})"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm"
                                    wire:click="delete_confirmation({{ $p->id }})" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-2">{{ $rows->links('livewire::bootstrap') }}</div>
        </div>
    </div>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="pageModal" tabindex="-1" aria-labelledby="pageModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pageModalLabel">{{ $isEdit ? 'Edit' : 'Tambah' }} Page</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>

                <div class="modal-body">
                    <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                        <div class="form-row">
                            <div class="form-group col-md-8">
                                <label>Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" wire:model.live="title"
                                    wire:keyup.debounce.500ms="generateSlug">
                                @error('title')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label>Slug</label>
                                <input type="text" class="form-control" wire:model.live="slug"
                                    placeholder="otomatis dari title jika kosong">
                                @error('slug')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Page Type <span class="text-danger">*</span></label>
                                <select class="form-control" wire:model.live="page_type">
                                    <option value="">-- Pilih Page Type --</option>
                                    <option value="service">Service</option>
                                    <option value="profile">Profile</option>
                                    <option value="contact">Contact</option>
                                    <option value="rfc2350">RFC2350</option>
                                    <option value="guide">Guide</option>
                                    <option value="other">Other</option>
                                </select>
                                @error('page_type')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="form-group col-md-4">
                                <label>Status Publish <span class="text-danger">*</span></label>
                                <select class="form-control" wire:model.live="is_published">
                                    <option value="0">Draft</option>
                                    <option value="1">Published</option>
                                </select>
                                @error('is_published')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-2 d-flex align-items-end">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="is_file"
                                        wire:model.live="is_file">
                                    <label class="custom-control-label" for="is_file">Halaman Panduan?</label>
                                </div>
                                @error('is_file')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-9">
                                <label>Featured Image (opsional, max 2MB)</label>
                                <input type="file" class="form-control-file" wire:model="image" accept="image/*">
                                @error('image')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                                <div wire:loading wire:target="image" class="text-info small mt-1">
                                    <i class="fas fa-spinner fa-spin"></i> Uploading...
                                </div>

                                @if ($isEdit)
                                    <small class="text-muted d-block mt-1">
                                        Current image path: {{ $current_image ?? 'null' }}
                                    </small>
                                @endif
                            </div>
                            <div class="form-group col-md-3">
                                @if ($image)
                                    <label class="d-block text-center">Preview Baru</label>
                                    <img src="{{ $image->temporaryUrl() }}" class="img-thumbnail"
                                        style="max-height:120px;width:100%;object-fit:cover;">
                                @elseif(!empty($current_image))
                                    <label class="d-block text-center">Gambar Saat Ini</label>
                                    <img src="{{ asset('storage/' . $current_image) }}" class="img-thumbnail"
                                        style="max-height:120px;width:100%;object-fit:cover;"
                                        onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22100%22 height=%22100%22%3E%3Crect fill=%22%23ddd%22 width=%22100%22 height=%22100%22/%3E%3Ctext fill=%22%23999%22 x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dy=%22.3em%22%3ENo Image%3C/text%3E%3C/svg%3E';">
                                @else
                                    <label class="d-block text-center">Preview</label>
                                    <div class="border rounded bg-light d-flex align-items-center justify-content-center"
                                        style="height:120px;">
                                        <span class="text-muted"><i class="fas fa-image fa-2x"></i></span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-12">
                                <label>File Dokumen (opsional, PDF, max 10MB)</label>
                                <input type="file" class="form-control-file" wire:model="file" accept=".pdf"
                                    id="file-input">
                                @error('file')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                                <div wire:loading wire:target="file" class="text-info small mt-1">
                                    <i class="fas fa-spinner fa-spin"></i> Uploading...
                                </div>

                                @if ($isEdit && $current_file)
                                    <small class="text-muted d-block mt-1">
                                        Current file: <a href="{{ asset('storage/' . $current_file) }}"
                                            target="_blank">{{ basename($current_file) }}</a>
                                    </small>
                                @endif
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-12" wire:ignore>
                                <label>Preview Dokumen</label>
                                <div class="border rounded bg-light" style="height: 400px;">
                                    <iframe id="pdf-preview" src="" width="100%" height="100%"
                                        style="display: none;"></iframe>
                                    <div id="no-preview"
                                        class="d-flex align-items-center justify-content-center h-100">
                                        <span class="text-muted">Tidak ada file untuk ditampilkan</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" wire:ignore>
                            <label>Content <span class="text-danger">*</span></label>
                            <textarea rows="8" class="form-control" wire:model="content" placeholder="Tulis konten halaman..."
                                id="content"></textarea>
                            @error('content')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary"
                        wire:click.prevent="{{ $isEdit ? 'update' : 'store' }}" wire:loading.attr="disabled"
                        wire:target="store,update">
                        <span wire:loading.remove wire:target="store,update">
                            {{ $isEdit ? 'Update' : 'Simpan' }}
                        </span>
                        <span wire:loading wire:target="store,update">
                            <i class="fas fa-spinner fa-spin"></i> Processing...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('css')
    <style>
        .ck-editor__editable {
            min-height: 350px;
        }

        .ck.ck-editor {
            width: 100%;
        }
    </style>
@endpush

@push('js')
    <script>
        let editorInstance = null;

        function initializeCKEditor() {
            const contentElement = document.querySelector('#content');
            if (!contentElement) return;

            if (editorInstance) {
                editorInstance.destroy().then(() => {
                    editorInstance = null;
                    createEditor();
                });
            } else {
                createEditor();
            }
        }

        function createEditor() {
            class MyUploadAdapter {
                constructor(loader) {
                    this.loader = loader;
                }

                upload() {
                    return this.loader.file
                        .then(file => new Promise((resolve, reject) => {
                            this._initRequest();
                            this._initListeners(resolve, reject, file);
                            this._sendRequest(file);
                        }));
                }

                abort() {
                    if (this.xhr) {
                        this.xhr.abort();
                    }
                }

                _initRequest() {
                    const xhr = this.xhr = new XMLHttpRequest();
                    xhr.open('POST', '{{ route('upload-image') }}', true);
                    xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content'));
                    xhr.responseType = 'json';
                }

                _initListeners(resolve, reject, file) {
                    const xhr = this.xhr;
                    const loader = this.loader;
                    const genericErrorText = `Couldn't upload file: ${ file.name }.`;

                    xhr.addEventListener('error', () => reject(genericErrorText));
                    xhr.addEventListener('abort', () => reject());
                    xhr.addEventListener('load', () => {
                        const response = xhr.response;

                        if (!response || response.error) {
                            return reject(response && response.error ? response.error.message :
                                genericErrorText);
                        }

                        resolve({
                            default: response.url
                        });
                    });

                    if (xhr.upload) {
                        xhr.upload.addEventListener('progress', evt => {
                            if (evt.lengthComputable) {
                                loader.uploadTotal = evt.total;
                                loader.uploaded = evt.loaded;
                            }
                        });
                    }
                }

                _sendRequest(file) {
                    const data = new FormData();
                    data.append('upload', file);
                    this.xhr.send(data);
                }
            }

            function MyCustomUploadAdapterPlugin(editor) {
                editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                    return new MyUploadAdapter(loader);
                };
            }

            ClassicEditor.create(document.querySelector('#content'), {
                extraPlugins: [MyCustomUploadAdapterPlugin],
                toolbar: {
                    items: [
                        'heading', '|', 'bold', 'italic', 'link', '|',
                        'bulletedList', 'numberedList', '|',
                        'outdent', 'indent', '|', 'blockQuote', 'insertTable', 'imageUpload', '|',
                        'undo', 'redo'
                    ]
                },
                image: {
                    resizeOptions: [{
                            name: 'imageResize:original',
                            value: null,
                            label: 'Original'
                        },
                        {
                            name: 'imageResize:50',
                            value: '50',
                            label: '50%'
                        },
                        {
                            name: 'imageResize:75',
                            value: '75',
                            label: '75%'
                        }
                    ],
                    toolbar: [
                        'imageStyle:inline',
                        'imageStyle:block',
                        'imageStyle:side',
                        '|',
                        'toggleImageCaption',
                        'imageTextAlternative',
                        '|',
                        'imageResize'
                    ]
                },
                heading: {
                    options: [{
                            model: 'paragraph',
                            title: 'Paragraph',
                            class: 'ck-heading_paragraph'
                        },
                        {
                            model: 'heading1',
                            view: 'h1',
                            title: 'Heading 1',
                            class: 'ck-heading_heading1'
                        },
                        {
                            model: 'heading2',
                            view: 'h2',
                            title: 'Heading 2',
                            class: 'ck-heading_heading2'
                        },
                        {
                            model: 'heading3',
                            view: 'h3',
                            title: 'Heading 3',
                            class: 'ck-heading_heading3'
                        }
                    ]
                },
                table: {
                    contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
                },
                language: 'en'
            }).then(editor => {
                editorInstance = editor;
                editor.model.document.on('change:data', () => {
                    @this.set('content', editor.getData(), false);
                });
            }).catch(console.error);
        }

        $(document).ready(function() {
            $('#pageModal').on('shown.bs.modal', function() {
                setTimeout(initializeCKEditor, 200);
            });
            $('#pageModal').on('hidden.bs.modal', function() {
                if (editorInstance) {
                    editorInstance.destroy().then(() => {
                        editorInstance = null;
                    });
                }
            });
        });

        document.addEventListener('livewire:init', () => {
            Livewire.on('editor-content-updated', (event) => {
                setTimeout(() => {
                    if (editorInstance) editorInstance.setData(event.content || '');
                }, 250);
            });

            Livewire.on('clear-editor', () => {
                if (editorInstance) editorInstance.setData('');
            });

            Livewire.on('file-updated', (event) => {
                const pdfPreview = document.getElementById('pdf-preview');
                const noPreview = document.getElementById('no-preview');
                if (event.fileUrl) {
                    pdfPreview.src = event.fileUrl;
                    pdfPreview.style.display = 'block';
                    noPreview.style.display = 'none';
                } else {
                    pdfPreview.src = '';
                    pdfPreview.style.display = 'none';
                    noPreview.style.display = 'block';
                }
            });
        });

        $('#pageModal').on('shown.bs.modal', function() {
            const fileInput = document.getElementById('file-input');
            const pdfPreview = document.getElementById('pdf-preview');
            const noPreview = document.getElementById('no-preview');

            fileInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file && file.type === 'application/pdf') {
                    const url = URL.createObjectURL(file);
                    pdfPreview.src = url;
                    pdfPreview.style.display = 'block';
                    noPreview.style.display = 'none';
                } else {
                    pdfPreview.src = '';
                    pdfPreview.style.display = 'none';
                    noPreview.style.display = 'flex';
                }
            });
        });

        $('#pageModal').on('hidden.bs.modal', function() {
            const pdfPreview = document.getElementById('pdf-preview');
            const noPreview = document.getElementById('no-preview');
            if (pdfPreview.src) {
                URL.revokeObjectURL(pdfPreview.src);
            }
            pdfPreview.src = '';
            pdfPreview.style.display = 'none';
            noPreview.style.display = 'flex';
            document.getElementById('file-input').value = '';
        });
    </script>
@endpush
