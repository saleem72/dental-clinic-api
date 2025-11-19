<?php


if (!function_exists('apiResponse')) {
    function apiResponse(
        $data = null,
        bool $success = true,
        ?string $message = null,
        ?int $error_code = null,
        $validation_errors = null,
        int $status = 200,
    )
    {
        $response = [
            'success' => $success,
        ];

        if ($message !== null) {
            $response['message'] = $message;
        }

        if ($validation_errors !== null) {
            $response['validation_errors'] = $validation_errors;
        }

        if ($data !== null) {
            $response['data'] = $data;
        }

        if ($error_code !== null) {
            $response['error_code'] = $error_code;
        }

        return response()->json($response, $status);
    }
}

if (!function_exists('formatValidationErrors')) {
    function formatValidationErrors($errors)
    {
        $formattedErrors = [];

        foreach ($errors as $field => $messages) {
            $formattedErrors[] = [
                'field' => $field,
                'errors' => (array) $messages, // Ensure messages is an array
            ];
        }

        return $formattedErrors;
    }
}

if (!function_exists('replace_value_in_array')) {
    function replace_value_in_array(array &$array, $oldValue, $newValue): array
    {
        $key = array_search($oldValue, $array);

        if ($key !== false) {
            $array[$key] = $newValue;
        }

        return $array;
    }
}
