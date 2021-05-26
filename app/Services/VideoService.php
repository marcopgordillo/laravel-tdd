<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use App\Models\Video;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class VideoService
{
    public function addVideoSubmission(array $postData, User $user): Video
    {
        $desc = '';

        if (isset($postData['description'])) {
            $desc = $postData['description'];
        }

        $video = Video::create([
            'url' => $postData['url'],
            'title' => $postData['title'],
            'description' => $desc,
            'user_id' => $user->id,
            'type' => 'youtube',
            'is_published' => 0,
        ]);

        return $video;
    }

    public function validateYoutubeUrl(string $url): bool
    {
        $youtubeRegexp = "/^(http(s)?:\/\/)?((w){3}.)?youtu(be|.be)?(\.com)?\/.+/";

        return preg_match($youtubeRegexp, $url) === 1;
    }
}
