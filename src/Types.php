<?php

declare(strict_types=1);

namespace RunApi\GeminiTts;

final class Types
{
    /**
     * Allowed model slugs for text to speech requests.
     *
     * @var list<string>
     */
    public const TEXT_TO_SPEECH_MODELS = ['gemini-2.5-pro-tts', 'gemini-3.1-flash-tts'];

    private function __construct()
    {
    }
}
