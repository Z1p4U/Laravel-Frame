<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePhotoRequest;
use App\Http\Requests\UpdatePhotoRequest;
use App\Http\Resources\PhotoDetailResource;
use App\Http\Resources\PhotoResource;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $photos = Photo::latest("id")->searchQuery()->sortingQuery()->paginationQuery();

        if (empty($photos->toArray())) {
            return response()->json([
                "message" => "There is no photo"
            ]);
        }

        return PhotoResource::collection($photos);
        // return $this->success("Photo List", $photos);
    }

    /**
     * Store a newly created resource in storage.
     */

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    public function store(StorePhotoRequest $request)
    {
        if ($request->hasFile('photos')) {
            $photos = $request->file('photos');
            $savedPhotos = [];
            foreach ($photos as $photo) {
                $fileSize = $photo->getSize();
                $name = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                $savedPhoto = $photo->store("public/photo");
                $savedPhotos[] = [
                    "url" => $savedPhoto,
                    "name" => $name,
                    "extension" => $photo->extension(),
                    "user_id" => Auth::id(),
                    "size" => $this->formatBytes($fileSize),
                    "created_at" => now(),
                    "updated_at" => now()
                ];
            }
            Photo::insert($savedPhotos);
        }

        return response()->json([
            "message" => "Photo Uploaded Successfully",
            "id" => $request->id,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $photo = Photo::find($id);

        if (is_null($photo)) {
            return response()->json([
                "message" => "there is no photo"
            ]);
        }
        $this->authorize('view', $photo);

        return new PhotoDetailResource($photo);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePhotoRequest $request, Photo $photo)
    {
        // return $request;
    }

    public function deleteMultiplePhotos(Request $request)
    {
        $photoId = $request->photos;
        $photos = Photo::whereIn("id", $photoId)->get();

        if (empty($photos)) {
            return response()->json([
                "message" => "There is no Photo to delete"
            ]);
        }

        foreach ($photos as $photo) {
            if (Auth::id() != $photo->user_id) {
                return response()->json([
                    'message' => "You are not allowed"
                ]);
            }
        }
        Photo::whereIn('id', $photoId)->delete();
        // Storage::delete($photos->pluck('url')->toArray());
        return response()->json([
            "message" => "Photos deleted successfully"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $photo = Photo::find($id);

        if (is_null($photo)) {
            return response()->json([
                "message" => "There is no photo"
            ]);
        }

        $this->authorize('delete', $photo);

        $photo->delete();
        return response()->json([
            "message" => "Photo deleted successfully"
        ], 200);
    }

    public function trash()
    {
        $softDeletedPhotos = Photo::onlyTrashed()->get();

        return response()->json(["data" => PhotoResource::collection($softDeletedPhotos)], 200);
    }

    public function deletedPhoto($id)
    {
        $photo = Photo::onlyTrashed()->find($id);


        if (is_null($photo)) {
            return response()->json([
                "message" => "There is no photo"
            ]);
        }

        $showPhoto = new PhotoDetailResource($photo);

        return $this->success('Trash', $showPhoto);
    }

    public function restore(string $id)
    {
        $photo = Photo::withTrashed()->find($id);

        $photo->restore();

        return response()->json(['message' => 'Photo restored from trash'], 200);
    }

    public function forceDelete(string $id)
    {
        $photo = Photo::onlyTrashed()->find($id);

        if (is_null($photo)) {
            return response()->json([
                "message" => "There is no photo"
            ]);
        }

        $photo->forceDelete();
        Storage::delete($photo->url);

        return response()->json(['message' => 'Photo is deleted permanently'], 200);
    }

    public function clearTrash()
    {
        $photos = Photo::onlyTrashed()->get();

        foreach ($photos as $photo) {
            $photo->forceDelete();
            Storage::delete($photo->url);
        }

        return response()->json(['message' => 'Trash Cleared'], 200);
    }
}
