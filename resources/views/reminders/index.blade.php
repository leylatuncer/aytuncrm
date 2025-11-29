@extends('layouts.app')

@section('title', 'Hatırlatmalar')

@push('styles')
<style>
    /* Mobil için kart görünümü */
    @media (max-width: 767.98px) {
        .mobile-card {
            display: block;
        }
        .desktop-table {
            display: none;
        }
        .reminder-card {
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .reminder-card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
    }

    /* Desktop için tablo görünümü */
    @media (min-width: 768px) {
        .mobile-card {
            display: none;
        }
        .desktop-table {
            display: block;
        }
    }

    /* Başlık responsive */
    @media (max-width: 575.98px) {
        h2 {
            font-size: 1.3rem;
        }
    }

    /* Filtre kartı responsive */
    @media (max-width: 575.98px) {
        .filter-input {
            font-size: 0.9rem;
        }
        .form-label.small {
            font-size: 0.8rem;
        }
    }

    /* Buton grubu responsive */
    .action-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    @media (max-width: 575.98px) {
        .action-buttons {
            flex-direction: column;
        }
        .action-buttons .btn {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    {{-- Başlık ve Buton --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 mb-md-4 gap-2">
        <h2 class="mb-0"><i class="bi bi-bell"></i> Hatırlatmalar</h2>

        <div class="action-buttons w-100 w-md-auto">
            <a href="{{ route('reminders.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle"></i> Yeni Hatırlatma
            </a>
        </div>
    </div>

    {{-- Filtreleme Kartı --}}
    <div class="card mb-3 mb-md-4">
        <div class="card-header bg-light">
            <i class="bi bi-funnel"></i> Filtreleme
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reminders.index') }}" id="filterForm">
                <div class="row g-2 g-md-3">
                    <div class="col-6 col-md-4">
                        <label class="form-label small">Başlangıç Tarihi</label>
                        <input type="date"
                               name="start_date"
                               class="form-control form-control-sm filter-input"
                               value="{{ request('start_date') }}">
                    </div>

                    <div class="col-6 col-md-4">
                        <label class="form-label small">Bitiş Tarihi</label>
                        <input type="date"
                               name="end_date"
                               class="form-control form-control-sm filter-input"
                               value="{{ request('end_date') }}">
                    </div>

                    <div class="col-12 col-md-4">
                        <label class="form-label small">Durum</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="">Tümü</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Tamamlanmış</option>
                        </select>
                    </div>
                </div>

                {{-- Filtre Butonları --}}
                <div class="row mt-2 mt-md-3">
                    <div class="col-12">
                        <div class="d-flex flex-column flex-sm-row justify-content-end gap-2">
                            <a href="{{ route('reminders.index') }}" class="btn btn-secondary btn-sm">
                                <i class="bi bi-x-circle"></i> Temizle
                            </a>
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bi bi-search"></i> Filtrele
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- MOBİL: Kart Görünümü --}}
    <div class="mobile-card">
        @forelse($reminders as $reminder)
            <div class="card reminder-card border-start border-{{ $reminder->color }} border-4"
                 onclick="window.location='{{ route('reminders.show', $reminder) }}'">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-bold {{ $reminder->is_completed ? 'text-decoration-line-through text-muted' : '' }}">
                                {{ $reminder->title }}
                            </h6>
                            <small class="text-muted">
                                <i class="bi bi-calendar3"></i> {{ $reminder->reminder_date->format('d.m.Y H:i') }}
                            </small>
                        </div>
                        <span class="badge bg-{{ $reminder->color }} ms-2">
                            @if($reminder->is_completed)
                                <i class="bi bi-check2-all"></i> Tamamlandı
                            @elseif($reminder->color === 'danger')
                                <i class="bi bi-exclamation-triangle"></i> Acil
                            @elseif($reminder->color === 'warning')
                                <i class="bi bi-clock"></i> Yaklaşıyor
                            @else
                                <i class="bi bi-check-circle"></i> Normal
                            @endif
                        </span>
                    </div>

                    @if($reminder->note)
                        <hr class="my-2">
                        <p class="small text-muted mb-2">
                            {{ Str::limit($reminder->note, 80) }}
                        </p>
                    @endif

                    <div class="d-flex justify-content-between align-items-center gap-1 mt-2" onclick="event.stopPropagation();">
                        <div class="form-check">
                            <input class="form-check-input toggle-complete"
                                   type="checkbox"
                                   data-id="{{ $reminder->id }}"
                                   {{ $reminder->is_completed ? 'checked' : '' }}>
                            <label class="form-check-label small text-muted">
                                Tamamlandı
                            </label>
                        </div>

                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('reminders.show', $reminder) }}"
                               class="btn btn-sm btn-outline-info"
                               title="Görüntüle">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('reminders.edit', $reminder) }}"
                               class="btn btn-sm btn-outline-primary"
                               title="Düzenle">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('reminders.destroy', $reminder) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Bu hatırlatmayı silmek istediğinize emin misiniz?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Sil">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="card">
                <div class="card-body text-center text-muted py-4">
                    <i class="bi bi-inbox display-6 d-block mb-2"></i>
                    @if(request()->has('start_date') || request()->has('end_date') || request()->has('status'))
                        Filtreye uygun hatırlatma bulunamadı.
                    @else
                        Henüz hatırlatma eklenmemiş.
                    @endif
                </div>
            </div>
        @endforelse
    </div>

    {{-- DESKTOP: Tablo Görünümü --}}
    <div class="desktop-table">
        @if($reminders->count() > 0)
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th width="40">Durum</th>
                                    <th>Başlık</th>
                                    <th>Not</th>
                                    <th width="180">Tarih & Saat</th>
                                    <th width="100" class="text-center">Öncelik</th>
                                    <th width="180" class="text-center">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reminders as $reminder)
                                    <tr style="cursor: pointer;" onclick="window.location='{{ route('reminders.show', $reminder) }}'">
                                        <!-- Tamamlanma Durumu -->
                                        <td class="text-center" onclick="event.stopPropagation();">
                                            <div class="form-check">
                                                <input class="form-check-input toggle-complete"
                                                       type="checkbox"
                                                       data-id="{{ $reminder->id }}"
                                                       {{ $reminder->is_completed ? 'checked' : '' }}>
                                            </div>
                                        </td>

                                        <!-- Başlık -->
                                        <td>
                                            <strong class="{{ $reminder->is_completed ? 'text-decoration-line-through text-muted' : '' }}">
                                                {{ $reminder->title }}
                                            </strong>
                                        </td>

                                        <!-- Not -->
                                        <td>
                                            <small class="text-muted">
                                                {{ Str::limit($reminder->note, 50) }}
                                            </small>
                                        </td>

                                        <!-- Tarih & Saat -->
                                        <td>
                                            <i class="bi bi-calendar3"></i>
                                            {{ $reminder->reminder_date->format('d.m.Y H:i') }}
                                        </td>

                                        <!-- Öncelik Badge -->
                                        <td class="text-center">
                                            <span class="badge bg-{{ $reminder->color }}">
                                                @if($reminder->color === 'danger')
                                                    <i class="bi bi-exclamation-triangle"></i> Acil
                                                @elseif($reminder->color === 'warning')
                                                    <i class="bi bi-clock"></i> Yaklaşıyor
                                                @elseif($reminder->color === 'success')
                                                    <i class="bi bi-check-circle"></i> Normal
                                                @else
                                                    <i class="bi bi-check2-all"></i> Tamamlandı
                                                @endif
                                            </span>
                                        </td>

                                        <!-- İşlemler -->
                                        <td class="text-center" onclick="event.stopPropagation();">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('reminders.show', $reminder) }}"
                                                   class="btn btn-outline-info"
                                                   title="Görüntüle">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('reminders.edit', $reminder) }}"
                                                   class="btn btn-outline-primary"
                                                   title="Düzenle">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('reminders.destroy', $reminder) }}"
                                                      method="POST"
                                                      class="d-inline"
                                                      onsubmit="return confirm('Bu hatırlatmayı silmek istediğinize emin misiniz?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-outline-danger"
                                                            title="Sil">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="card">
                <div class="card-body text-center text-muted py-4">
                    <i class="bi bi-inbox display-6 d-block mb-2"></i>
                    @if(request()->has('start_date') || request()->has('end_date') || request()->has('status'))
                        Filtreye uygun hatırlatma bulunamadı.
                    @else
                        Henüz hatırlatma eklenmemiş.
                    @endif
                </div>
            </div>
        @endif
    </div>

    {{-- Pagination --}}
    @if($reminders->hasPages())
        <div class="d-flex justify-content-center mt-3 mt-md-4">
            {{ $reminders->withQueryString()->links() }}
        </div>
    @endif

    {{-- Toplam Kayıt Bilgisi --}}
    <div class="text-muted small mt-2 text-center text-md-start">
        Toplam {{ $reminders->total() }} hatırlatma kayıtlı.
    </div>
</div>

@push('scripts')
<script>
    // AJAX ile tamamlama durumunu değiştir
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.toggle-complete');

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const reminderId = this.dataset.id;
                const isChecked = this.checked;

                fetch(`/reminders/${reminderId}/toggle-complete`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Sayfayı yenile
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Hata:', error);
                    // Checkbox'ı eski haline getir
                    this.checked = !isChecked;
                    alert('Bir hata oluştu. Lütfen tekrar deneyin.');
                });
            });
        });
    });
</script>
@endpush
@endsection