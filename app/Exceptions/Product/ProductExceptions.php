<?php

namespace App\Exceptions\Product;

use App\Exceptions\BaseException;

class ProductNotFoundException extends BaseException
{
    protected $statusCode = 404;
    protected $errorCode = 'PRODUCT_NOT_FOUND';

    public function __construct(string $message = 'Product not found')
    {
        parent::__construct($message);
    }
}

class ProductCreationException extends BaseException
{
    protected $statusCode = 422;
    protected $errorCode = 'PRODUCT_CREATION_FAILED';

    public function __construct(string $message = 'Failed to create product')
    {
        parent::__construct($message);
    }
}

class ProductUpdateException extends BaseException
{
    protected $statusCode = 422;
    protected $errorCode = 'PRODUCT_UPDATE_FAILED';

    public function __construct(string $message = 'Failed to update product')
    {
        parent::__construct($message);
    }
}

class ProductDeletionException extends BaseException
{
    protected $statusCode = 422;
    protected $errorCode = 'PRODUCT_DELETION_FAILED';

    public function __construct(string $message = 'Failed to delete product')
    {
        parent::__construct($message);
    }
}

class ProductImageUploadException extends BaseException
{
    protected $statusCode = 422;
    protected $errorCode = 'PRODUCT_IMAGE_UPLOAD_FAILED';

    public function __construct(string $message = 'Failed to upload product image')
    {
        parent::__construct($message);
    }
}