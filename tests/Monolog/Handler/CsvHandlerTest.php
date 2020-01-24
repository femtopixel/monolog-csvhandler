<?php

namespace FemtoPixel\Monolog\Handler;

use FemtoPixel\Monolog\TestCase;
use Monolog\Logger;
use \Monolog\Formatter\NormalizerFormatter;

class CsvHandlerTest extends TestCase
{
    public function testWrite()
    {
        $handle = fopen('php://memory', 'a+');
        $handler = new CsvHandler($handle);
        $handler->setFormatter($this->getIdentityFormatter());
        $handler->handle($this->getRecord(Logger::WARNING, 'test'));
        $handler->handle($this->getRecord(Logger::WARNING, 'test2'));
        $handler->handle($this->getRecord(Logger::WARNING, 'test3'));
        fseek($handle, 0);
        $this->assertEquals("test\ntest2\ntest3\n", fread($handle, 100));
    }

    public function testWriteWithNormalizer()
    {
        $handle = fopen('php://memory', 'a+');
        $handler = new CsvHandler($handle);
        $handler->setFormatter(new NormalizerFormatter);
        $handler->handle($this->getRecord(Logger::WARNING, 'doesn\'t fail'));
        fseek($handle, 0);
        $regexp = "~\\A'doesn''t fail',\\[\\],300,WARNING,test,[0-9]{4}\\-[0-9]{2}\\-[0-9]{2}T[0-9]{2}:[0-9]{2}:[0-9]{2}\\+[0-9]{2}:[0-9]{2},\\[\\]\n\\Z~";
        $this->assertSame(1, preg_match($regexp, fread($handle, 100)));
    }
}
