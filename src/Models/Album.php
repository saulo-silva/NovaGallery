<?php

namespace SauloSilva\NovaGallery\Models;

use App\Models\Package;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Album extends Model
{
    use SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'el_albums';

    /**
     * @var array
     */
    protected $fillable = ['id', 'package_id', 'name', 'description', 'order'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class)->orderBy('position');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Return default photo for album
     *
     * @return string
     */
    public function getDefaultPhotoAttribute()
    {
        if ($this->photos()->count()) {
            return $this->photos->first()->image;
        }

        return '';
    }


    /**
     * Return default photo for album
     *
     * @return string
     */
    public function defaultPhoto()
    {
        if ($this->photos()->count()) {
            return $this->photos->first()->image;
        }

        return '';
    }

    /**
     * @return mixed
     */
    public function totalPhotos()
    {
        return $this->photos()->count();
    }
}
