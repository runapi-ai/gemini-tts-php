<?php

declare(strict_types=1);

namespace RunApi\GeminiTts\Resources;

use RunApi\Core\Http\HttpClient;
use RunApi\Core\Models\TaskCreateResponse;
use RunApi\Core\RequestOptions;
use RunApi\Core\Resources\TypedConfiguredResource;
use RunApi\GeminiTts\Models\AudioTaskResponse;
use RunApi\GeminiTts\Models\CompletedAudioTaskResponse;
use RunApi\GeminiTts\Types;

/** Text to speech operations for Gemini TTS. */
readonly class TextToSpeech extends TypedConfiguredResource
{
    /**
     * Create a text to speech task and return immediately with a task id.
     *
     * @param array{
     *   dialogue_turns: list<array{speaker_id: string, text: string}>,
     *   model: string,
     *   speakers: list<array{speaker_id: string, voice_name: string, audio_profile?: string, accent?: string, style?: string, pace?: string}>,
     *   callback_url?: string,
     *   sample_context?: string,
     *   scene?: string,
     *   temperature?: float|int
     * } $params
     */
    public function create(array $params, ?RequestOptions $options = null): TaskCreateResponse
    {
        return parent::create($params, $options);
    }

    /** Fetch the current status of a text to speech task. */
    public function get(string $id, ?RequestOptions $options = null): AudioTaskResponse
    {
        $response = parent::get($id, $options);

        /** @var AudioTaskResponse $response */
        return $response;
    }

    /**
     * Create a text to speech task and poll until it completes.
     *
     * @param array{
     *   dialogue_turns: list<array{speaker_id: string, text: string}>,
     *   model: string,
     *   speakers: list<array{speaker_id: string, voice_name: string, audio_profile?: string, accent?: string, style?: string, pace?: string}>,
     *   callback_url?: string,
     *   sample_context?: string,
     *   scene?: string,
     *   temperature?: float|int
     * } $params
     */
    public function run(array $params, ?RequestOptions $options = null): CompletedAudioTaskResponse
    {
        $response = parent::run($params, $options);

        /** @var CompletedAudioTaskResponse $response */
        return $response;
    }

    /** Create the resource using the shared RunAPI HTTP transport. */
    public static function fromHttp(HttpClient $http): self
    {
        return new self(
            $http,
            '/api/v1/gemini_tts/text_to_speech',
            'gemini-tts/text-to-speech',
            AudioTaskResponse::class,
            CompletedAudioTaskResponse::class,
            Types::TEXT_TO_SPEECH_MODELS,
            'text-to-speech',
            AudioTaskResponse::class,
            CompletedAudioTaskResponse::class,
        );
    }
}
