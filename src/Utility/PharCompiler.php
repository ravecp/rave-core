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
namespace Rave\Utility;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Process;

/**
 * Compiles rave into a phar
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Jordi Boggiano <j.boggiano@seld.be>
 * @author Jerry Ablan <jerryablan@dreamfactory.com>
 */
class Compiler
{
    //*************************************************************************
    //	Constants
    //*************************************************************************

    /**
     * @type string
     */
    const DEFAULT_PHAR_FILE = 'rave.phar';
    /**
     * @var string
     */
    const DEFAULT_BIN_FILE = '/bin/rave';

    //*************************************************************************
    //	Members
    //*************************************************************************

    /**
     * @var string
     */
    protected static $_version;
    /**
     * @var string
     */
    protected static $_versionDate;
    /**
     * @var string The base path of the thing
     */
    protected static $_basePath;

    //*************************************************************************
    //	Methods
    //*************************************************************************

    /**
     * Grabs the latest version stats from git
     *
     * @throws \Exception
     */
    protected static function _getVersionData()
    {
        static::$_basePath = dirname( dirname( __DIR__ ) );

        $_gitException = new \RuntimeException(
            'Cannot run "git log". This utility must be run from a "git" clone of the DreamFactory Rave(tm) repository and "git" must be available.'
        );

        static::$_version = static::_runProcess( 'git log --pretty="%H" -n1 HEAD', $_gitException );
        $_date = new \DateTime( static::_runProcess( 'git log -n1 --pretty=%ci HEAD', $_gitException ) );
        $_date->setTimezone( new \DateTimeZone( 'UTC' ) );
        static::$_versionDate = $_date->format( 'Y-m-d H:i:s' );

        $_process = static::_runProcess( 'git describe --tags HEAD', false );

        if ( 0 == $_process->getExitCode() )
        {
            static::$_version = trim( $_process->getOutput() );
        }
    }

    /**
     * Compiles composer into a single phar file
     *
     * @throws \RuntimeException
     *
     * @param  string $pharFile The full path to the file to create
     */
    public static function compile( $pharFile = self::DEFAULT_PHAR_FILE )
    {
        if ( file_exists( $pharFile ) && false === unlink( $pharFile ) )
        {
            throw new \RuntimeException( 'Unable to existing PHAR file.' );
        }

        static::_getVersionData();

        static::_buildPhar( $pharFile );
    }

    /**
     * @param string $pharFile
     */
    protected static function _buildPhar( $pharFile = self::DEFAULT_PHAR_FILE )
    {
        $_phar = new \Phar( $pharFile, 0, static::DEFAULT_PHAR_FILE );
        $_phar->setSignatureAlgorithm( \Phar::SHA256 );
        $_phar->startBuffering();

        $_finder = new Finder();

        $_finder->files()
            ->ignoreVCS( true )
            ->name( '*.php' )
            ->notName( 'PharCompiler.php' )
            ->in( __DIR__ . '/..' );

        foreach ( $_finder as $file )
        {
            static::_addFile( $_phar, $file );
        }

        $_finder = new Finder();
        $_finder->files()
            ->ignoreVCS( true )
            ->name( '*.php' )
            ->exclude( 'Tests' )
            ->in( static::$_basePath . '/vendor/symfony/' )
            ->in( static::$_basePath . '/vendor/dreamfactory/' );

        foreach ( $_finder as $file )
        {
            static::_addFile( $_phar, $file );
        }

        static::_addFile( $_phar, new \SplFileInfo( static::$_basePath . '/vendor/autoload.php' ) );
        static::_addFile( $_phar, new \SplFileInfo( static::$_basePath . '/vendor/composer/autoload_namespaces.php' ) );
        static::_addFile( $_phar, new \SplFileInfo( static::$_basePath . '/vendor/composer/autoload_psr4.php' ) );
        static::_addFile( $_phar, new \SplFileInfo( static::$_basePath . '/vendor/composer/autoload_classmap.php' ) );
        static::_addFile( $_phar, new \SplFileInfo( static::$_basePath . '/vendor/composer/autoload_real.php' ) );

        if ( file_exists( static::$_basePath . '/vendor/composer/include_paths.php' ) )
        {
            static::_addFile( $_phar, new \SplFileInfo( static::$_basePath . '/vendor/composer/include_paths.php' ) );
        }

        static::_addFile( $_phar, new \SplFileInfo( static::$_basePath . '/vendor/composer/ClassLoader.php' ) );
        static::_addRaveBin( $_phar );

        //  Stubs
        $_phar->setStub( static::_getRuntimeStub( $pharFile ) );
        $_phar->stopBuffering();

        if ( function_exists( 'gzcompress' ) )
        {
            $_phar->compressFiles( \Phar::GZ );
        }

        static::_addFile( $_phar, new \SplFileInfo( static::$_basePath . '/LICENSE' ), false );

        unset( $_phar );
    }

