<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

class JsonApiValidationErrorResponse extends JsonResponse
{

    /**
     * @param ValidationException $exception
     */
    public function __construct(\Illuminate\Validation\ValidationException $exception, $status = 422)
    {


        //        $errors = [];
        //with foreach the exceptions
//        foreach($exception->errors() as $field => $message) {
//            $pointer = "/".str_replace('.','/',$field);
//
//            $errors[] =[
//                'title'   => $title,
//                'detail'  => $message[0],
//                'source'  => [
//                    'pointer' => $pointer,
//                ]
//            ];
//        };

        //with collect the exceptions
//
//        $errors = collect($exception->errors())
//            ->map(function ($message, $field) use ($title) {
//                return [
//                    'title'   => $title,
//                    'detail'  => $message[0],
//                   'source'  => [
//                        'pointer' => "/".str_replace('.','/',$field),
//                    ]
//                ];
//            })->values();
        $data = $this->formatJsonApiErrors($exception);

        $headers = ['content-type' => 'application/vnd.api+json'];


        parent::__construct($data, $status, $headers);

    }

    /**
     * @param \Illuminate\Validation\ValidationException|ValidationException $exception

     * @return array
     */
    protected function formatJsonApiErrors(\Illuminate\Validation\ValidationException|ValidationException $exception): array
    {
        $title = $exception->getMessage();

        return [
            'errors' => collect($exception->errors())
                ->map(function ($message, $field) use ($title) {
                    return [
                        'title' => $title,
                        'detail' => $message[0],
                        'source' => [
                            'pointer' => "/" . str_replace('.', '/', $field),
                        ]
                    ];
                })->values()
        ];
    }
}
