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

use Kisma\Core\Enums\SeedEnum;

/**
 * Various data format constants
 */
class DataFormats extends SeedEnum
{
    //*************************************************************************
    //	Constants
    //*************************************************************************

    /** @type int */
    const RAW = 0;
    /** @type int */
    const JSON = 1;
    /** @type int */
    const XML = 2;

    /** @type int */
    const YAML = 100;
    /** @type int */
    const RAML = 101;

    /** @type int */
    const CSV = 200;
    /** @type int */
    const TSV = 201;
    /** @type int */
    const PSV = 202;

    /** @type int */
    const PHP_ARRAY = 1000;
    /** @type int */
    const PHP_OBJECT = 1001;
    /** @type int */
    const PHP_SIMPLEXML = 1002;
}
