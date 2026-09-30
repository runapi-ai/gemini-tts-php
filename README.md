# Gemini TTS PHP SDK for RunAPI

[![Packagist](https://img.shields.io/packagist/v/runapi-ai/gemini-tts)](https://packagist.org/packages/runapi-ai/gemini-tts)
[![License](https://img.shields.io/github/license/runapi-ai/gemini-tts-php)](https://github.com/runapi-ai/gemini-tts-php/blob/main/LICENSE)

The Gemini TTS PHP SDK is the language-specific package for Gemini TTS
on RunAPI. Use this package when your application needs Composer installs,
associative-array request bodies, task status lookup, and consistent RunAPI
errors in PHP.

This README is the PHP package guide for the public `gemini-tts-php` split
repository. For model details, use https://runapi.ai/models/gemini-tts; for API
reference, use https://runapi.ai/docs/api/gemini-tts/text-to-speech; for SDK docs, use
https://runapi.ai/docs/resources/sdks.

## Install

```bash
composer require runapi-ai/gemini-tts
```

## Quick start

```php
<?php

require __DIR__ . "/vendor/autoload.php";

use RunApi\GeminiTts\GeminiTtsClient;

$client = new GeminiTtsClient(); // reads RUNAPI_API_KEY



$task = $client->textToSpeech->create([
    'model' => 'gemini-2.5-pro-tts',
    'dialogue_turns' => [['speaker_id' => 'Speaker 1', 'text' => 'Welcome.']],
    'sample_context' => 'sample',
    'scene' => 'sample',
    'speakers' => [['speaker_id' => 'Speaker 1', 'voice_name' => 'Fenrir', 'accent' => 'British (RP)', 'style' => 'Deadpan', 'pace' => 'Natural']],
    'temperature' => 0.5,
]);

$status = $client->textToSpeech->get($task->id);

$result = $client->textToSpeech->run([
    'model' => 'gemini-2.5-pro-tts',
    'dialogue_turns' => [['speaker_id' => 'Speaker 1', 'text' => 'Welcome.']],
    'sample_context' => 'sample',
    'scene' => 'sample',
    'speakers' => [['speaker_id' => 'Speaker 1', 'voice_name' => 'Fenrir', 'accent' => 'British (RP)', 'style' => 'Deadpan', 'pace' => 'Natural']],
    'temperature' => 0.5,
]);

echo $result->audios[0]->url . PHP_EOL;
```

Use `create()` to submit a task and return quickly, `get()` to fetch the latest
task state, and `run()` when a script should create and poll until completion.
In web request handlers, prefer `create()` plus webhook or later `get()`
polling so a worker is not held open.


RunAPI-generated file URLs are temporary. Download and store generated files
in your own durable storage within the retention window; do not treat returned
URLs as long-term assets.

## Language notes

Pass request parameters as associative arrays with snake_case keys. The
available resources are `textToSpeech`. Keep `RUNAPI_API_KEY` in the environment
or your secret manager; never commit API keys or callback secrets.

## Links

- Model page: https://runapi.ai/models/gemini-tts
- SDK docs: https://runapi.ai/docs/resources/sdks
- Product docs: https://runapi.ai/docs/api/gemini-tts/text-to-speech
- Pricing and rate limits: https://runapi.ai/models/gemini-tts/gemini-2.5-pro-tts
- Full catalog: https://runapi.ai/models
- GitHub repository: https://github.com/runapi-ai/gemini-tts-php
- Multi-language SDK repository: https://github.com/runapi-ai/gemini-tts-sdk

## License

Licensed under the Apache License, Version 2.0.
