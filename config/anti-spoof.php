<?php

return [
    /**
     * List of trusted proxies.
     * Requests coming from these IPs will not be checked for spoofing.
     * This is useful for load balancers or reverse proxies that handle the real IP.
     * You can specify IPs or CIDR ranges.
     */
    'trusted_proxies' => [
        // Example: '192.168.0.1', '10.0.0.1'
    ],

    /**
     * Determine if spoofing attempts should block access.
     * If true, a 403 response will be returned when spoofing is detected.
     * If false, spoofing attempts will be logged but not blocked.
     */
    'block' => true,

    /**
     * Message to return when spoofing is detected and blocking is enabled.
     * This message will be shown in the 403 response.
     * You can customize it to provide more context or instructions to the user.
     */
    'message' => 'Access denied.',

    'user_agent' => [
        /**
         * Enable or disable user agent spoofing detection.
         * If false, user agent checks will be skipped.
         */
        'enabled' => true,

        /**
         * Allowed user agent patterns.
         * If empty, all user agents are allowed except those in the 'blocked' list.
         */
        'allowed' => [
            // 'Mozilla/', 'Chrome/', 'Safari/', etc.
        ],

        /**
         * Block these patterns even if allowed list is empty.
         * This pattern takes priority over the allowed list.
         * If a user agent matches any of these patterns, it will be considered suspicious even if it is in the allowed list.
         * You can add common bot or script user agents here to prevent them from accessing your application.
         * Examples include 'curl', 'bot', 'python', 'scrapy', 'node-fetch', etc.
         */
        'blocked' => [
            'curl',
            'bot',
            'python',
            'scrapy',
            'node-fetch',
        ],
    ],
];
