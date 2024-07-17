<?php

namespace App\Traits;

use Exception;

trait ApiTrait
{
    protected $status = false;
    protected $message = '';
    protected $data = null;
    protected $statusCode = 200;

    private function response()
    {
        $content = [
            'status'    => $this->status,
            'message'   => !empty($message) ? $message : $this->message,
            'data'      => $this->data,
        ];
        return response($content, $this->statusCode);
    }
    protected function apiSuccess($data = null, $message = 'Success Response', $statusCode = 200)
    {
        $this->status = true;
        $this->message = $message;
        $this->statusCode = $statusCode;
        $this->data = $data;
        return $this->response();
    }

    protected function apiFailed($data = null, $message = 'Failed Response', $statusCode = 400)
    {
        $this->status = false;
        $this->message = $message;
        $this->statusCode = $statusCode;
        $this->data = $data;
        return $this->response();
    }

    /**
     * Get Errors
     *
     * @param Exception $e
     * @return void
     */
    protected function getError($e = null)
    {
        if (!empty($e)) {
            if ($e->getCode() == 400) {
                return $e->getMessage();
            }
            if (env('APP_ENV') == 'local') {
                return $e->getMessage() . ' On File ' . $e->getFile() . ' on line ' . $e->getLine();
            }
            return $this->apiOutput(400, $e->getMessage());
        }
        return $this->apiOutput(400, 'Bad request');
    }
}
