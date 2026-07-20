<?php

declare(strict_types=1);

namespace RunApi\GeminiTts;

use RunApi\Core\BaseClient;
use RunApi\Core\ClientOptions;
use RunApi\GeminiTts\Resources\TextToSpeech;

/**
 * Gemini TTS RunAPI PHP client.
 *
 * The client exposes typed model resources plus the universal `files` and
 * `account` resources.
 */
final class GeminiTtsClient extends BaseClient
{
    /** Text to speech operations for Gemini TTS. */
    public readonly TextToSpeech $textToSpeech;

    /** Create a Gemini TTS client with optional API key, base URL, and transport overrides. */
    public function __construct(ClientOptions $options = new ClientOptions())
    {
        parent::__construct($options);
        $this->textToSpeech = TextToSpeech::fromHttp($this->http);
    }
}
