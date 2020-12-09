<?php

use Illuminate\Support\Str;

define('PAGINATION_COUNT', 7);

function getFolder()
{
    return app()->getLocale() == 'ar' ? 'css-rtl' : 'css';
}


function uploadImage($folder,$image){
    $image->store('/', $folder);
    $filename = $image->hashName();
    $path = 'images/' . $folder . '/' . $filename;
    return  $path;
 }

 function deleteImage($model_image)
 {
     $image = Str::after($model_image, 'assets/');
     $image = public_path('assets/' . $image);
     if(file_exists($image)) {
         unlink($image); //delete from folder
         return true;
     }
     return false;
 }
