@extends('layouts.app')

@section('title', 'Hatırlatma Düzenle')

@push('styles')
<style>
    /* Başlık responsive */
    @media (max-width: 575.98px) {
        h2 {
            font-size: 1.3rem;
        }
    }

    /* Kart header responsive */
    @media (max-width: 575.98px) {
        .card-header {
            font-size: 0.9rem;
            padding: 0.5rem 0.75rem;
        }
    }

    /* Form label responsive */
    @media (max-width: 575.98px) {
        .form-label {
            font-size: 0.85rem;
            margin-bottom: 0.25rem;
        }
    }

    /* Buton grubu responsive */
    @media (max-width: 575.98px) {
        .btn-group-responsive {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            width: 100%;
        }
        .btn-group-responsive .btn {
            width: 100%;
        }
    }

    /* Alert responsive */
    @media (max-width: 575.98px) {
        .alert {
            font-size: 0.85rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-lg-8 offset-lg-2">
            {{-- Başlık --}}
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-3 mb-md-4 gap-2">
                <h2 class="mb-0"><i class="bi bi-pencil-square"></i> Hatırlatma Düzenle</h2>
                <a href="{{ route('reminders.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Geri Dön
                </a>
            </div>

            {{-- Form --}}
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('reminders.update', $reminder) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Başlık --}}
                        <div class="mb-3">
                            <label for="title" class="form-label">
                                Başlık <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control form-control-sm @error('title') is-invalid @enderror"
                                   id="title"
                                   name="title"
                                   value="{{ old('title', $reminder->title) }}"
                                   placeholder="Örn: Müşteri toplantısı"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tarih ve Saat --}}
                        <div class="mb-3">
                            <label for="reminder_date" class="form-label">
                                Tarih ve Saat <span class="text-danger">*</span>
                            </label>
                            <input type="datetime-local"
                                   class="form-control form-control-sm @error('reminder_date') is-invalid @enderror"
                                   id="reminder_date"
                                   name="reminder_date"
                                   value="{{ old('reminder_date', $reminder->reminder_date->format('Y-m-d\TH:i')) }}"
                                   required>
                            @error('reminder_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-1">
                                <i class="bi bi-info-circle"></i> Düzenleme sırasında geçmiş tarih seçilebilir
                            </small>
                        </div>

                        {{-- Not --}}
                        <div class="mb-3">
                            <label for="note" class="form-label">Not (Opsiyonel)</label>
                            <textarea class="form-control form-control-sm @error('note') is-invalid @enderror"
                                      id="note"
                                      name="note"
                                      rows="4"
                                      placeholder="Hatırlatma ile ilgili detaylı bilgi girebilirsiniz...">{{ old('note', $reminder->note) }}</textarea>
                            @error('note')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tamamlanma Durumu --}}
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input"
                                       type="checkbox"
                                       id="is_completed"
                                       name="is_completed"
                                       value="1"
                                       {{ old('is_completed', $reminder->is_completed) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_completed">
                                    <i class="bi bi-check-circle"></i> Tamamlandı olarak işaretle
                                </label>
                            </div>
                        </div>

                        {{-- Mevcut Durum Bilgisi --}}
                        <div class="alert alert-{{ $reminder->color }} mb-3">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-info-circle me-2 mt-1"></i>
                                <div>
                                    <strong class="d-block mb-1">Mevcut Durum:</strong>
                                    @if($reminder->is_completed)
                                        <span class="badge bg-secondary">Tamamlanmış</span>
                                    @else
                                        @if($reminder->color === 'danger')
                                            <span class="badge bg-danger">Acil - 3 gün veya daha az kaldı</span>
                                        @elseif($reminder->color === 'warning')
                                            <span class="badge bg-warning text-dark">Yaklaşıyor - 1 hafta veya daha az kaldı</span>
                                        @else
                                            <span class="badge bg-success">Normal - 1 haftadan fazla süre var</span>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Butonlar --}}
                        <div class="d-flex flex-column flex-sm-row justify-content-between gap-2">
                            <div class="order-2 order-sm-1">
                                <a href="{{ route('reminders.index') }}" class="btn btn-secondary btn-sm w-100 w-sm-auto">
                                    <i class="bi bi-x-circle"></i> İptal
                                </a>
                            </div>
                            <div class="d-flex flex-column flex-sm-row gap-2 order-1 order-sm-2">
                                {{-- Sil Butonu --}}
                                <button type="button"
                                        class="btn btn-danger btn-sm w-100 w-sm-auto"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteModal">
                                    <i class="bi bi-trash"></i> Sil
                                </button>
                                {{-- Güncelle Butonu --}}
                                <button type="submit" class="btn btn-primary btn-sm w-100 w-sm-auto">
                                    <i class="bi bi-save"></i> Güncelle
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Silme Onay Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="bi bi-exclamation-triangle"></i> Hatırlatmayı Sil
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">
                    <strong>"{{ $reminder->title }}"</strong> başlıklı hatırlatmayı silmek istediğinize emin misiniz?
                </p>
                <p class="text-muted small mb-0 mt-2">Bu işlem geri alınamaz.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> İptal
                </button>
                <form action="{{ route('reminders.destroy', $reminder) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="bi bi-trash"></i> Evet, Sil
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection