<?php

namespace FemtoPixel\Monolog;

use Monolog\Logger;
use Monolog\Formatter\FormatterInterface;

class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @param int $level
     * @param string $message
     * @param array $context
     * @return array Record
     */
    protected function getRecord($level = Logger::WARNING, $message = 'test', $context = array())
    {
        return array(
            'message' => $message,
            'context' => $context,
            'level' => $level,
            'level_name' => Logger::getLevelName($level),
            'channel' => 'test',
            'datetime' => \DateTime::createFromFormat('U.u', sprintf('%.6F', microtime(true))),
            'extra' => array(),
        );
    }

    /**
     * @return FormatterInterface
     */
    protected function getIdentityFormatter()
    {
        $formatter = $this->createMock(FormatterInterface::class);
        $formatter->expects($this->any())
            ->method('format')
            ->will($this->returnCallback(function ($record) { return $record['message']; }));

        return $formatter;
    }
}
