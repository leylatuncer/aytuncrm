@extends('layouts.app')

@section('title', 'Yeni Hatırlatma Ekle')

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

    /* Alert liste responsive */
    @media (max-width: 575.98px) {
        .alert ul li {
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
                <h2 class="mb-0"><i class="bi bi-bell-fill"></i> Yeni Hatırlatma Ekle</h2>
                <a href="{{ route('reminders.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Geri Dön
                </a>
            </div>

            {{-- Form --}}
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('reminders.store') }}" method="POST">
                        @csrf

                        {{-- Başlık --}}
                        <div class="mb-3">
                            <label for="title" class="form-label">
                                Başlık <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control form-control-sm @error('title') is-invalid @enderror"
                                   id="title"
                                   name="title"
                                   value="{{ old('title') }}"
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
                                   value="{{ old('reminder_date') }}"
                                   min="{{ now()->format('Y-m-d\TH:i') }}"
                                   required>
                            @error('reminder_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-1">
                                <i class="bi bi-info-circle"></i> Geçmiş tarih seçilemez
                            </small>
                        </div>

                        {{-- Not --}}
                        <div class="mb-3">
                            <label for="note" class="form-label">Not (Opsiyonel)</label>
                            <textarea class="form-control form-control-sm @error('note') is-invalid @enderror"
                                      id="note"
                                      name="note"
                                      rows="4"
                                      placeholder="Hatırlatma ile ilgili detaylı bilgi girebilirsiniz...">{{ old('note') }}</textarea>
                            @error('note')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Bilgilendirme --}}
                        <div class="alert alert-info">
                            <i class="bi bi-lightbulb"></i> <strong>Öncelik Renkleri:</strong>
                            <ul class="mb-0 mt-2 small">
                                <li><span class="badge bg-danger">Kırmızı</span> - 3 gün veya daha az kalan hatırlatmalar</li>
                                <li><span class="badge bg-warning text-dark">Sarı</span> - 1 hafta veya daha az kalan hatırlatmalar</li>
                                <li><span class="badge bg-success">Yeşil</span> - 1 haftadan fazla süre kalan hatırlatmalar</li>
                            </ul>
                        </div>

                        {{-- Butonlar --}}
                        <div class="btn-group-responsive d-flex justify-content-end gap-2">
                            <a href="{{ route('reminders.index') }}" class="btn btn-secondary btn-sm">
                                <i class="bi bi-x-circle"></i> İptal
                            </a>
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bi bi-save"></i> Kaydet
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection