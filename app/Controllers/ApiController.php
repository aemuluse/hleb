<?php

namespace App\Controllers;

use Hleb\Constructor\Handlers\Request;

class ApiController extends \MainController
{
    private $error_code;
    private $error_message;
    private $id;

    /**
     * Возвращает время в секундах в UNIX формате
     */
    public function get_time_unix()
    {
        return time() + microtime();
    }

    /**
     * Возвращает время в MySQL формате
     */
    public function get_time_mysql()
    {
        return date("Y-m-d H:i:s");
    }

    /**
     * Проверяет POST запрос и отправляет ответ.
     * Является входной точкой для маршрута api.
     */
    public function handler()
    {
        $call = Request::getInputBody();
        $request = json_decode($call, true);

        if ($this->isValid($request)) {
            $response = $this->constructResponse($request);
        } else {
            $response = $this->constructError();
        }

        echo json_encode($response);
    }

    /**
     * Проверяет корректность json-запроса
     * @param array|null $request - параметр с массивом из json-запроса
     * @return bool
     */
    private function isValid($request): bool
    {
        if ($request === null) {
            $this->error_code = -32700;
            $this->error_message = "Parse error";
            $this->id = null;
            return false;
        }

        if (
            count($request) !== 3 ||
            !(isset($request["jsonrpc"]) && isset($request["method"]) && isset($request["id"]))
        ) {
            $this->error_code = -32600;
            $this->error_message = "Invalid Request";
            $this->id = null;
            return false;
        }

        if (!($request["method"] === "get_time_unix" || $request["method"] === "get_time_mysql")) {
            $this->error_code = -32601;
            $this->error_message = "Method not found";
            $this->id = $request["id"];
            return false;
        }

        if ($request["params"] !== null) {
            $this->error_code = -32602;
            $this->error_message = "Invalid params";
            $this->id = $request["id"];
            return false;
        }

        return true;
    }

    /**
     * Собирает json-ответ с результатом выполнения
     * функции в массив
     * @param array $request - массив из json-запроса
     * @return array
     */
    private function constructResponse(array $request): array
    {
        $method = $request["method"];
        $result = $this->{$method}();

        return array(
            "jsonrpc" => "2.0",
            "result" => $result,
            "id" => $request["id"]
        );
    }

    /**
     * Собирает json-ответ в виде массива в случае ошибки в запросе
     * @return array
     */
    private function constructError(): array
    {
        $code = $this->error_code;
        $message = $this->error_message;
        $id = $this->id;

        return array(
            "jsonrpc" => "2.0",
            "error" => array(
                "code" => $code,
                "message" => $message
            ),
            "id" => $id
        );
    }
}