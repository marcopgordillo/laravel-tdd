<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Video;

class VideoController extends Controller
{
    public function store(Request $request): Response
    {
        $postData = $this->validate($request, [
            'url' => ['required', 'url'],
            'title' => ['required'],
            'description' => ['sometimes'],
        ]);

        $desc = $request->has('description')
            ? $request->input('description')
            :'';

        $video = Video::create([
            'url' => $postData['url'],
            'title' => $postData['title'],
            'description' => $desc,
            'user_id' => 1,
            'type' => 'youtube',
            'is_published' => false,
        ]);

        return response($video, Response::HTTP_CREATED);
    }
}
