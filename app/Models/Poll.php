<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Poll extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'options_with_votes',
        'is_active',
    ];

    protected $casts = [
        'options_with_votes' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Relationship dengan voters
     */
    public function voters()
    {
        return $this->hasMany(PollVoter::class);
    }

    /**
     * Accessor untuk mendapatkan hasil polling dengan persentase
     */
    protected function results(): Attribute
    {
        return Attribute::make(
            get: function () {
                $options = $this->options_with_votes ?? [];
                $totalVotes = array_sum(array_column($options, 'votes'));

                return array_map(function ($option) use ($totalVotes) {
                    $percentage = $totalVotes > 0
                        ? ($option['votes'] / $totalVotes) * 100
                        : 0;

                    return [
                        'id' => $option['id'],
                        'text' => $option['text'],
                        'votes' => $option['votes'],
                        'percentage' => $percentage,
                    ];
                }, $options);
            }
        );
    }

    /**
     * Method untuk menambah vote
     */
    public function addVote(int $optionId): bool
    {
        $options = $this->options_with_votes;

        foreach ($options as $key => $option) {
            if ($option['id'] == $optionId) {
                $options[$key]['votes']++;
                $this->options_with_votes = $options;
                return $this->save();
            }
        }

        return false;
    }

    /**
     * Cek apakah IP sudah vote
     */
    public function hasVoted(string $ipAddress): bool
    {
        return $this->voters()
            ->where('ip_address', $ipAddress)
            ->exists();
    }

    /**
     * Scope untuk polling aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
