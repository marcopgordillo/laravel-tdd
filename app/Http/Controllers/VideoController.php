<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Video;
use App\Http\Requests\VideoPostRequest;

class VideoController extends Controller
{
    public function index(): Response
    {
        $videos = Video::query()
            ->published()
            ->orderByDesc('created_at')
            ->paginate(config('app.pagination'));

        return response($videos, Response::HTTP_OK);
    }

    public function store(VideoPostRequest $request): Response
    {
        $desc = $request->has('description')
            ? $request->input('description')
            :'';

        $video = Video::create([
            'url' => $request->input('url'),
            'title' => $request->input('title'),
            'description' => $desc,
            'user_id' => auth()->user()->id,
            'type' => 'youtube',
            'is_published' => false,
        ]);

        return response($video, Response::HTTP_CREATED);
    }
}
