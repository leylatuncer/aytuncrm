<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReminderController extends Controller
{
    /**
     * Hatırlatma listesi
     */
    public function index(Request $request)
    {
        $query = Reminder::query();

        // Tarih filtresi
        if ($request->filled('start_date')) {
            $query->where('reminder_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('reminder_date', '<=', $request->end_date . ' 23:59:59');
        }

        // Durum filtresi
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'completed') {
                $query->where('is_completed', true);
            }
        }

        $reminders = $query->orderByDate('asc')->paginate(15);

        return view('reminders.index', compact('reminders'));
    }

    /**
     * Yeni hatırlatma formu
     */
    public function create()
    {
        return view('reminders.create');
    }

    /**
     * Hatırlatma kaydet
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'note' => 'nullable|string',
            'reminder_date' => 'required|date|after_or_equal:today',
        ], [
            'title.required' => 'Başlık alanı zorunludur.',
            'title.max' => 'Başlık en fazla 255 karakter olabilir.',
            'reminder_date.required' => 'Tarih ve saat alanı zorunludur.',
            'reminder_date.date' => 'Geçerli bir tarih giriniz.',
            'reminder_date.after_or_equal' => 'Geçmiş tarih seçilemez.',
        ]);

        Reminder::create($validated);

        return redirect()->route('reminders.index')->with('success', 'Hatırlatma başarıyla oluşturuldu.');
    }

    /**
     * Hatırlatma detayını göster
     */
    public function show(Reminder $reminder)
    {
        return view('reminders.show', compact('reminder'));
    }

    /**
     * Hatırlatma düzenleme formu
     */
    public function edit(Reminder $reminder)
    {
        return view('reminders.edit', compact('reminder'));
    }

    /**
     * Hatırlatma güncelle
     */
    public function update(Request $request, Reminder $reminder)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'note' => 'nullable|string',
            'reminder_date' => 'required|date',
            'is_completed' => 'boolean',
        ], [
            'title.required' => 'Başlık alanı zorunludur.',
            'title.max' => 'Başlık en fazla 255 karakter olabilir.',
            'reminder_date.required' => 'Tarih ve saat alanı zorunludur.',
            'reminder_date.date' => 'Geçerli bir tarih giriniz.',
        ]);

        $reminder->update($validated);

        return redirect()->route('reminders.index')->with('success', 'Hatırlatma başarıyla güncellendi.');
    }

    /**
     * Hatırlatma sil
     */
    public function destroy(Reminder $reminder)
    {
        $reminder->delete();

        return redirect()->route('reminders.index')->with('success', 'Hatırlatma başarıyla silindi.');
    }

    /**
     * Hatırlatma tamamla/tamamlama kaldır (AJAX)
     */
    public function toggleComplete(Reminder $reminder)
    {
        $reminder->update([
            'is_completed' => !$reminder->is_completed
        ]);

        return response()->json([
            'success' => true,
            'is_completed' => $reminder->is_completed,
            'message' => $reminder->is_completed ? 'Hatırlatma tamamlandı.' : 'Hatırlatma aktif duruma getirildi.'
        ]);
    }
}