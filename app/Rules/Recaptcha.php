<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Recaptcha implements ValidationRule
{
    /**
     * Expected action name for reCAPTCHA v3.
     */
    protected ?string $action;

    public function __construct(?string $action = null)
    {
        $this->action = $action;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $secretKey = config('services.recaptcha.secret_key');

        // Allow bypassing in local/testing environment if keys have not been configured yet
        if (empty($secretKey)) {
            if (app()->environment('local', 'testing')) {
                Log::info('reCAPTCHA skipped: RECAPTCHA_SECRET_KEY is not configured in local environment.');
                return;
            }

            Log::warning('reCAPTCHA verification failed: RECAPTCHA_SECRET_KEY is missing.');
            $fail('CAPTCHA configuration is missing. Please contact support.');
            return;
        }

        if (empty($value)) {
            $fail('Please complete the CAPTCHA verification.');
            return;
        }

        try {
            $response = Http::asForm()->timeout(5)->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret'   => $secretKey,
                'response' => $value,
                'remoteip' => request()->ip(),
            ]);

            if (! $response->successful()) {
                Log::error('reCAPTCHA service request failed', ['status' => $response->status()]);
                $fail('Unable to verify CAPTCHA. Please try again.');
                return;
            }

            $result = $response->json();

            if (! ($result['success'] ?? false)) {
                Log::warning('reCAPTCHA verification returned failure', ['result' => $result]);
                $fail('CAPTCHA verification failed. Please try again.');
                return;
            }

            // Verify action if specified (v3)
            if ($this->action && isset($result['action']) && $result['action'] !== $this->action) {
                Log::warning('reCAPTCHA action mismatch', [
                    'expected' => $this->action,
                    'received' => $result['action'],
                ]);
                $fail('CAPTCHA action mismatch. Please try again.');
                return;
            }

            // Check score threshold if returned by Google (v3)
            if (isset($result['score'])) {
                $score = (float) $result['score'];
                $threshold = (float) config('services.recaptcha.score_threshold', 0.5);

                if ($score < $threshold) {
                    Log::warning('reCAPTCHA low score detected', [
                        'score'     => $score,
                        'threshold' => $threshold,
                        'ip'        => request()->ip(),
                    ]);
                    $fail('Suspicious activity detected. Please try again.');
                    return;
                }
            }
        } catch (\Throwable $e) {
            Log::error('reCAPTCHA exception during verification', ['error' => $e->getMessage()]);
            $fail('Error verifying CAPTCHA. Please try again.');
        }
    }
}
