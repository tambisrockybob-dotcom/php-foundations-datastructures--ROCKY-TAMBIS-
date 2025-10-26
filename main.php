<?php
/**
 * main.php
 * Integration of all parts into a simple web interface.
 *
 * - Shows recursive category display (clickable book titles)
 * - Shows book details (from hash table) when title clicked via ?title=...
 * - Shows alphabetical list (BST) and allows searching via form.
 *
 * Place all files in the same folder or just use this single file — it contains
 * the required data and logic inline.
 */

// ---------- Sample data (same as other parts) ----------
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

// ---------- Recursion function (HTML-friendly) ----------
function displayLibraryLinks($library, $baseIndent = 0) {
    echo "<ul style='list-style-type: none; padding-left: " . ($baseIndent * 12) . "px;'>";
    foreach ($library as $key => $value) {
        if (is_array($value)) {
            echo "<li><strong>" . htmlspecialchars($key) . "</strong></li>";
            displayLibraryLinks($value, $baseIndent + 1);
        } else {
            // book title string
            $title = $value;
            // link back to this page with the book title encoded
            $url = htmlspecialchars($_SERVER['PHP_SELF'] . '?title=' . urlencode($title));
            echo "<li style='margin-left: 12px;'>• <a href='{$url}'>" . htmlspecialchars($title) . "</a></li>";
        }
    }
    echo "</ul>";
}

// ---------- Hash table lookup ----------
function getBookInfo($title, $bookInfo) {
    if (array_key_exists($title, $bookInfo)) {
        return $bookInfo[$title];
    }
    return null;
}

// ---------- BST Implementation ----------
class Node {
    public $data;
    public $left;
    public $right;
    public function __construct($data) {
        $this->data = $data;
        $this->left = null;
        $this->right = null;
    }
}
class BinarySearchTree {
    private $root = null;
    public function insert($data) {
        $this->root = $this->insertRec($this->root, $data);
    }
    private function insertRec($node, $data) {
        if ($node === null) return new Node($data);
        if (strcasecmp($data, $node->data) < 0) {
            $node->left = $this->insertRec($node->left, $data);
        } elseif (strcasecmp($data, $node->data) > 0) {
            $node->right = $this->insertRec($node->right, $data);
        }
        return $node;
    }
    public function search($data) {
        return $this->searchRec($this->root, $data);
    }
    private function searchRec($node, $data) {
        if ($node === null) return false;
        $cmp = strcasecmp($data, $node->data);
        if ($cmp === 0) return true;
        if ($cmp < 0) return $this->searchRec($node->left, $data);
        return $this->searchRec($node->right, $data);
    }
    public function inorderTraversal() {
        $result = [];
        $this->inorderRec($this->root, $result);
        return $result;
    }
    private function inorderRec($node, &$result) {
        if ($node !== null) {
            $this->inorderRec($node->left, $result);
            $result[] = $node->data;
            $this->inorderRec($node->right, $result);
        }
    }
}

// Build BST from all book titles present in $bookInfo
$bst = new BinarySearchTree();
foreach (array_keys($bookInfo) as $title) {
    $bst->insert($title);
}

// ---------- Handle incoming parameters ----------
$selectedTitle = isset($_GET['title']) ? trim($_GET['title']) : null;
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : null;
$searchResult = null;
if ($searchQuery !== null && $searchQuery !== '') {
    $searchResult = $bst->search($searchQuery);
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Digital Library Organizer — Integrated</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; max-width: 900px; margin: auto; }
        .col { display: inline-block; vertical-align: top; width: 48%; box-sizing: border-box; }
        .card { border: 1px solid #ddd; padding: 12px; border-radius: 6px; margin-bottom: 12px; }
        h2 { margin-top: 0; }
        a { color: #0b63d6; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .small { font-size: 0.9rem; color: #555; }
    </style>
</head>
<body>
    <h1>Digital Library Organizer</h1>
    <div class="col">
        <div class="card">
            <h2>Categories (Recursive Display)</h2>
            <?php displayLibraryLinks($library); ?>
            <p class="small">Click a book title to view its details.</p>
        </div>

        <div class="card">
            <h2>Book Details</h2>
            <?php
            if ($selectedTitle) {
                $info = getBookInfo($selectedTitle, $bookInfo);
                if ($info) {
                    echo "<h3>" . htmlspecialchars($selectedTitle) . "</h3>";
                    echo "<p><strong>Author:</strong> " . htmlspecialchars($info['author']) . "<br>";
                    echo "<strong>Year:</strong> " . htmlspecialchars($info['year']) . "<br>";
                    echo "<strong>Genre:</strong> " . htmlspecialchars($info['genre']) . "</p>";
                } else {
                    echo "<p>Book not found.</p>";
                }
            } else {
                echo "<p>Select a book from the left to see details here.</p>";
            }
            ?>
        </div>
    </div>

    <div class="col" style="margin-left:4%;">
        <div class="card">
            <h2>Alphabetical List (BST — Inorder Traversal)</h2>
            <ol>
                <?php foreach ($bst->inorderTraversal() as $t): ?>
                    <li><?php echo htmlspecialchars($t); ?></li>
                <?php endforeach; ?>
            </ol>
        </div>

        <div class="card">
            <h2>Search Book (BST)</h2>
            <form method="get" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <input type="text" name="search" placeholder="Enter book title" style="width:70%;" value="<?php echo htmlspecialchars($searchQuery ?? ''); ?>">
                <button type="submit">Search</button>
            </form>
            <?php
            if ($searchQuery !== null) {
                if ($searchResult) {
                    echo "<p>Searching for \"" . htmlspecialchars($searchQuery) . "\": <strong>Found!</strong></p>";
                } else {
                    echo "<p>Searching for \"" . htmlspecialchars($searchQuery) . "\": <strong>Not Found.</strong></p>";
                }
            } else {
                echo "<p>Enter a title above and click <em>Search</em>.</p>";
            }
            ?>
        </div>
    </div>

    <div style="clear: both;"></div>
    <hr>
    <p class="small">Files included: recursion.php | hashtable.php | bst.php | main.php</p>
</body>
</html>
