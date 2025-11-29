@extends('layouts.app')

@section('title', 'Hatırlatma Detayı')

@push('styles')
<style>
    /* Başlık responsive */
    @media (max-width: 575.98px) {
        h2 {
            font-size: 1.3rem;
        }
        h4 {
            font-size: 1.1rem;
        }
        .text-muted.small {
            font-size: 0.75rem;
        }
    }

    /* Kart header responsive */
    @media (max-width: 575.98px) {
        .card-header {
            font-size: 0.9rem;
            padding: 0.5rem 0.75rem;
        }
    }

    /* Label ve değerler responsive */
    @media (max-width: 575.98px) {
        h6.text-muted {
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
        }
        .fs-5 {
            font-size: 1rem !important;
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

    /* Badge responsive */
    @media (max-width: 575.98px) {
        .badge.fs-6 {
            font-size: 0.8rem !important;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    {{-- Başlık --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-3 mb-md-4 gap-2">
        <div class="w-100 w-sm-auto">
            <h2 class="mb-0"><i class="bi bi-eye"></i> Hatırlatma Detayı</h2>
        </div>
        <a href="{{ route('reminders.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Geri Dön
        </a>
    </div>

    {{-- Detay Kartı --}}
    <div class="card">
        <div class="card-header bg-{{ $reminder->color }} text-white">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h4 class="mb-0">
                    <i class="bi bi-bell-fill"></i> {{ $reminder->title }}
                </h4>
                <span class="badge bg-white text-{{ $reminder->color }}">
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
        </div>
        <div class="card-body">
            <!-- Tarih ve Saat -->
            <div class="row g-2 g-md-3 mb-3 mb-md-4">
                <div class="col-12 col-md-6">
                    <h6 class="text-muted mb-2">
                        <i class="bi bi-calendar3"></i> Tarih ve Saat
                    </h6>
                    <p class="fs-5 mb-0">
                        {{ $reminder->reminder_date->format('d.m.Y') }}
                        <span class="text-muted">-</span>
                        {{ $reminder->reminder_date->format('H:i') }}
                    </p>
                    <small class="text-muted">
                        {{ $reminder->reminder_date->diffForHumans() }}
                    </small>
                </div>
                <div class="col-12 col-md-6">
                    <h6 class="text-muted mb-2">
                        <i class="bi bi-clock-history"></i> Oluşturulma Tarihi
                    </h6>
                    <p class="mb-0">
                        {{ $reminder->created_at->format('d.m.Y H:i') }}
                    </p>
                    <small class="text-muted">
                        {{ $reminder->created_at->diffForHumans() }}
                    </small>
                </div>
            </div>

            <hr>

            <!-- Not -->
            <div class="mb-3 mb-md-4">
                <h6 class="text-muted mb-3">
                    <i class="bi bi-journal-text"></i> Not
                </h6>
                @if($reminder->note)
                    <div class="alert alert-light border">
                        <p class="mb-0 small" style="white-space: pre-line;">{{ $reminder->note }}</p>
                    </div>
                @else
                    <p class="text-muted fst-italic small">Not girilmemiş.</p>
                @endif
            </div>

            <hr>

            <!-- Durum Bilgisi -->
            <div class="mb-3 mb-md-4">
                <h6 class="text-muted mb-3">
                    <i class="bi bi-info-circle"></i> Durum Bilgisi
                </h6>
                <div class="row g-2 g-md-3">
                    <div class="col-6 col-md-6">
                        <div class="d-flex align-items-center mb-2">
                            @if($reminder->is_completed)
                                <i class="bi bi-check-circle-fill text-success fs-4 me-2"></i>
                                <span class="small">Tamamlandı</span>
                            @else
                                <i class="bi bi-circle text-secondary fs-4 me-2"></i>
                                <span class="small">Aktif</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-6 col-md-6">
                        @php
                            $now = \Carbon\Carbon::now();
                            $daysUntil = $now->diffInDays($reminder->reminder_date, false);
                        @endphp
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-hourglass-split fs-4 me-2"></i>
                            <span class="small">
                                @if($daysUntil < 0)
                                    {{ abs($daysUntil) }} gün geçti
                                @elseif($daysUntil == 0)
                                    Bugün
                                @else
                                    {{ $daysUntil }} gün kaldı
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Son Güncelleme -->
            @if($reminder->updated_at != $reminder->created_at)
                <div class="alert alert-info mb-0">
                    <small>
                        <i class="bi bi-pencil"></i>
                        Son güncelleme: {{ $reminder->updated_at->format('d.m.Y H:i') }}
                        ({{ $reminder->updated_at->diffForHumans() }})
                    </small>
                </div>
            @endif
        </div>
        <div class="card-footer bg-light">
            <div class="btn-group-responsive d-flex justify-content-between flex-wrap gap-2">
                <div>
                    <a href="{{ route('reminders.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Listeye Dön
                    </a>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('reminders.edit', $reminder) }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-pencil"></i> Düzenle
                    </a>
                    <button type="button"
                            class="btn btn-danger btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#deleteModal">
                        <i class="bi bi-trash"></i> Sil
                    </button>
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