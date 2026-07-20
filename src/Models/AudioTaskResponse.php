<?php

declare(strict_types=1);

namespace RunApi\GeminiTts\Models;

use RunApi\Core\Models\TaskResponse;
use RunApi\Core\Support\Payload;

/** Async audio task response with lifecycle status and output audios. */
readonly class AudioTaskResponse extends TaskResponse
{
    /**
     * @param list<Audio> $audios Generated audio files when the task has completed.

     * @param array<string, mixed> $raw Raw response payload preserved by `toArray()`.
     */
    public function __construct(?string $id, string $status, ?string $error = null, public array $audios = [], array $raw = [])
    {
        parent::__construct(id: $id, status: $status, error: $error, raw: $raw === [] ? ['id' => $id, 'status' => $status, 'error' => $error, 'audios' => array_map(static fn (Audio $audio): array => $audio->toArray(), $audios)] : $raw);
    }

    /**
     * Hydrate a task status response from a RunAPI response object.
     *
     * @param array<string, mixed> $raw
     */
    public static function fromArray(array $raw): self
    {
        return new self(id: Payload::string($raw, 'id'), status: Payload::string($raw, 'status'), error: self::error($raw), audios: self::audios($raw), raw: $raw);
    }

    /**
     * @param array<string, mixed> $raw
     *
     * @return list<Audio>
     */
    protected static function audios(array $raw, bool $required = false): array
    {
        return Payload::listOf($raw, 'audios', Audio::fromArray(...), $required);
    }


}
