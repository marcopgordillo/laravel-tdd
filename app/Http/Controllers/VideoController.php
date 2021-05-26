<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Video;
use App\Http\Requests\VideoPostRequest;
use App\Services\VideoService;
use Illuminate\Support\Facades\Auth;

class VideoController extends Controller
{
    private VideoService $videoService;

    public function __construct(VideoService $videoService)
    {
        $this->videoService = $videoService;
    }

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
        $postData = array_replace([], $request->input());

        $video = $this->videoService->addVideoSubmission($postData, Auth::user());

        return response($video, Response::HTTP_CREATED);
    }
}
