<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Event extends Model
{
    /** @use HasFactory<\Database\Factories\EventsFactory> */
    use HasFactory;
    public $fillable = [
        'status',
        'title',
        'slug', 
        'category_id',
        'short_description',
        'long_description',  
        'start_date',
        'end_date',
        'start_time',
        'end_time',        
        'location',
        'city',
        'street', 
        'postal_code',
        'image_path'];

    public function category():BelongsTo{
        return $this->belongsTo(Category::class);
    }

    public function host():BelongsTo{
        return $this->belongsTo(User::class);
    }
    public function tickets():HasMany{
        return $this->hasMany(Ticket::class);
    }

    public function company(){
        return $this->belongsTo(Company::class);
    }

    public function participants():BelongsToMany{
        return $this->belongsToMany(Participant::class);
    }

    public function cheapestTicketPrice():?float{
        return $this->tickets()->min('price');
    }

    public function isFreeEvent():bool{
        return $this->tickets()->where('type', 'Free')->exists();
    }

    public function getEventImage(){
        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('events');
        
        if($disk->exists($this->image_path)){
            return $disk->url($this->image_path);
        }
        return asset($this->image_path);
    }

    protected function casts():array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

}
