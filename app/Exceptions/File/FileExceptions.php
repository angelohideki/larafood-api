<?php

namespace App\Exceptions\File;

use App\Exceptions\BaseException;

class FileUploadException extends BaseException
{
    protected $statusCode = 422;
    protected $errorCode = 'FILE_UPLOAD_FAILED';

    public function __construct(string $message = 'File upload failed')
    {
        parent::__construct($message);
    }
}

class FileDeleteException extends BaseException
{
    protected $statusCode = 422;
    protected $errorCode = 'FILE_DELETE_FAILED';

    public function __construct(string $message = 'File deletion failed')
    {
        parent::__construct($message);
    }
}

class FileNotFoundException extends BaseException
{
    protected $statusCode = 404;
    protected $errorCode = 'FILE_NOT_FOUND';

    public function __construct(string $message = 'File not found')
    {
        parent::__construct($message);
    }
}

class InvalidFileTypeException extends BaseException
{
    protected $statusCode = 422;
    protected $errorCode = 'INVALID_FILE_TYPE';

    public function __construct(string $message = 'Invalid file type')
    {
        parent::__construct($message);
    }
}

class FileSizeExceededException extends BaseException
{
    protected $statusCode = 422;
    protected $errorCode = 'FILE_SIZE_EXCEEDED';

    public function __construct(string $message = 'File size exceeded maximum limit')
    {
        parent::__construct($message);
    }
}