# Changelog

## [v0.2.0](https://github.com/runapi-ai/gemini-tts-php/releases/tag/v0.2.0) - 2026-09-30

### Changed
- Send request parameters to the service without local validation. Model ids and parameter values the service supports work without an SDK upgrade; static types and enum constants remain for completion.
  Migration: Invalid parameters now throw `ValidationException` built from the service's 400 response, including its status and message, instead of a `ValidationException` thrown locally before the request.


## [v0.1.1](https://github.com/runapi-ai/gemini-tts-php/releases/tag/v0.1.1) - 2026-08-04

### Fixed
- Allow speaker configurations to omit accent, style, and pace.


## [v0.1.0](https://github.com/runapi-ai/gemini-tts-php/releases/tag/v0.1.0) - 2026-07-20

### Added
- Add multi-speaker text-to-speech with typed nested request documentation and audio task responses.
