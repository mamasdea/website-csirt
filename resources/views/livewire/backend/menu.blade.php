<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Menu Manage</h3>
            <button class="btn btn-outline-primary btn-sm" wire:click="resetInputFields" data-toggle="modal"
                data-target="#menuModal">
                <i class="fas fa-plus"></i> Tambah
            </button>
        </div>

        <div class="card-body">
            {{-- Search --}}
            <div class="d-flex flex-wrap justify-content-between mb-3">
                <div class="mb-2">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" placeholder="Search name/link"
                            wire:model.live="search">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <table class="table table-bordered">
                <thead class="text-center">
                    <tr>
                        <th style="width:70px">Order</th>
                        <th>Menu</th>
                        <th>Link</th>
                        <th style="width:120px">Type</th>
                        <th style="width:110px">Is Parent</th>
                        <th style="width:160px">Updated</th>
                        <th style="width:120px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $m)
                        <tr>
                            <td class="text-center align-middle">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button class="btn btn-light" wire:click="moveUp({{ $m['id'] }})"
                                        title="Up">
                                        <i class="fas fa-arrow-up"></i>
                                    </button>
                                    <button class="btn btn-light" wire:click="moveDown({{ $m['id'] }})"
                                        title="Down">
                                        <i class="fas fa-arrow-down"></i>
                                    </button>
                                </div>
                                <div class="small text-muted mt-1">#{{ $m['order'] }}</div>
                            </td>
                            <td>
                                <div class="font-weight-bold">
                                    {!! str_repeat('&mdash; ', $m['depth']) !!} {{ $m['name'] }}
                                </div>
                                <div class="text-muted small">
                                    Parent: {{ $m['parent_id'] ? '#' . $m['parent_id'] : '—' }}
                                </div>
                            </td>
                            <td class="small">
                                <a href="{{ $m['link'] ?? '#' }}" target="_blank">
                                    {{ $m['link'] ?: '-' }}
                                </a>
                            </td>
                            <td class="text-center">
                                @php
                                    $label = $m['type'] === 1 ? 'Route' : ($m['type'] === 2 ? 'Page Slug' : 'Default');
                                    $badge = $m['type'] === 1 ? 'info' : ($m['type'] === 2 ? 'primary' : 'secondary');
                                @endphp
                                <span class="badge badge-{{ $badge }}">{{ $label }}
                                    ({{ $m['type'] }})
                                </span>
                            </td>
                            <td class="text-center">
                                @if ($m['is_parent'])
                                    <span class="badge badge-success">Yes</span>
                                @else
                                    <span class="badge badge-secondary">No</span>
                                @endif
                            </td>
                            <td class="small text-muted">{{ optional($m['updated_at'])->diffForHumans() }}</td>
                            <td class="text-center">
                                <button class="btn btn-warning btn-sm" wire:click="edit({{ $m['id'] }})"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm"
                                    wire:click="delete_confirmation({{ $m['id'] }})" title="Hapus">
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
        </div>
    </div>

    {{-- Modal --}}
    <div wire:ignore.self class="modal fade" id="menuModal" tabindex="-1" aria-labelledby="menuModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">{{ $isEdit ? 'Edit' : 'Tambah' }} Menu</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>

                <div class="modal-body">
                    <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">

                        {{-- Name --}}
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Nama <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" wire:model.live="name"
                                    placeholder="Misal: Beranda">
                                @error('name')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Parent --}}
                            <div class="form-group col-md-6">
                                <label>Parent</label>
                                <select class="form-control" wire:model.live="parent_id">
                                    <option value="">— Top Level —</option>
                                    @foreach ($parentOptions as $opt)
                                        <option value="{{ $opt->id }}">{{ $opt->name }}</option>
                                    @endforeach
                                </select>
                                @error('parent_id')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Order & Is Parent --}}
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>Order</label>
                                <input type="number" min="0" class="form-control" wire:model.live="order"
                                    placeholder="otomatis jika kosong/0">
                                @error('order')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label>Is Parent?</label>
                                <select class="form-control" wire:model.live="is_parent">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                                @error('is_parent')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Type --}}
                            <div class="form-group col-md-4">
                                <label>Type (TINYINT)</label>
                                <select class="form-control" wire:model.live="type">
                                    @foreach ($typeOptions as $val => $label)
                                        <option value="{{ $val }}">{{ $label }}
                                            ({{ $val }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Dynamic link section --}}
                        <div class="form-row">
                            {{-- TYPE 1: ROUTE NAME --}}
                            @if ((int) $type === 1)
                                <div class="form-group col-md-12">
                                    <label>Route Name <span class="text-danger">*</span></label>
                                    <select class="form-control" wire:model.live="route_name">
                                        <option value="">— Pilih Route —</option>
                                        @foreach ($routeOptions as $name => $label)
                                            <option value="{{ $name }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Link akan diisi otomatis dari
                                        <code>route()</code>.</small>
                                    @error('route_name')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- TYPE 2: PAGE SLUG SELECT --}}
                            @elseif ((int) $type === 2)
                                <div class="form-group col-md-12">
                                    <label>Pilih Page (slug) <span class="text-danger">*</span></label>
                                    <select class="form-control" wire:model.live="page_slug">
                                        <option value="">— Pilih Page —</option>
                                        @foreach ($pageOptions as $slug => $title)
                                            <option value="{{ $slug }}">{{ $title }}
                                                ({{ $slug }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Link akan diisi otomatis ke
                                        <code>/&lt;slug&gt;</code>.</small>
                                    @error('page_slug')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- DEFAULT: MANUAL LINK --}}
                            @else
                                <div class="form-group col-md-12">
                                    <label>Link</label>
                                    <input type="text" class="form-control" wire:model.live="link"
                                        placeholder="/about atau https://...">
                                    @error('link')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle"></i>
                            <ul class="mb-0 pl-3">
                                <li><b>Type 1 (Route)</b>: isi <em>Route Name</em>, contoh <code>articles.index</code>.
                                </li>
                                <li><b>Type 2 (Page Slug)</b>: pilih page → link otomatis ke <code>/slug</code>.</li>
                                <li><b>Default</b>: isi link manual.</li>
                                <li>Order kosong/0 → otomatis di posisi terakhir dalam parent yang sama.</li>
                            </ul>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary"
                        wire:click.prevent="{{ $isEdit ? 'update' : 'store' }}" wire:loading.attr="disabled"
                        wire:target="store,update">
                        <span wire:loading.remove
                            wire:target="store,update">{{ $isEdit ? 'Update' : 'Simpan' }}</span>
                        <span wire:loading wire:target="store,update">
                            <i class="fas fa-spinner fa-spin"></i> Processing...
                        </span>
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>
