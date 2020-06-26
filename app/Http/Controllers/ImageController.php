<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class ImageController extends Controller
{
    public function store(Request $request)
    {
        $imageRequest = $request->file('image');

        $img = Image::make($imageRequest);

        $pixelated = $this->pixelate($img);

        $mask = Image::canvas($img->width(), $img->height(), 'rgba(1,1,1,0)');

        $mask->rectangle(0, 0, $img->width(), 500, function($draw){
            $draw->background('#cccccc');
        });

        $mask->ellipse(500, 400, 500, 1200, function($draw){
            $draw->background('#cccccc');
        });

        $pixelated->mask($mask, true);

        $img->insert($pixelated, 'top-left', 0, 0);

        $img->save(storage_path('app/public/asd/'. $imageRequest->getClientOriginalName()));

        return redirect()->back();
    }

    /**
     * @param \Intervention\Image\Image $img
     * @return \Intervention\Image\Image
     */
    protected function pixelate(\Intervention\Image\Image $img): \Intervention\Image\Image
    {
        $pixelated = clone $img;
        $pixelated->pixelate(20);
        return $pixelated;
    }
}
