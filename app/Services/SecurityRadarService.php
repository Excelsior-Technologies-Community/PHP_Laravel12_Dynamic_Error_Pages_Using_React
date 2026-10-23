<?php

namespace App\Services;

class SecurityRadarService
{
    /**
     * Analyze a requested URL, user-agent, and IP to detect malicious bot activity.
     */
    public static function analyze(string $url, ?string $userAgent, ?string $ip): array
    {
        $path = strtolower(parse_url($url, PHP_URL_PATH) ?? $url);
        $query = strtolower(parse_url($url, PHP_URL_QUERY) ?? '');
        $ua = strtolower($userAgent ?? '');

        $threatLevel = 'safe';
        $threatCategory = 'Normal Mistake';

        // 1. Critical Environment / Credential File Probes
        if (
            str_contains($path, '.env') ||
            str_contains($path, 'config.json') ||
            str_contains($path, 'web.config') ||
            str_contains($path, 'credentials') ||
            str_contains($path, 'id_rsa') ||
            str_contains($path, '.aws')
        ) {
            $threatLevel = 'critical';
            $threatCategory = 'Env & Credentials Leak Probe';
        }
        // 2. Admin & Web Backdoor Exploits
        elseif (
            str_contains($path, 'wp-admin') ||
            str_contains($path, 'wp-login') ||
            str_contains($path, 'phpmyadmin') ||
            str_contains($path, 'adminer') ||
            str_contains($path, 'eval-stdin') ||
            str_contains($path, 'shell.php') ||
            str_contains($path, 'alfa.php') ||
            str_contains($path, 'actuator/health') ||
            str_contains($path, 'xmlrpc.php')
        ) {
            $threatLevel = 'critical';
            $threatCategory = 'Admin / Backdoor Probe';
        }
        // 3. VCS Source Code Leak Probes (.git, .svn)
        elseif (
            str_contains($path, '.git') ||
            str_contains($path, '.svn') ||
            str_contains($path, '.hg') ||
            str_contains($path, '.gitlab-ci')
        ) {
            $threatLevel = 'suspicious';
            $threatCategory = 'Git / VCS Repository Exposure Probe';
        }
        // 4. SQL Injection, Directory Traversal, or XSS signatures
        elseif (
            str_contains($query, 'union%20select') ||
            str_contains($query, 'union+select') ||
            str_contains($path, '..%2f') ||
            str_contains($path, '../') ||
            str_contains($query, '<script') ||
            str_contains($query, '1=1')
        ) {
            $threatLevel = 'critical';
            $threatCategory = 'Injection / Path Traversal Attack';
        }
        // 5. Automated Attack Tool User Agents
        elseif (
            str_contains($ua, 'sqlmap') ||
            str_contains($ua, 'nikto') ||
            str_contains($ua, 'nmap') ||
            str_contains($ua, 'masscan') ||
            str_contains($ua, 'dirbuster') ||
            str_contains($ua, 'zgrab')
        ) {
            $threatLevel = 'suspicious';
            $threatCategory = 'Automated Vulnerability Scanner Bot';
        }

        // Parse Client Device, Browser, and Operating System
        $clientInfo = self::parseClientInfo($userAgent);

        // Geo Lookup
        $geo = self::resolveGeoLocation($ip);

        return [
            'threat_level' => $threatLevel,
            'threat_category' => $threatCategory,
            'device_type' => $clientInfo['device_type'],
            'browser' => $clientInfo['browser'],
            'os' => $clientInfo['os'],
            'country' => $geo['country'],
            'country_code' => $geo['country_code'],
        ];
    }

    /**
     * Parse User Agent into Device, Browser, and OS.
     */
    public static function parseClientInfo(?string $userAgent): array
    {
        $ua = $userAgent ?? '';
        $deviceType = 'Desktop';
        $browser = 'Chrome';
        $os = 'Windows';

        // Device
        if (preg_match('/(tablet|ipad|playbook|silk)|(android(?!.*mobi))/i', $ua)) {
            $deviceType = 'Tablet';
        } elseif (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile|mobile)/i', $ua)) {
            $deviceType = 'Mobile';
        } elseif (preg_match('/(bot|crawl|spider|slurp|curl|wget|python|ruby)/i', $ua)) {
            $deviceType = 'Bot';
        }

        // OS
        if (preg_match('/windows|win32/i', $ua)) {
            $os = 'Windows';
        } elseif (preg_match('/macintosh|mac os x/i', $ua)) {
            $os = 'macOS';
        } elseif (preg_match('/linux/i', $ua)) {
            $os = 'Linux';
        } elseif (preg_match('/iphone|ipad|ipod/i', $ua)) {
            $os = 'iOS';
        } elseif (preg_match('/android/i', $ua)) {
            $os = 'Android';
        }

        // Browser
        if (preg_match('/edg/i', $ua)) {
            $browser = 'Edge';
        } elseif (preg_match('/firefox|fxios/i', $ua)) {
            $browser = 'Firefox';
        } elseif (preg_match('/chrome|crios/i', $ua)) {
            $browser = 'Chrome';
        } elseif (preg_match('/safari/i', $ua)) {
            $browser = 'Safari';
        } elseif (preg_match('/opera|opr\//i', $ua)) {
            $browser = 'Opera';
        } elseif (preg_match('/bot|crawl|spider|curl/i', $ua)) {
            $browser = 'Scanner Bot';
        }

        return [
            'device_type' => $deviceType,
            'browser' => $browser,
            'os' => $os,
        ];
    }

    /**
     * Resolve Geo Location (Country & Country Code).
     */
    public static function resolveGeoLocation(?string $ip): array
    {
        if (empty($ip) || $ip === '127.0.0.1' || $ip === '::1' || str_starts_with($ip, '192.168.') || str_starts_with($ip, '10.')) {
            return [
                'country' => 'Localhost / Intranet',
                'country_code' => 'US',
            ];
        }

        // Simple hashed realistic distribution for sample/external IP addresses
        $countries = [
            ['country' => 'United States', 'country_code' => 'US'],
            ['country' => 'Germany', 'country_code' => 'DE'],
            ['country' => 'India', 'country_code' => 'IN'],
            ['country' => 'United Kingdom', 'country_code' => 'GB'],
            ['country' => 'France', 'country_code' => 'FR'],
            ['country' => 'Canada', 'country_code' => 'CA'],
            ['country' => 'Netherlands', 'country_code' => 'NL'],
        ];

        $index = abs(crc32($ip)) % count($countries);
        return $countries[$index];
    }
}
