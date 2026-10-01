<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class Plan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'starts_at' => 'date',
            'ends_at' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Plan $plan) {
            $plan->validateTemporalScope();
        });
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    private function validateTemporalScope(): void
    {
        $start = $this->starts_at;
        $end = $this->ends_at;

        if (! $start || ! $end || $end->lessThanOrEqualTo($start)) {
            throw ValidationException::withMessages([
                'ends_at' => 'La fecha de término debe ser posterior a la fecha de inicio.',
            ]);
        }

        $oneYear = $start->copy()->addYear();
        $fiveYears = $start->copy()->addYears(5);

        $valid = match ($this->type) {
            'operational' => $end->lessThan($oneYear),
            'tactical' => $end->greaterThanOrEqualTo($oneYear)
                && $end->lessThan($fiveYears),
            'strategic' => $end->greaterThanOrEqualTo($fiveYears),
            default => false,
        };

        if (! $valid) {
            throw ValidationException::withMessages([
                'type' => 'El tipo de plan no corresponde a su horizonte temporal.',
            ]);
        }
    }
}
