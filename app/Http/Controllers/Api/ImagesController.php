<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\ImageRequest;
use Illuminate\Support\Facades\Storage;

class ImagesController extends Controller {
    public function store(ImageRequest $request) {
            $path = 'uploads';
            $cdn = 'https://f.zzwcdn.com/';
            $url = $cdn . Storage::putFile($path, $request->image);
            return response()->json([
                    'url'         => $url,
                    'status_code' => 201
                ])->setStatusCode(201);
    }
}
