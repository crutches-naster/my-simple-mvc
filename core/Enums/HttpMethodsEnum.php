<?php

namespace Core\Enums;

enum HttpMethodsEnum : string
{
    case HTTP_HEAD = "HEAD";
    case HTTP_GET = "GET";
    case HTTP_POST = "POST";
    case HTTP_PUT = "PUT";
}
