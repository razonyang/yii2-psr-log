<?php
namespace RazonYang\Yii2\Psr\Log\Tests\Unit;

use yii\log\Logger as YiiLogger;
use Codeception\Test\Unit;
use Psr\Log\LogLevel;
use RazonYang\Yii2\Psr\Log\Logger;
use Yii;

class LoggerTest extends Unit
{
    /**
     * @dataProvider dataLog
     */
    public function testLog(
        string $level,
        string $message,
        array $context,
        int $expectedLevel,
        string $expectedMessage,
        string $expectedCategory
    ): void {
        $yiiLogger = Yii::createObject('yii\log\Logger');
        Yii::setLogger($yiiLogger);

        $logger = new Logger();
        $logger->log($level, $message, $context);
        $message = Yii::getLogger()->messages[0];
        $this->assertSame($expectedMessage, $message[0]);
        $this->assertSame($expectedLevel, $message[1]);
        $this->assertSame($expectedCategory, $message[2]);
    }

    public function dataLog(): array
    {
        return [
            [LogLevel::EMERGENCY, 'foo', [], YiiLogger::LEVEL_ERROR, 'foo', 'application'],
            [LogLevel::ALERT, 'bar', ['__CATEGORY__' => __METHOD__], YiiLogger::LEVEL_ERROR, 'bar', __METHOD__],
            [LogLevel::CRITICAL, 'foo', [], YiiLogger::LEVEL_ERROR, 'foo', 'application'],
            [LogLevel::ERROR, 'foo', [], YiiLogger::LEVEL_ERROR, 'foo', 'application'],
            [LogLevel::WARNING, 'foo', [], YiiLogger::LEVEL_WARNING, 'foo', 'application'],
            [LogLevel::NOTICE, 'foo', [], YiiLogger::LEVEL_INFO, 'foo', 'application'],
            [LogLevel::INFO, 'foo', [], YiiLogger::LEVEL_INFO, 'foo', 'application'],
            [LogLevel::DEBUG, 'hi {name}', ['name' => 'foo'], YiiLogger::LEVEL_TRACE, 'hi foo', 'application'],
        ];
    }

    /**
     * @dataProvider dataConstruct
     */
    public function testConstruct(int $defaultLevel, string $categoryParam, array $levelMap): void
    {
        $logger = new Logger($categoryParam, $levelMap, $defaultLevel);
        foreach (['categoryParam', 'defaultLevel', 'levelMap'] as $name) {
            $property = new \ReflectionProperty(Logger::class, $name);
            $property->setAccessible(true);
            $this->assertSame($$name, $property->getValue($logger));
        }
    }

    public function dataConstruct(): array
    {
        return [
            [YiiLogger::LEVEL_ERROR, 'foo', []],
            [YiiLogger::LEVEL_WARNING, 'bar', [LogLevel::CRITICAL => YiiLogger::LEVEL_ERROR]],
        ];
    }

    /**
     * @dataProvider dataInterpolate
     */
    public function testInterpolate(string $message, array $context, string $expected): void
    {
        $logger = new Logger();
        $method = new \ReflectionMethod(Logger::class, 'interpolate');
        $method->setAccessible(true);
        $this->assertSame($expected, $method->invoke($logger, $message, $context));
    }

    public function dataInterpolate(): array
    {
        return [
            ['hello world', [], 'hello world'],
            ['hello {name}', ['name' => 'foo'], 'hello foo'],
        ];
    }
}
