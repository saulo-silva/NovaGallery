<?php

namespace SauloSilva\NovaGallery\Http\Observers;


use App\Models\Product;
use SauloSilva\NovaGallery\Models\Photo;

class PhotoObserver
{
    /**
     * @param Photo $photo
     */
    public function created(Photo $photo)
    {
        $package_id = $photo->album->package->id;
        $description = $photo->album->description;
        $price = $photo->album->package->price;
        $actived = $photo->album->package->actived;

        Product::create([
            'package_id' => $package_id,
            'photo_id' => $photo->id,
            'name' => $photo->name,
            'description' => $description,
            'price' => $price,
            'quantity' => 1,
            'position' => $photo->position,
            'actived' => $actived,
        ]);

        return true;
    }

    /**
     * Handle the Page "updating" event.
     *
     * Saves no column fields into data column
     * and translated fields into extras column
     *
     * @param  SauloSilva\NovaGallery\Models\Album  $album
     *
     * @return bool
     */
    public function updated(Photo $photo)
    {
        Product::where('photo_id', $photo->id)->update([
            'name' => $photo->name,
            'position' => $photo->position,
        ]);

        return true;
    }

    /**
     * Delete files when Album is deleted
     *
     * @param Photo $photo
     */
    public function deleted(Photo $photo)
    {
        logger('deleted');
        return true;
    }
}
