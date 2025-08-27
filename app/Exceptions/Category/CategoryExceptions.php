<?php

namespace App\Exceptions\Category;

use App\Exceptions\BaseException;

class CategoryNotFoundException extends BaseException
{
    protected $statusCode = 404;
    protected $errorCode = 'CATEGORY_NOT_FOUND';

    public function __construct(string $message = 'Category not found')
    {
        parent::__construct($message);
    }
}

class CategoryCreationException extends BaseException
{
    protected $statusCode = 422;
    protected $errorCode = 'CATEGORY_CREATION_FAILED';

    public function __construct(string $message = 'Failed to create category')
    {
        parent::__construct($message);
    }
}

class CategoryUpdateException extends BaseException
{
    protected $statusCode = 422;
    protected $errorCode = 'CATEGORY_UPDATE_FAILED';

    public function __construct(string $message = 'Failed to update category')
    {
        parent::__construct($message);
    }
}

class CategoryDeletionException extends BaseException
{
    protected $statusCode = 422;
    protected $errorCode = 'CATEGORY_DELETION_FAILED';

    public function __construct(string $message = 'Failed to delete category')
    {
        parent::__construct($message);
    }
}

class CategoryAlreadyExistsException extends BaseException
{
    protected $statusCode = 409;
    protected $errorCode = 'CATEGORY_ALREADY_EXISTS';

    public function __construct(string $message = 'Category already exists')
    {
        parent::__construct($message);
    }
}