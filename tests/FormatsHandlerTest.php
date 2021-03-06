<?php declare(strict_types=1);

namespace Pinepain\SimpleRouting\Tests;

use PHPUnit\Framework\TestCase;
use Pinepain\SimpleRouting\FormatsHandler;

class FormatsHandlerTest extends TestCase
{
    /**
     * \Pinepain\SimpleRouting\FormatsHandler::handleDefault
     */
    public function testHandleDefault()
    {
        $handler = new FormatsHandler();

        $this->assertEquals('test', $handler->handleDefault('test'));
        $this->assertEquals('test', $handler->handleDefault('test'));
        $this->assertEquals('test%20spaces', $handler->handleDefault('test spaces'));
        $this->assertEquals('test%2Fslashes', $handler->handleDefault('test/slashes'));
        $this->assertEquals(
            'test%20%E4%B8%AD%E5%9B%BD%20%2F%E4%B8%AD%E5%9C%8B',
            $handler->handleDefault('test 中国 /中國')
        );
        $this->assertEquals(
            'test%20%D0%BA%D0%B8%D1%80%D0%B8%D0%BB%D0%BB%D0%B8%D1%86%D0%B0',
            $handler->handleDefault('test кириллица')
        );
        $this->assertEquals(
            'test%20%E0%A4%B9%E0%A4%BF%E0%A4%82%E0%A4%A6%E0%A5%80',
            $handler->handleDefault('test हिंदी')
        );
    }

    /**
     * \Pinepain\SimpleRouting\FormatsHandler::handleDefault
     */
    public function testHandle()
    {
        $format_handler = $this->getMockBuilder('stdClass')->setMethods(['handle'])->getMock();
        $format_handler->expects($this->once())
                       ->method('handle')
                       ->with('value')
                       ->willReturn('handled');

        /** @var FormatsHandler | \PHPUnit_Framework_MockObject_MockObject $handler */
        $handler = $this->getMockBuilder(FormatsHandler::class)
                        ->setMethods(['handleDefault'])
                        ->setConstructorArgs([['known' => $format_handler]])
                        ->getMock();

        $handler->expects($this->once())
                ->method('handleDefault')
                ->with('value')
                ->willReturn('handled default');

        $this->assertEquals('handled', $handler->handle('known', 'value'));
        $this->assertEquals('handled default', $handler->handle('unknown', 'value'));
    }
}
