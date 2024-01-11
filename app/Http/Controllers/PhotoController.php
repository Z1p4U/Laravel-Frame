<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePhotoRequest;
use App\Http\Requests\UpdatePhotoRequest;
use App\Http\Resources\PhotoDetailResource;
use App\Http\Resources\PhotoResource;
use App\Models\Photo;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public function index()
    {
        $photos = Photo::latest("id")
            ->searchQuery()
            ->sortingQuery()
            ->paginationQuery();

        if (empty($photos->toArray())) {
            return $this->notFound('There is no photo');
        }

        $photoResource = PhotoResource::collection($photos);

        return $this->success("Photo List", $photos);
    }

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
        DB::beginTransaction();
        try {
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

                DB::commit();

                return $this->success('Photo Uploaded Successfully', $savedPhotos);
            }
        } catch (Exception $e) {
            DB::rollback();

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        DB::beginTransaction();
        try {
            $photo = Photo::findOrFail($id);
            $photoDetailResource = new PhotoDetailResource($photo);

            DB::commit();

            return $this->success('Photo Detail', $photo);
        } catch (ModelNotFoundException $e) {
            DB::rollback();
            $errorMessage = 'Photo not found';

            return response()->json(['error' => $errorMessage], 404);
        } catch (Exception $e) {
            DB::rollback();

            return response()->json(['error' => $e->getMessage()], 500);
        }

        return new PhotoDetailResource($photo);
    }

    public function deleteMultiplePhotos(Request $request)
    {
        $photoId = $request->photos;
        $photos = Photo::whereIn("id", $photoId)->get();
        DB::beginTransaction();
        try {
            if (empty($photos)) {
                return response()->json([
                    "message" => "There is no Photo to delete"
                ]);
            }

            Photo::whereIn('id', $photoId)->delete();

            return $this->success('Photos deleted successfully', $photos);
        } catch (ModelNotFoundException $e) {
            DB::rollback();
            $errorMessage = 'Photo not found';

            return response()->json(['error' => $errorMessage], 404);
        } catch (Exception $e) {
            DB::rollback();

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $photo = Photo::findOrFail($id);

            $photo->delete($id);

            DB::commit();

            return $this->success('Photo deleted successfully', $photo);
        } catch (ModelNotFoundException $e) {
            DB::rollback();
            $errorMessage = 'Photo not found';

            return response()->json(['error' => $errorMessage], 404);
        } catch (Exception $e) {
            DB::rollback();

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function trash()
    {
        $softDeletedPhotos = Photo::onlyTrashed()
            ->searchQuery()
            ->sortingQuery()
            ->paginationQuery();

        if (empty($softDeletedPhotos->toArray())) {
            return $this->notFound("Trash is empty");
        }

        $photoResource = PhotoResource::collection($softDeletedPhotos);

        return $this->success('Trash Bin', $softDeletedPhotos);
    }

    public function deletedPhoto($id)
    {
        try {
            $photo = Photo::onlyTrashed()
                ->findOrFail($id);

            $showPhoto = new PhotoDetailResource($photo);

            return $this->success('Deleted Photo', $showPhoto);
        } catch (ModelNotFoundException $e) {
            DB::rollback();
            $errorMessage = 'Photo not found';

            return response()->json(['error' => $errorMessage], 404);
        }
    }

    public function restore(string $id)
    {
        DB::beginTransaction();
        try {
            $photo = Photo::withTrashed()->findOrFail($id);

            $photo->restore($id);

            DB::commit();

            return $this->success('Photo restored successfully', $photo);
        } catch (ModelNotFoundException $e) {
            DB::rollback();
            $errorMessage = 'Photo not found';

            return response()->json(['error' => $errorMessage], 404);
        } catch (Exception $e) {
            DB::rollback();

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function forceDelete(string $id)
    {
        DB::beginTransaction();
        try {
            $photo = Photo::onlyTrashed()
                ->findOrFail($id);

            $photo->forceDelete($id);

            Storage::delete($photo->url);

            DB::commit();

            return $this->success('Photo removed permanently', $photo);
        } catch (ModelNotFoundException $e) {
            DB::rollback();
            $errorMessage = 'Photo not found';

            return response()->json(['error' => $errorMessage], 404);
        } catch (Exception $e) {
            DB::rollback();

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function clearTrash()
    {
        DB::beginTransaction();
        try {

            $photos = Photo::onlyTrashed()->get();

            foreach ($photos as $photo) {
                $photo->forceDelete();
                Storage::delete($photo->url);
            }

            DB::commit();

            return $this->success('Trash Cleared', $photo);
        } catch (Exception $e) {
            DB::rollback();

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
