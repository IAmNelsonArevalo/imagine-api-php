<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * The active status value.
     * @var int $active_status
     */
    public int $active_status = 1;
    public int $inactive_status = 2;

    /**
     * Generate an API response array.
     *
     * @param bool $status The status of the transaction.
     * @param array $message The message details.
     * @param mixed $data The data to include in the response.
     * @return array The API response array.
     */
    public function responseApi(bool $status, array $message = [], mixed $data = []): array
    {
        $message['code'] = match ($message['type']) {
            'success' => 200,
            'error' => 500
        };

        return array(
            'transaction' => array('status' => $status),
            'message' => $message,
            'data' => $data
        );
    }
}
