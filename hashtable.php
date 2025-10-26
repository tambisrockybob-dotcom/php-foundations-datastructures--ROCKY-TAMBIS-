<?php
/**
 * hashtable.php
 * Part II — Hash Table for Book Details
 *
 * Demonstrates storing book metadata in associative arrays and a lookup function.
 */

// Book information hash table (associative array)
$bookInfo = [
    "Harry Potter" => ["author" => "J.K. Rowling", "year" => 1997, "genre" => "Fantasy"],
    "The Hobbit" => ["author" => "J.R.R. Tolkien", "year" => 1937, "genre" => "Fantasy"],
    "Sherlock Holmes" => ["author" => "Arthur Conan Doyle", "year" => 1892, "genre" => "Mystery"],
    "Gone Girl" => ["author" => "Gillian Flynn", "year" => 2012, "genre" => "Mystery"],
    "A Brief History of Time" => ["author" => "Stephen Hawking", "year" => 1988, "genre" => "Science"],
    "The Selfish Gene" => ["author" => "Richard Dawkins", "year" => 1976, "genre" => "Science"],
    "Steve Jobs" => ["author" => "Walter Isaacson", "year" => 2011, "genre" => "Biography"],
    "Becoming" => ["author" => "Michelle Obama", "year" => 2018, "genre" => "Biography"]
];

/**
 * getBookInfo
 * Return details for a book title, or null if not found.
 *
 * @param string $title
 * @param array $bookInfo
 * @return array|null
 */
function getBookInfo($title, $bookInfo) {
    if (array_key_exists($title, $bookInfo)) {
        return $bookInfo[$title];
    }
    return null;
}

/* Example usage when run directly */
if (php_sapi_name() === 'cli') {
    $title = "Harry Potter";
    $info = getBookInfo($title, $bookInfo);
    if ($info) {
        echo "Title: $title" . PHP_EOL;
        echo "Author: " . $info['author'] . PHP_EOL;
        echo "Year: " . $info['year'] . PHP_EOL;
        echo "Genre: " . $info['genre'] . PHP_EOL;
    } else {
        echo "Book not found" . PHP_EOL;
    }
}
