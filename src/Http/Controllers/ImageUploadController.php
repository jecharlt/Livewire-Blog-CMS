<?php

namespace Jecharlt\LivewireBlogCMS\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Encoders\WebpEncoder;

class ImageUploadController extends Controller
{
    public function upload(Request $request) {
        if (!Auth::guard('blog')->check()) {
            return response()->json([
                'error' => [
                    'message' => 'Permission Denied'
                ]
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'upload' => 'required|image|max:2048|mimes:jpeg,png,gif,bmp,webp,tiff',
            'type' => 'required'
        ], [
            'upload.required' => 'an image file is required.',
            'upload.image' => 'the file uploaded must be an image.',
            'upload.max' => 'the image must not be larger than 2MB.',
            'upload.mimes' => 'only jpeg, png, gif, bmp, webp, and tiff files are allowed.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => [
                    'message' => "An error occurred: " . implode(' ', $validator->errors()->all()),
                ]
            ], 400);
        }
        else {
            try {
                $manager = new ImageManager(new Driver());
                $image = $request->file('upload');
                $uniqueId = hash_file('sha256', $image->getRealPath());
                $originalFileName = "original.{$image->getClientOriginalExtension()}";
                $baseDir = "assets/blog_img/{$uniqueId}";

                $image->storeAs($baseDir, $originalFileName, 'public');
                $urls['default'] = Storage::url("{$baseDir}/{$originalFileName}");

                if ($request->input('type') == 'banner') {
                    $sizes = [800, 1024, 1920];
                }
                else {
                    $sizes = [250, 350, 400];
                }

                foreach ($sizes as $size) {
                    $resizedImage = $manager->read($image->getRealPath())->scaleDown($size, null);

                    $resizedFilename = "{$size}";
                    $resizedPath = "{$baseDir}/{$resizedFilename}.webp";

                    Storage::disk('public')->put($resizedPath, (string) $resizedImage->encode(new WebpEncoder()));
                    $urls[$size] = Storage::url($resizedPath);
                }

                return response()->json(['urls' => $urls]);
            }
            catch (Exception $e) {
                return response()->json([
                    'error' => [
                        'message' => "An error occurred: " . $e->getMessage(),
                    ]
                ], 500);
            }
        }
    }
}
