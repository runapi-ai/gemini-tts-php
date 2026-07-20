<?php

declare(strict_types=1);

namespace RunApi\GeminiTts\Tests\Unit;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use RunApi\Core\ClientOptions;
use RunApi\Core\Errors\ValidationException;
use RunApi\Core\Tests\Fixtures\QueueHttpClient;
use RunApi\GeminiTts\GeminiTtsClient;
use RunApi\GeminiTts\Models\CompletedAudioTaskResponse;
use RunApi\GeminiTts\Resources\TextToSpeech;

final class GeminiTtsClientTest extends TestCase
{
    public function testExposesTypedResources(): void
    {
        $client = new GeminiTtsClient(new ClientOptions(apiKey: 'k', httpClient: new QueueHttpClient([]), maxRetries: 0));

        self::assertInstanceOf(TextToSpeech::class, $client->textToSpeech);
    }

    public function testCreatePostsCompactedBodyToCorrectPath(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1"}'),
        ]);
        $client = new GeminiTtsClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $task = $client->textToSpeech->create([
            'model' => 'gemini-2.5-pro-tts',
            'dialogue_turns' => [['speaker_id' => 'Speaker 1', 'text' => 'Welcome.']],
            'sample_context' => 'sample',
            'scene' => 'sample',
            'speakers' => [['speaker_id' => 'Speaker 1', 'voice_name' => 'Fenrir', 'accent' => 'British (RP)', 'style' => 'Deadpan', 'pace' => 'Natural']],
            'temperature' => 0.5,
            'callback_url' => '',
            'seed' => null,
        ]);

        $body = json_decode((string) $transport->requests[0]->getBody(), true, flags: JSON_THROW_ON_ERROR);

        self::assertSame('task_1', $task->id);
        self::assertSame('/api/v1/gemini_tts/text_to_speech', $transport->requests[0]->getUri()->getPath());
        self::assertSame('gemini-2.5-pro-tts', $body['model']);
        self::assertArrayNotHasKey('callback_url', $body);
        self::assertArrayNotHasKey('seed', $body);
    }

    public function testRunReturnsTypedCompletedResponseAndPreservesUnknownFields(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1"}'),
            new Response(200, [], '{"id":"task_1","status":"completed","audios":[{"url":"https://file.runapi.ai/result"}],"extra_field":"kept"}'),
        ]);
        $client = new GeminiTtsClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $result = $client->textToSpeech->run([
            'model' => 'gemini-2.5-pro-tts',
            'dialogue_turns' => [['speaker_id' => 'Speaker 1', 'text' => 'Welcome.']],
            'sample_context' => 'sample',
            'scene' => 'sample',
            'speakers' => [['speaker_id' => 'Speaker 1', 'voice_name' => 'Fenrir', 'accent' => 'British (RP)', 'style' => 'Deadpan', 'pace' => 'Natural']],
            'temperature' => 0.5,
        ]);

        self::assertInstanceOf(CompletedAudioTaskResponse::class, $result);
        self::assertSame('https://file.runapi.ai/result', $result->audios[0]->url);
        self::assertSame('kept', $result->toArray()['extra_field']);
        self::assertSame('/api/v1/gemini_tts/text_to_speech/task_1', $transport->requests[1]->getUri()->getPath());
    }

    public function testCompletedResponseRequiresResultFiles(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1"}'),
            new Response(200, [], '{"id":"task_1","status":"completed"}'),
        ]);
        $client = new GeminiTtsClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('audios is required');

        $client->textToSpeech->run([
            'model' => 'gemini-2.5-pro-tts',
            'dialogue_turns' => [['speaker_id' => 'Speaker 1', 'text' => 'Welcome.']],
            'sample_context' => 'sample',
            'scene' => 'sample',
            'speakers' => [['speaker_id' => 'Speaker 1', 'voice_name' => 'Fenrir', 'accent' => 'British (RP)', 'style' => 'Deadpan', 'pace' => 'Natural']],
            'temperature' => 0.5,
        ]);
    }



    public function testSecondaryResourceUsesItsOwnPath(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_2"}'),
        ]);
        $client = new GeminiTtsClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $client->textToSpeech->create([
            'model' => 'gemini-2.5-pro-tts',
            'dialogue_turns' => [['speaker_id' => 'Speaker 1', 'text' => 'Welcome.']],
            'sample_context' => 'sample',
            'scene' => 'sample',
            'speakers' => [['speaker_id' => 'Speaker 1', 'voice_name' => 'Fenrir', 'accent' => 'British (RP)', 'style' => 'Deadpan', 'pace' => 'Natural']],
            'temperature' => 0.5,
        ]);

        self::assertSame('/api/v1/gemini_tts/text_to_speech', $transport->requests[0]->getUri()->getPath());
    }
}
