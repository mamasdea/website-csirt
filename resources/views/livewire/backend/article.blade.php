<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Articles</h3>
            <button class="btn btn-outline-primary btn-sm" wire:click="resetInputFields" data-toggle="modal"
                data-target="#articleModal">
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
                        <option value="archived">Archived</option>
                    </select>
                </div>
            </div>

            <table class="table table-bordered">
                <thead class="text-center">
                    <tr>
                        <th style="width:60px">No</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Author</th>
                        <th>Status</th>
                        <th>Updated</th>
                        <th style="width:120px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $i => $a)
                        <tr>
                            <td class="text-center">{{ ($rows->currentPage() - 1) * $rows->perPage() + $i + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if ($a->image)
                                        <img src="{{ asset('storage/' . $a->image) }}" class="rounded mr-2"
                                            style="width:50px;height:50px;object-fit:cover"
                                            onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'bg-secondary rounded mr-2 d-flex align-items-center justify-content-center\' style=\'width:50px;height:50px;min-width:50px;\'><i class=\'fas fa-image text-white\'></i></div><div><div class=\'font-weight-bold\'>{{ $a->title }}</div><div class=\'text-muted small\'>{{ $a->slug }}</div></div>';">
                                    @else
                                        <div class="bg-secondary rounded mr-2 d-flex align-items-center justify-content-center"
                                            style="width:50px;height:50px;min-width:50px;">
                                            <i class="fas fa-image text-white"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-weight-bold">{{ $a->title }}</div>
                                        <div class="text-muted small">{{ $a->slug }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $a->category->name ?? '-' }}</td>
                            <td>{{ $a->author->name ?? '-' }}</td>
                            <td class="text-center">
                                @if ($a->status === 'published')
                                    <span class="badge badge-success">Published</span>
                                @elseif($a->status === 'draft')
                                    <span class="badge badge-secondary">Draft</span>
                                @else
                                    <span class="badge badge-warning">Archived</span>
                                @endif
                            </td>
                            <td>{{ $a->updated_at->diffForHumans() }}</td>
                            <td class="text-center">
                                <button class="btn btn-warning btn-sm" wire:click="edit({{ $a->id }})"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm"
                                    wire:click="delete_confirmation({{ $a->id }})" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-2">{{ $rows->links('livewire::bootstrap') }}</div>
        </div>
    </div>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="articleModal" tabindex="-1" aria-labelledby="articleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="articleModalLabel">{{ $isEdit ? 'Edit' : 'Tambah' }} Artikel</h5>
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
                            <div class="form-group col-md-4">
                                <label>Category <span class="text-danger">*</span></label>
                                <select class="form-control" wire:model.live="category_id">
                                    <option value="">-- pilih --</option>
                                    @foreach ($categories as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label>Author <span class="text-danger">*</span></label>
                                <select class="form-control" wire:model.live="author_id">
                                    <option value="">-- pilih --</option>
                                    @foreach ($authors as $u)
                                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                                    @endforeach
                                </select>
                                @error('author_id')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label>Status <span class="text-danger">*</span></label>
                                <select class="form-control" wire:model.live="status">
                                    <option value="draft">Draft</option>
                                    <option value="published">Published</option>
                                    <option value="archived">Archived</option>
                                </select>
                                @error('status')
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

                                {{-- Debug info --}}
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

                        <div class="form-group" wire:ignore>
                            <label>Content <span class="text-danger">*</span></label>
                            <textarea rows="8" class="form-control" wire:model="content" placeholder="Tulis konten artikel..."
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

        // Initialize CKEditor
        function initializeCKEditor() {
            const contentElement = document.querySelector('#content');

            if (!contentElement) {
                console.error('Content element not found');
                return;
            }

            // Destroy existing instance if any
            if (editorInstance) {
                editorInstance.destroy()
                    .then(() => {
                        editorInstance = null;
                        createEditor();
                    })
                    .catch(err => console.error('Error destroying editor:', err));
            } else {
                createEditor();
            }
        }

        function createEditor() {
            ClassicEditor
                .create(document.querySelector('#content'), {
                    toolbar: {
                        items: [
                            'heading', '|',
                            'bold', 'italic', 'link', '|',
                            'bulletedList', 'numberedList', '|',
                            'outdent', 'indent', '|',
                            'blockQuote', 'insertTable', '|',
                            'undo', 'redo'
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
                })
                .then(editor => {
                    editorInstance = editor;

                    // Sync content to Livewire
                    editor.model.document.on('change:data', () => {
                        @this.set('content', editor.getData(), false);
                    });

                    console.log('✅ CKEditor initialized successfully');
                })
                .catch(error => {
                    console.error('❌ CKEditor initialization error:', error);
                });
        }

        // Initialize when modal is shown
        $(document).ready(function() {
            $('#articleModal').on('shown.bs.modal', function() {
                console.log('📝 Modal shown, initializing CKEditor...');
                setTimeout(initializeCKEditor, 200);
            });

            // Clean up when modal is hidden
            $('#articleModal').on('hidden.bs.modal', function() {
                if (editorInstance) {
                    editorInstance.destroy()
                        .then(() => {
                            editorInstance = null;
                            console.log('🗑️ CKEditor destroyed');
                        })
                        .catch(err => console.error('Error destroying editor:', err));
                }
            });
        });

        // Livewire event listeners
        document.addEventListener('livewire:init', () => {
            // Update editor content when editing
            Livewire.on('editor-content-updated', (event) => {
                console.log('📄 Updating editor content...');
                setTimeout(() => {
                    if (editorInstance) {
                        editorInstance.setData(event.content || '');
                        console.log('✅ Content updated');
                    }
                }, 250);
            });

            // Clear editor
            Livewire.on('clear-editor', () => {
                if (editorInstance) {
                    editorInstance.setData('');
                    console.log('🧹 Editor cleared');
                }
            });
        });
    </script>
@endpush
