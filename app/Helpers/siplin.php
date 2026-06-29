<?php

/**
 * SIPLIN Helper Functions
 * 
 * Fungsi-fungsi helper untuk kemudahan akses konfigurasi
 */

if (!function_exists('siplin_config')) {
    /**
     * Get SIPLIN configuration
     */
    function siplin_config(string $key = null, $default = null): mixed
    {
        if ($key === null) {
            return config('siplin');
        }
        return config("siplin.{$key}", $default);
    }
}

if (!function_exists('feature_enabled')) {
    /**
     * Check if a feature is enabled
     */
    function feature_enabled(string $feature): bool
    {
        return config("siplin.features.{$feature}", true);
    }
}

if (!function_exists('org_name')) {
    /**
     * Get organization name
     */
    function org_name(bool $short = false): string
    {
        return $short 
            ? config('siplin.organization.short_name', 'BPBJ')
            : config('siplin.organization.name', 'Biro Pengadaan Barang dan Jasa');
    }
}

if (!function_exists('app_version')) {
    /**
     * Get application version
     */
    function app_version(): string
    {
        return config('siplin.version', '1.0.0');
    }
}

if (!function_exists('condition_label')) {
    /**
     * Get condition label
     */
    function condition_label(string $condition): string
    {
        return config("siplin.conditions.{$condition}", ucfirst(str_replace('_', ' ', $condition)));
    }
}

if (!function_exists('acquisition_label')) {
    /**
     * Get acquisition type label
     */
    function acquisition_label(string $type): string
    {
        return config("siplin.acquisition_types.{$type}", ucfirst($type));
    }
}
