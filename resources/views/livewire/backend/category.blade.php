<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Kategori</h3>
            <button class="btn btn-outline-primary btn-sm" wire:click="resetInputFields" data-toggle="modal"
                data-target="#categoryModal">
                <i class="fas fa-plus"></i> Tambah Kategori
            </button>
        </div>

        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mt-2 mb-2">
                <div class="col-lg-2">
                    <div class="d-flex align-items-center">
                        <label class="col-form-label mr-2">Show:</label>
                        <select wire:model.live="paginate" class="form-control form-control-sm">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="15">15</option>
                            <option value="20">20</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" placeholder="Search" wire:model.live="search">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                    </div>
                </div>
            </div>

            <table class="table table-bordered mt-3">
                <thead>
                    <tr class="text-center">
                        <th style="width:70px;">No</th>
                        <th>Nama</th>
                        <th>Slug</th>
                        <th style="width:140px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $item)
                        <tr>
                            <td class="text-center">
                                {{ ($rows->currentPage() - 1) * $rows->perPage() + $loop->iteration }}
                            </td>
                            <td>{{ $item->name }}</td>
                            <td class="text-muted">{{ $item->slug }}</td>
                            <td class="text-center">
                                <button wire:click="edit({{ $item->id }})" class="btn btn-warning btn-sm"
                                    data-toggle="tooltip" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button wire:click="delete_confirmation({{ $item->id }})"
                                    class="btn btn-danger btn-sm" data-toggle="tooltip" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Belum ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="container mt-2 mb-2">
                {{ $rows->links('livewire::bootstrap') }}
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="categoryModalLabel">{{ $isEdit ? 'Edit Kategori' : 'Tambah Kategori' }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>

                <div class="modal-body">
                    <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text" id="name" class="form-control" wire:model.live="name">
                            @error('name')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        {{-- <div class="form-group">
                            <label for="slug">Slug (opsional)</label>
                            <input type="text" id="slug" class="form-control" wire:model.live="slug"
                                placeholder="otomatis dari nama jika kosong">
                            @error('slug')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div> --}}
                    </form>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button class="btn btn-primary" wire:click.prevent="{{ $isEdit ? 'update' : 'store' }}">
                        {{ $isEdit ? 'Update' : 'Simpan' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Tooltip & fokus input --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#categoryModal').on('shown.bs.modal', function() {
            document.getElementById('name')?.focus();
        });
        $(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    });
</script>
