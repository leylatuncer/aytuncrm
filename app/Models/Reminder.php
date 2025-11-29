<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'note',
        'reminder_date',
        'is_completed'
    ];

    protected $casts = [
        'reminder_date' => 'datetime',
        'is_completed' => 'boolean'
    ];

    /**
     * Sadece aktif (tamamlanmamış) hatırlatmalar
     */
    public function scopeActive($query)
    {
        return $query->where('is_completed', false);
    }

    /**
     * Tarihe göre sıralama
     */
    public function scopeOrderByDate($query, $direction = 'asc')
    {
        return $query->orderBy('reminder_date', $direction);
    }

    /**
     * Tarih aralığına göre filtreleme
     */
    public function scopeFilterByDate($query, $startDate = null, $endDate = null)
    {
        if ($startDate) {
            $query->where('reminder_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('reminder_date', '<=', $endDate);
        }
        return $query;
    }

    /**
     * Hatırlatmanın rengini belirle (3 gün kırmızı, 1 hafta sarı, daha fazla yeşil)
     */
    public function getColorAttribute()
    {
        if ($this->is_completed) {
            return 'secondary'; // Tamamlanmış - gri
        }

        $now = Carbon::now();
        $reminderDate = Carbon::parse($this->reminder_date);
        $daysUntil = $now->diffInDays($reminderDate, false);

        if ($daysUntil < 0) {
            return 'danger'; // Geçmiş - kırmızı
        } elseif ($daysUntil <= 3) {
            return 'danger'; // 3 gün veya daha az - kırmızı
        } elseif ($daysUntil <= 7) {
            return 'warning'; // 1 hafta veya daha az - sarı
        } else {
            return 'success'; // Daha uzun süre - yeşil
        }
    }

    /**
     * Kırmızı (acil) hatırlatma sayısını getir
     */
    public static function getUrgentCount()
    {
        $now = Carbon::now();
        $threeDaysLater = Carbon::now()->addDays(3);

        return self::where('is_completed', false)
            ->where('reminder_date', '<=', $threeDaysLater)
            ->count();
    }
}