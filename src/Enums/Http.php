<?php
/**
 * This file is part of the DreamFactory Rave(tm)
 *
 * DreamFactory Rave(tm) <http://github.com/dreamfactorysoftware/rave>
 * Copyright 2012-2014 DreamFactory Software, Inc. <support@dreamfactory.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
namespace Rave\Enums;

/**
 * general purpose constant container for HTTP stuff
 */
class Http extends SeedEnum
{
    //*************************************************************************
    //* Constants
    //*************************************************************************

    //.........................................................................
    //. Success/Status (2xx)
    //.........................................................................

    /**
     * @var int
     */
    const OK = 200;
    /**
     * @var int
     */
    const CREATED = 201;
    /**
     * @var int
     */
    const ACCEPTED = 202;
    /**
     * @var int
     */
    const NON_AUTHORITATIVE_INFORMATION = 203;
    /**
     * @var int
     */
    const NO_CONTENT = 204;
    /**
     * @var int
     */
    const RESET_CONTENT = 205;
    /**
     * @var int
     */
    const PARTIAL_CONTENT = 206;

    //.........................................................................
    //. REDIRECTION (3XX)
    //.........................................................................

    /**
     * @var int
     */
    const MULTIPLE_CHOICES = 300;
    /**
     * @var int
     */
    const MOVED_PERMANENTLY = 301;
    /**
     * @var int
     */
    const FOUND = 302;
    /**
     * @var int
     */
    const SEE_OTHER = 303;
    /**
     * @var int
     */
    const NOT_MODIFIED = 304;
    /**
     * @var int
     */
    const USE_PROXY = 305;
    /**
     * @var int
     */
    const TEMPORARY_REDIRECT = 307;

    //.........................................................................
    //. CLIENT ERRORS (4XX)
    //.........................................................................

    /**
     * @var int
     */
    const BAD_REQUEST = 400;
    /**
     * @var int
     */
    const UNAUTHORIZED = 401;
    /**
     * @var int
     */
    const PAYMENT_REQUIRED = 402;
    /**
     * @var int
     */
    const FORBIDDEN = 403;
    /**
     * @var int
     */
    const NOT_FOUND = 404;
    /**
     * @var int
     */
    const METHOD_NOT_ALLOWED = 405;
    /**
     * @var int
     */
    const NOT_ACCEPTABLE = 406;
    /**
     * @var int
     */
    const PROXY_AUTHENTICATION_REQUIRED = 407;
    /**
     * @var int
     */
    const REQUEST_TIMEOUT = 408;
    /**
     * @var int
     */
    const CONFLICT = 409;
    /**
     * @var int
     */
    const GONE = 410;
    /**
     * @var int
     */
    const LENGTH_REQUIRED = 411;
    /**
     * @var int
     */
    const PRECONDITION_FAILED = 412;
    /**
     * @var int
     */
    const REQUEST_ENTITY_TOO_LARGE = 413;
    /**
     * @var int
     */
    const REQUEST_URI_TOO_LONG = 414;
    /**
     * @var int
     */
    const UNSUPPORTED_MEDIA_TYPE = 415;
    /**
     * @var int
     */
    const REQUESTED_RANGE_NOT_SATISFIABLE = 416;
    /**
     * @var int
     */
    const EXPECTATION_FAILED = 417;

    //.........................................................................
    //. Server Errors (5xx)
    //.........................................................................

    /**
     * @var int
     */
    const INTERNAL_SERVER_ERROR = 500;
    /**
     * @var int
     */
    const NOT_IMPLEMENTED = 501;
    /**
     * @var int
     */
    const BAD_GATEWAY = 502;
    /**
     * @var int
     */
    const SERVICE_UNAVAILABLE = 503;
    /**
     * @var int
     */
    const GATEWAY_TIMEOUT = 504;
    /**
     * @var int
     */
    const HTTP_VERSION_NOT_SUPPORTED = 505;
}
