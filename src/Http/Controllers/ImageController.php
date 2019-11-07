<?php

namespace App\Http\Controllers;

use App\Models\ContentImage;
use App\Models\ContentImgDimention;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use File;


class ImageController extends Controller
{
    function saveImage($image, $type, $height = 237, $width = 109)
    {
        if (!$image) {
            return '';
        }

        $filename = rand(1, 9999) . time() . '.' . $image->getClientOriginalExtension();

        $path = public_path('imgs/' . $type);
        $resizePath = $path . '/thumb';
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
            mkdir($resizePath, 0777, true);
        }

        $img = Image::make($image->getRealPath());
        $img->save($path . '/' . $filename);
          // $img->resize($height, $width);

        $img->resize(210, null, function ($constraint) {
                   $constraint->aspectRatio();
               });

        $img->save($resizePath . '/' . $filename);


        return $filename;
    }


    function updateImage($item, $image, $type,$height = 237, $width = 109)
    {
        $filename = rand(1, 9999) . time() . '.' . $image->getClientOriginalExtension();

        $path = public_path('imgs/' . $type);
        $resizePath = $path . '/thumb';

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        if (!file_exists($resizePath)) {
            mkdir($resizePath, 0777, true);
        }
        if (file_exists($path . '/' . $item->image)) {
            File::delete($path . '/' . $item->image);
        }

        if (file_exists($resizePath . '/' . $item->image)) {
            File::delete($resizePath . '/' . $item->image);
        }


        $img = Image::make($image->getRealPath());
        $img->save($path . '/' . $filename);
        $img->resize(210, null, function ($constraint) {
                   $constraint->aspectRatio();
               });
        // $img->resize($height, $width);
        $img->save($resizePath . '/' . $filename);

        return $filename;
    }




  public function store()
    {
        $cropperImage = request()->croppedImage; //temp file
        $type = request()->type;
        $filename = request()->filename;

        $path = public_path('imgs/' . $type . '/thumb/' . $filename);
        $beforeEdit = public_path('imgs/' . $type . '/' . $filename);
        if (!File::exists($beforeEdit)) {
            File::move($path, $beforeEdit);  //old file , new file
        }

        move_uploaded_file($cropperImage, $path);

        return url('public/imgs/' . $type . '/' . $filename);
    }


    /**
     * Function to order Images
     * @return mixed
     */
    public function reOrder()
    {
        return reOrderImage();
    }

    /**
     * @param Image $image
     * @param $type
     * @return string
     */
    public function delete(\App\Image $image, $type)
    {
      // dd($image);

        if ($image->delete()) {
            deleteImageFile($image, $type);
            return "success";
        }

    }


}
