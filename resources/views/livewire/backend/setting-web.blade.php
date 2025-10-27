<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Web Settings</h3>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="save">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="name">Nama Aplikasi/Situs</label>
                        <input type="text" class="form-control" id="name" wire:model.live="name">
                        @error('name')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="email">Email Kontak</label>
                        <input type="email" class="form-control" id="email" wire:model.live="email">
                        @error('email')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi Singkat/Tagline</label>
                    <textarea class="form-control" id="description" rows="3" wire:model.live="description"></textarea>
                    @error('description')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="no_telp">Nomor Telepon</label>
                        <input type="text" class="form-control" id="no_telp" wire:model.live="no_telp">
                        @error('no_telp')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="link_aduan">Link Aduan</label>
                        <input type="url" class="form-control" id="link_aduan" wire:model.live="link_aduan">
                        @error('link_aduan')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="address">Alamat Fisik</label>
                    <textarea class="form-control" id="address" rows="3" wire:model.live="address"></textarea>
                    @error('address')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="maps_embed">Kode Embed Google Maps</label>
                    <textarea class="form-control" id="maps_embed" rows="5" wire:model.live="maps_embed"></textarea>
                    @error('maps_embed')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group col-md-9">
                        <label>Logo (opsional, max 2MB)</label>
                        <input type="file" class="form-control-file" wire:model="logo" accept="image/*"
                            id="logo-input">
                        @error('logo')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                        <div wire:loading wire:target="logo" class="text-info small mt-1">
                            <i class="fas fa-spinner fa-spin"></i> Uploading...
                        </div>

                        @if ($current_logo)
                            <small class="text-muted d-block mt-1">
                                Current logo path: {{ $current_logo }}
                            </small>
                        @endif
                    </div>
                    <div class="form-group col-md-3">
                        @if ($logo)
                            <label class="d-block text-center">Preview Baru</label>
                            <img src="{{ $logo->temporaryUrl() }}" class="img-thumbnail"
                                style="max-height:120px;width:100%;object-fit:cover;">
                        @elseif(!empty($current_logo))
                            <label class="d-block text-center">Logo Saat Ini</label>
                            <img src="{{ asset('storage/' . $current_logo) }}" class="img-thumbnail"
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

                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="save">
                    <span wire:loading.remove wire:target="save">Simpan Pengaturan</span>
                    <span wire:loading wire:target="save">
                        <i class="fas fa-spinner fa-spin"></i> Processing...
                    </span>
                </button>
            </form>
        </div>
    </div>
</div>

@push('js')
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('clear-logo-input', () => {
                document.getElementById('logo-input').value = '';
            });
        });
    </script>
@endpush
