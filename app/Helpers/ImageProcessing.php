<?php
namespace App\Helpers;

class ImageProcessing
{
    static function saveBase64ImagePng($base64Image)
    {
        if (file_exists(public_path($base64Image))) { 
            return $base64Image;                 
        }
        //set name of the image file

        $fileName =  ImageProcessing::generateRandomString().'.png';

        $base64Image = trim($base64Image);
        $base64Image = str_replace('data:image/png;base64,', '', $base64Image);
        $base64Image = str_replace('data:image/jpg;base64,', '', $base64Image);
        $base64Image = str_replace('data:image/jpeg;base64,', '', $base64Image);
        $base64Image = str_replace('data:image/gif;base64,', '', $base64Image);
        $base64Image = str_replace(' ', '+', $base64Image);

        $imageData = base64_decode($base64Image);
        $publicPath = public_path('uploads');

        //mkdir($publicPath, 0777, true);

        if (!file_exists($publicPath)) {
            mkdir($publicPath, 0777, true);
        }
        file_put_contents(public_path('uploads/'.$fileName), $imageData);
        return '/uploads/'.$fileName;
    }

    static function generateRandomString($length = 10) 
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) 
        {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}