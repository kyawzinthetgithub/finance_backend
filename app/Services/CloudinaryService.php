<?php

namespace App\Services;
use App\Models\Image;
use Cloudinary\Cloudinary;

class CloudinaryService
{
    protected $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary();
    }

    public function upload($file)
    {
        $uploadedFile = $this->store($file);
        $image = new Image();
        $image->image_url = $uploadedFile['secure_url'];
        $image->public_id = $uploadedFile['public_id'];
        $image->save();

        return $image;
    }

    public function update(Image $image, $file)
    {
        // Replace the old image with the new one
        $this->cloudinary->uploadApi()->destroy($image->public_id); // remove the old image
        $uploadedFile = $this->store($file);

        $image->image_url = $uploadedFile['secure_url'];
        $image->public_id = $uploadedFile['public_id'];
        $image->save();

        return $image;
    }

    public function delete(Image $image)
    {
        // Delete the image from Cloudinary
        $this->cloudinary->uploadApi()->destroy($image->public_id);

        // Remove the image record from the database
        return $image->delete();
    }

    protected function store($file)
    {
        $uploadedFile = $this->cloudinary->uploadApi()->upload($file->getRealPath(), [
            'folder' => 'daily_expend',
            'quality' => '50',
        ]);
        return $uploadedFile;
    }
}
