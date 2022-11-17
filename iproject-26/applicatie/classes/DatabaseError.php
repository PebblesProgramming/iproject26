<?php

class DatabaseError extends \Exception {
    // Error types
    public const PRODUCT_ERR = Product::class;
    public const REVIEW_ERR = Review::class;

    private $type;

    public function __construct($message, $type, Throwable $previous = null, $code = 0) {
        parent::__construct($message, $code, $previous);

        $this->type = $type;
    }

    public function getType() {
        return $this->type;
    }
}