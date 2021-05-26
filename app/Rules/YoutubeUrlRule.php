<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Services\VideoService;

class YoutubeUrlRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $videoService = app()->make(VideoService::class);

        return $videoService->validateYoutubeUrl($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
