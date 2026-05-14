<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Concept;
use Illuminate\Contracts\View\View;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

final class MediaLibraryController extends Controller
{
    public function index(): View
    {
        $media = Media::query()
            ->where('model_type', Concept::class)
            ->with(['model' => fn ($m) => $m->select('id', 'uuid', 'status')])
            ->latest('id')
            ->paginate(48);

        return view('admin.media.index', [
            'media' => $media,
        ]);
    }
}
