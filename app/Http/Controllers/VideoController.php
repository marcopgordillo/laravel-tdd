<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Video;
use App\Http\Requests\VideoPostRequest;

class VideoController extends Controller
{
    public function store(VideoPostRequest $request): Response
    {
        $desc = $request->has('description')
            ? $request->input('description')
            :'';

        $video = Video::create([
            'url' => $request->input('url'),
            'title' => $request->input('title'),
            'description' => $desc,
            'user_id' => 1,
            'type' => 'youtube',
            'is_published' => false,
        ]);

        return response($video, Response::HTTP_CREATED);
    }
}
