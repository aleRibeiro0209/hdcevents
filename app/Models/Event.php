<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Event extends Model
{
    use HasFactory;

    protected $casts = [
        'items' => 'array'
    ];

    protected $dates = ['date'];

    protected $guarded = [];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function users() {
        return $this->belongsToMany('App\Models\User')->withPivot('valor_pago');
    }

    public function feedbacks(){
        return $this->hasMany('App\Models\Feedback');
    }

    // Método para buscar ingressos vendidos nos últimos 12 meses
    public function ticketsSoldLast12Months()
    {
        $last12Months = Carbon::now()->subMonths(12);

        return DB::table('event_user')
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('COUNT(*) as total_tickets')
            )
            ->where('event_id', $this->id)
            ->where('created_at', '>=', $last12Months)
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->get();
    }
}
