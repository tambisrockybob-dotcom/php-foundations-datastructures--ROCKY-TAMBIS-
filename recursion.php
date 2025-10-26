<?php
/**
 * recursion.php
 * Part I — Recursive Directory Display
 *
 * Usage (CLI or browser): include or run directly.
 */

// Sample library structure
$library = [
    "Fiction" => [
        "Fantasy" => ["Harry Potter", "The Hobbit"],
        "Mystery" => ["Sherlock Holmes", "Gone Girl"]
    ],
    "Non-Fiction" => [
        "Science" => ["A Brief History of Time", "The Selfish Gene"],
        "Biography" => ["Steve Jobs", "Becoming"]
    ]
];

/**
 * displayLibrary
 * Recursively prints categories and books.
 *
 * @param array $library Nested associative array representing folders/categories
 * @param int $indent Number of spaces to indent (for CLI) — for HTML we use &nbsp;
 * @param bool $asHtml If true, output HTML <ul>/<li>, otherwise plain text
 */
function displayLibrary($library, $indent = 0, $asHtml = false) {
    if ($asHtml) {
        echo "<ul style='list-style-type: none; margin-left: " . ($indent * 12) . "px; padding-left:0;'>";
    }

    foreach ($library as $key => $value) {
        if (is_array($value)) {
            // $key is a category
            if ($asHtml) {
                echo "<li><strong>" . htmlspecialchars($key) . "</strong></li>";
            } else {
                echo str_repeat(" ", $indent) . $key . PHP_EOL;
            }
            // Recurse deeper
            displayLibrary($value, $indent + 4, $asHtml);
        } else {
            // It's a book title (string)
            if ($asHtml) {
                echo "<li style='margin-left: " . ($indent * 3) . "px;'>- " . htmlspecialchars($value) . "</li>";
            } else {
                echo str_repeat(" ", $indent) . $value . PHP_EOL;
            }
        }
    }

    if ($asHtml) {
        echo "</ul>";
    }
}

// If run directly in browser show HTML output:
if (php_sapi_name() !== 'cli') {
    echo "<!doctype html><html><head><meta charset='utf-8'><title>Recursive Library Display</title></head><body>";
    echo "<h2>Library (Recursive Display)</h2>";
    displayLibrary($library, 0, true);
    echo "</body></html>";
} else {
    // CLI output
    displayLibrary($library, 0, false);
}
