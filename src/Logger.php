<?php
namespace RazonYang\Yii2\Psr\Log;

use yii\base\Configurable;
use yii\log\Logger as YiiLogger;
use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;
use Yii;

class Logger extends AbstractLogger implements Configurable
{
    /**
     * @var string $categoryParam category param name.
     *
     * @see log()
     */
    private $categoryParam;

    /**
     * @var array $levelMap an array that mapping from PSR log level to Yii log level.
     */
    private $levelMap;

    /**
     * @var int $defaultLevel default Yii logger level
     *
     * @see log()
     */
    private $defaultLevel;

    /**
     * @var array $defaultLevelMap default log level map.
     */
    protected $defaultLevelMap = [
        LogLevel::EMERGENCY => YiiLogger::LEVEL_ERROR,
        LogLevel::ALERT => YiiLogger::LEVEL_ERROR,
        LogLevel::CRITICAL => YiiLogger::LEVEL_ERROR,
        LogLevel::ERROR => YiiLogger::LEVEL_ERROR,
        LogLevel::WARNING => YiiLogger::LEVEL_WARNING,
        LogLevel::NOTICE => YiiLogger::LEVEL_INFO,
        LogLevel::INFO => YiiLogger::LEVEL_INFO,
        LogLevel::DEBUG => YiiLogger::LEVEL_TRACE
    ];

    public function __construct(string $categoryParam = '__CATEGORY__', ?array $levelMap = null, $defaultLevel = YiiLogger::LEVEL_INFO)
    {
        $this->categoryParam = $categoryParam;
        $this->levelMap = $levelMap ?? $this->defaultLevelMap;
        $this->defaultLevel = $defaultLevel;
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     */
    public function log($level, $message, array $context = [])
    {
        $level = $this->levelMap[$level] ?? $this->defaultLevel;
        Yii::getLogger()->log($this->interpolate($message, $context), $level, $context[$this->categoryParam] ?? 'application');
    }

    /**
     * Interpolates context values into the message placeholders.
     *
     * @param string $message
     * @param array  $context
     *
     * @return string
     */
    protected function interpolate(string $message, array $context = []): string
    {
        $replace = [];
        foreach ($context as $key => $val) {
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace['{' . $key . '}'] = $val;
            }
        }

        return strtr($message, $replace);
    }
}
