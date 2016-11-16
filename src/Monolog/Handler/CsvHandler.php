<?php

/*
 * This file is part of the Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FemtoPixel\Monolog\Handler;

use Monolog\Formatter\NormalizerFormatter;
use Monolog\Handler\StreamHandler;

/**
 * Stores to a csv file
 *
 * Can be used to store big loads to physical files and import them later into another system that can handle CSV
 *
 * @author Jay MOULIN <jaymoulin@gmail.com>
 */
class CsvHandler extends StreamHandler
{
    const DELIMITER = ',';
    const ENCLOSURE = '\'';
    const ESCAPE_CHAR = '\\';

    /**
     * @inheritdoc
     */
    protected function streamWrite($resource, array $record)
    {
        if (is_array($record['formatted'])) {
            foreach ($record['formatted'] as $key => $info) {
                if (is_array($info)) {
                    $record['formatted'][$key] = json_encode($info);
                }
            }
        }
        $formated = (array)$record['formatted'];
        if (version_compare(PHP_VERSION, '5.5.4', '>=') && !defined('HHVM_VERSION')) {
            return fputcsv($resource, $formated, static::DELIMITER, static::ENCLOSURE, static::ESCAPE_CHAR);
        }
        return fputcsv($resource, $formated, static::DELIMITER, static::ENCLOSURE);
    }

    /**
     * @inheritdoc
     */
    protected function getDefaultFormatter()
    {
        return new NormalizerFormatter();
    }
}
