<?php

namespace App\Controllers;

use Hleb\Constructor\Handlers\Request;

class ApiController extends \MainController
{
    private $error_code;
    private $error_message;
    private $id;

    public function get_time_unix() {
        return time();
    }

    public function get_time_mysql() {
        return date("Y-m-d H:i:s");
    }

    # Сделать обработку пакетов (batch)

    public function handler() {
        $call = Request::getInputBody();
        $request = json_decode($call, true);

        if ($this->isValid($request)) {
            $response = $this->constructResponse($request);
        } else {
            $response = $this->constructError();
        }

        echo json_encode($response);
    }

    private function unbatch(string $call): array
    {
        if ($call[0] == '[' and $call[-1] == ']') {
            $batch = trim($call, "[]");
            $call = explode(',', $batch);
        } else {
            $call = [$call];
        }
        return $call;
    }

    private function isValid($request): bool
    {
        if ($request === null) {
            $this->error_code = -32700;
            $this->error_message = "Parse error";
            $this->id = null;
            return false;
        }

        if (count($request) !== 3 ||
            !(isset($request["jsonrpc"]) && isset($request["method"]) && isset($request["id"]))) {
            $this->error_code = -32600;
            $this->error_message = "Invalid Request";
            $this->id = null;
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

    private function constructResponse(array $request): array {
        $method = $request["method"];
        $result = $this->{$method}();

        return array(
            "jsonrpc" => "2.0",
            "result" => $result,
            "id" => $request["id"]
        );
    }

    private function constructError(): array {
        $error_code = $this->error_code;
        $error_message = $this->error_message;
        $id = $this->id;

        return array(
            "jsonrpc" => "2.0",
            "error" => array(
                "code" => $error_code,
                "message" => $error_message
            ),
            "id" => $id
        );
    }


    /**
     * https://ufa.hh.ru/applicant/negotiations/item?topicId=2842097229&chatId=2838436855&hhtmFrom=negotiation_list
     * https://laravel-news.com/json-rpc-server-for-laravel
     * https://overcoder.net/q/913238/%D0%BE%D0%B1%D0%BD%D0%BE%D0%B2%D0%BB%D1%8F%D1%82%D1%8C-%D0%B2%D1%80%D0%B5%D0%BC%D1%8F-%D0%B4%D0%B8%D0%BD%D0%B0%D0%BC%D0%B8%D1%87%D0%B5%D1%81%D0%BA%D0%B8-%D1%81-%D0%BF%D0%BE%D0%BC%D0%BE%D1%89%D1%8C%D1%8E-php-ajax
     */
}
