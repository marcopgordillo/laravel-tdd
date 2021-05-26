<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\VideoService;

class YoutubeUrlTest extends TestCase
{
    private VideoService $videoService;

    public function setUp(): void
    {
        parent::setUp();
        $this->videoService = app()->make(VideoService::class);
    }

    public function test_validates_correct_youtube_urls()
    {

        $urls = [
            'https://www.youtube.com/watch?v=1sTux4ys3iE&ab_channel=AmitavRoy',
            'https://youtu.be/1sTux4ys3iE?t=20',
            'https://youtube.com/watch?v=1sTux4ys3iE',
            'https://youtu.be/1sTux4ys3iE',
        ];

        foreach($urls as $url) {
            $this->assertTrue($this->videoService->validateYoutubeUrl($url));
        }
    }

    public function test_validates_wrong_youtube_urls()
    {
        $urls = [
            'you.be/1sTux4ys3iE',
            'htpps://vimeo.com/v=1236',
        ];

        foreach($urls as $url) {
            $this->assertFalse($this->videoService->validateYoutubeUrl($url));
        }
    }
}
