<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Company extends Model
{
    use HasFactory;
    protected $fillable = ['name','email', 'slug', 'pricing_plan_id'];
    
    public function users():HasMany{
        return $this->hasMany(User::class);
    }

    // belongsTo omdat de foreign key op de huidige table (companies) zit
    public function owner():BelongsTo{ 
        return $this->belongsTo(User::class);
    }

    public function pricingPlan():BelongsTo{
        return $this->belongsTo(PricingPlan::class);
    }

    public function events():HasMany{
        return $this->hasMany(Event::class);
    }

    public function isPremium():bool{
        return $this->pricingPlan->name === 'premium';
    }

}
