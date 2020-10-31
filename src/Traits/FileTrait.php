<?php
/**
 * Created by PhpStorm.
 * User: damon
 * Date: 3/5/19
 * Time: 11:16 AM
 * Usage: when need self create for code column in table, use this trait
 */

namespace Toetet\FileInput\Traits\FileTrait;

use Toetet\FileInput\Models\File;
use JD\Cloudder\Facades\Cloudder;

trait FileTrait
{
    public function files()
    {
        return $this->morphMany(File::class, 'related');
    }

    public function deleteRecentImages($input_name = null)
    {
        if($this->files()->exists()){
            if($input_name)
                $recent_images = $this->files()->where('input_name', $input_name);
            else
                $recent_images = $this->files();

            $recent_images->get('file')->map(function($recent_image) {
                if(file_exists($recent_image->file)) 
                    unlink($recent_image->file);
                else
                    Cloudder::delete($recent_image->file);
            });

            $recent_images->delete();
        }
    }

    public function generateImageName($image, $input_name)
    {
        $date = date('Y-m-d_H-i-ss');
        $code = $this->generateImageCode();

        return $date.'_'.$input_name.'_'.$code.'.'.$image->getClientOriginalExtension();
    }

    public function generateImageCode($length = 16)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz_-@';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function storeInPublic($image, $image_name, $directory)
    {
        $image->move(public_path($directory), $image_name);  
        return $directory.'/'.$image_name;  
    }

    public function storeInCloudinary($image, $image_name, $directory)
    {
        $image_file = $image->getRealPath();

        Cloudder::upload($image_file, $image_name,[
            'folder' => $directory.'/'
        ]);

        return Cloudder::getPublicId();
    }

    // @storage_type 'cloudinary'|'public'
    public function uploadImage($directory, $image, $input_name, $storage_type = 'public', $replace = true)
    {
        if($image) {
            if($replace) $this->deleteRecentImages($input_name);

            $image_name = $this->generateImageName($image, $input_name);

            if($storage_type == 'cloudinary')
                $uploaded_image = $this->storeInCloudinary($image, $image_name, $directory);
            else
                $uploaded_image = $this->storeInPublic($image, $image_name, $directory);

            $this->files()->createMany([
                [
                    'file' => $uploaded_image,
                    'input_name' => $input_name,
                ]
            ]);

            return $uploaded_image;
        }
    }

    // @options arr "options from cloudinary docs"
    public function getFile($placeholder = 'image', $input_name, $options = ['width' => 300, 'height' => 300])
    {
        if($file = $this->files()->where('input_name', $input_name)->first()) {
            if(file_exists($file->file))
                return $file->file;
            else
                return Cloudder::show($file->file, $options);
        } else {
            return 'assets/images/default/'.$placeholder.'.png';
        }
    }

    public function getFiles()
    {
        if($this->files()->first())
            return $this->files()->get();
        else
            return null;
    }
}