    /**
     * @param \Phar        $phar
     * @param \SplFileInfo $file
     * @param bool         $strip
     */
    protected static function _addFile( $phar, $file, $strip = true )
    {
        $_path = strtr( str_replace( dirname( dirname( __DIR__ ) ) . DIRECTORY_SEPARATOR, null, $file->getRealPath() ), '\\', '/' );

        $_content = file_get_contents( $file );

        if ( $strip )
        {
            $_content = static::_stripWhitespace( $_content );
        }
        elseif ( 'LICENSE' === basename( $file ) || 'LICENSE.txt' === basename( $file ) )
        {
            $_content = "\n" . $_content . "\n";
        }

        if ( $_path === 'src/bootstrap.php' )
        {
            $_content = str_replace( array('@package_version@', '@release_date@',), array(static::$_version, static::$_versionDate), $_content );
        }

        $phar->addFromString( $_path, $_content );
    }

    /**
     * @param \Phar $phar
     */
    protected static function _addRaveBin( $phar )
    {
        $phar->addFromString(
            static::DEFAULT_BIN_FILE,
            preg_replace( '{^#!/usr/bin/env php\s*}', null, file_get_contents( static::$_basePath . static::DEFAULT_BIN_FILE ) )
        );
    }

    /**
     * Removes whitespace from a PHP source string while preserving line numbers.
     *
     * @param  string $source A PHP string
     *
     * @return string The PHP string with the whitespace removed
     */
    protected static function _stripWhitespace( $source )
    {
        if ( !function_exists( 'token_get_all' ) )
        {
            return $source;
        }

        $_output = null;

        foreach ( token_get_all( $source ) as $_token )
        {
            if ( is_string( $_token ) )
            {
                $_output .= $_token;
            }
            elseif ( in_array( $_token[0], array(T_COMMENT, T_DOC_COMMENT) ) )
            {
                $_output .= str_repeat( "\n", substr_count( $_token[1], "\n" ) );
            }
            elseif ( T_WHITESPACE === $_token[0] )
            {
                // reduce wide spaces
                $_whitespace = preg_replace( '{[ \t]+}', ' ', $_token[1] );
                // normalize newlines to \n and trim leading spaces
                $_whitespace = preg_replace( array('{(?:\r\n|\r|\n)}', '{\n +}'), "\n", $_whitespace );
                $_output .= $_whitespace;
            }
            else
            {
                $_output .= $_token[1];
            }
        }

        return $_output;
    }

    /**
     * @param string $pharFile
     *
     * @return string
     */
    protected function _getRuntimeStub( $pharFile )
    {
        return <<<BASH
#!/usr/bin/env php
<?php
\Phar::mapPhar('{$pharFile}');
require 'phar://{$pharFile}{static::DEFAULT_BIN_FILE}';
__HALT_COMPILER();
BASH;
    }

    /**
     * @param string                  $command          The command to execute
     * @param string|\Exception|false $exceptionMessage A string for a runtime exception or a custom exception. Passing false returns the post-run process object
     * @param string                  $cwd              The current working directory
     *
     * @throws \RuntimeException|\Exception
     * @return string|Process The results of the process trimmed
     */
    protected static function _runProcess( $command, $exceptionMessage = null, $cwd = __DIR__ )
    {
        $_process = new Process( $command, $cwd );

        $_result = $_process->run();

        if ( false === $exceptionMessage )
        {
            return $_process;
        }

        if ( 0 != $_result )
        {
            if ( $exceptionMessage instanceof \Exception )
            {
                throw $exceptionMessage;
            }

            throw new \RuntimeException( $exceptionMessage, $_result );
        }

        return trim( $_process->getOutput() );
    }
}
