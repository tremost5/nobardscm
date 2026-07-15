<?php

namespace App\Services;

use App\Models\Setting;

class SettingsService
{
    public function get(string $key, mixed $default = null): mixed
    {
        $value = Setting::query()->where('key', $key)->value('value');

        if ($value === null) {
            return $default;
        }

        $decoded = json_decode($value, true);

        return json_last_error() === JSON_ERROR_NONE ? $decoded : $value;
    }

    public function string(string $key, string $default = ''): string
    {
        return (string) $this->get($key, $default);
    }

    public function boolean(string $key, bool $default = false): bool
    {
        return filter_var($this->get($key, $default), FILTER_VALIDATE_BOOL);
    }

    public function put(string $key, mixed $value): void
    {
        Setting::query()->updateOrCreate(
            ['key' => $key],
            ['value' => is_bool($value) ? (int) $value : (is_array($value) ? json_encode($value) : $value)],
        );
    }

    public function putMany(array $values): void
    {
        foreach ($values as $key => $value) {
            $this->put($key, $value);
        }
    }
}
