<?php
/**
 * This file is part of the DreamFactory Rave(tm)
 *
 * DreamFactory Rave(tm) <http://github.com/dreamfactorysoftware/rave>
 * Copyright 2012-2014 DreamFactory Software, Inc. <support@dreamfactory.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in COMPLIANCE WITH THE LICENSE.
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
namespace Rave;

use Rave\Enums\DataFormats;
use Silex\Application;

/**
 * The main RAVE server
 */
class Server extends Application
{
    //*************************************************************************
    //	Methods
    //*************************************************************************

    /**
     * @param array $values
     */
    public function __construct( array $values = array() )
    {
        parent::__construct( $values );

        $this->_initializeComponents();
    }

    /**
     * Initialize all the components of the server
     *
     * @param array $values
     */
    protected function _initializeComponents( array $values = array() )
    {
        $this['request.data_format'] = DataFormats::JSON;

        //  Exception handler
        $this->error(
            function ( \Exception $ex, $code )
            {
                //  output error
            }
        );
    }

}