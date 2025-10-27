<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">File Panduan</h3>
            <button class="btn btn-outline-primary btn-sm" wire:click="resetInputFields" data-toggle="modal"
                data-target="#filePanduanModal">
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
                        <input type="text" class="form-control" placeholder="Search title/file"
                            wire:model.live="search">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                    </div>
                </div>
            </div>

            <table class="table table-bordered">
                <thead class="text-center">
                    <tr>
                        <th style="width:60px">No</th>
                        <th>Title</th>
                        <th>File</th>
                        <th>Updated</th>
                        <th style="width:120px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $i => $fp)
                        <tr>
                            <td class="text-center">{{ ($rows->currentPage() - 1) * $rows->perPage() + $i + 1 }}</td>
                            <td>{{ $fp->title }}</td>
                            <td>
                                @if ($fp->file)
                                    <a href="{{ asset('storage/' . $fp->file) }}" target="_blank"
                                        class="btn btn-sm btn-info">
                                        <i class="fas fa-download"></i> Download File
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $fp->updated_at->diffForHumans() }}</td>
                            <td class="text-center">
                                <button class="btn btn-warning btn-sm" wire:click="edit({{ $fp->id }})"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm"
                                    wire:click="delete_confirmation({{ $fp->id }})" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-2">{{ $rows->links('livewire::bootstrap') }}</div>
        </div>
    </div>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="filePanduanModal" tabindex="-1"
        aria-labelledby="filePanduanModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filePanduanModalLabel">{{ $isEdit ? 'Edit' : 'Tambah' }} File Panduan
                    </h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>

                <div class="modal-body">
                    <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                        <div class="form-group">
                            <label>Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model.live="title">
                            @error('title')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>File (max 10MB) @if (!$isEdit)
                                    <span class="text-danger">*</span>
                                @endif
                            </label>
                            <input type="file" class="form-control-file" wire:model="file">
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
