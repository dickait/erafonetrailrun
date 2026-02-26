<?php
$dir = new RecursiveDirectoryIterator('resources/views');
$iter = new RecursiveIteratorIterator($dir);

foreach ($iter as $file) {
    if (pathinfo($file, PATHINFO_EXTENSION) == 'php') {
        $content = file_get_contents($file);

        // 1. Protect buttons/badges that intentionally use white text with colors
        // A simple way is to match class="..." and find if it has bg-gradient or text-white together.
        // Let's use simpler regex:
        $content = preg_replace_callback('/class=(["\'])(.*?)\1/is', function ($matches) {
            $classes = $matches[2];

            // if it has a solid or gradient background that is "dark" or colored, we might want to keep text-white
            if (preg_match('/bg-(gradient|erafone|forest|red|black|blue|indigo)-/', $classes)) {
                $classes = str_replace('text-white', 'text-true-white', $classes);
            }

            // We want body to be bg-white, layout backgrounds to be white.
            $replacements = [
                'bg-dark-900/95' => 'bg-white/95',
                'bg-dark-900/80' => 'bg-white/80',
                'bg-dark-800/90' => 'bg-white/90',
                'bg-dark-700/50' => 'bg-gray-50',
                'hover:bg-dark-700' => 'hover:bg-gray-100',
                'hover:bg-dark-600' => 'hover:bg-gray-200',
                'bg-dark-900' => 'bg-white',
                'bg-dark-800' => 'bg-gray-50',
                'bg-dark-700' => 'bg-gray-100',
                'bg-dark-600' => 'bg-gray-200',

                'border-dark-900' => 'border-gray-100',
                'border-dark-800' => 'border-gray-200',
                'border-dark-700' => 'border-gray-200',
                'border-dark-600' => 'border-gray-300',

                'text-dark-900' => 'text-gray-50',
                'text-dark-800' => 'text-gray-100',
                'text-dark-700' => 'text-gray-200',

                'text-gray-400' => 'text-gray-500',
                'text-gray-300' => 'text-gray-600',
                'text-gray-200' => 'text-gray-700',
                'text-white' => 'text-gray-900',

                'forest' => 'erafone'
            ];

            $classes = str_replace(array_keys($replacements), array_values($replacements), $classes);

            return 'class="' . $classes . '"';
        }, $content);

        // Change forest to erafone everywhere else (outside class attributes too, just in case)
        $content = str_replace('forest', 'erafone', $content);

        // Restore true white
        $content = str_replace('text-true-white', 'text-white', $content);

        file_put_contents($file, $content);
    }
}
echo "Theme mapped successfully.\n";
