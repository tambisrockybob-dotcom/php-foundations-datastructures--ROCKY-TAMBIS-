<?php
/**
 * bst.php
 * Part III — Binary Search Tree (BST) for Book Titles
 *
 * Insert titles into a BST, search them, and do inorder traversal to list alphabetically.
 */

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

    // Insert value into BST
    public function insert($data) {
        $this->root = $this->insertRec($this->root, $data);
    }

    private function insertRec($node, $data) {
        if ($node === null) {
            return new Node($data);
        }
        // Case-insensitive ordering; use strcmp for consistent alphabetical order
        if (strcasecmp($data, $node->data) < 0) {
            $node->left = $this->insertRec($node->left, $data);
        } elseif (strcasecmp($data, $node->data) > 0) {
            $node->right = $this->insertRec($node->right, $data);
        } // if equal, do nothing (no duplicates)
        return $node;
    }

    // Search for a value
    public function search($data) {
        return $this->searchRec($this->root, $data);
    }

    private function searchRec($node, $data) {
        if ($node === null) return false;
        $cmp = strcasecmp($data, $node->data);
        if ($cmp === 0) return true;
        if ($cmp < 0) {
            return $this->searchRec($node->left, $data);
        } else {
            return $this->searchRec($node->right, $data);
        }
    }

    // Inorder traversal returns alphabetical array of titles
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

/* Example usage when run directly */
if (php_sapi_name() === 'cli') {
    $titles = [
        "A Brief History of Time",
        "Becoming",
        "Gone Girl",
        "Harry Potter",
        "Sherlock Holmes",
        "The Hobbit"
    ];

    $bst = new BinarySearchTree();
    foreach ($titles as $t) {
        $bst->insert($t);
    }

    echo "Inorder Traversal (Alphabetical):" . PHP_EOL;
    foreach ($bst->inorderTraversal() as $t) {
        echo $t . PHP_EOL;
    }

    $searches = ["The Hobbit", "Inferno"];
    foreach ($searches as $s) {
        echo "Searching for \"$s\": " . ($bst->search($s) ? "Found!" : "Not Found.") . PHP_EOL;
    }
}
