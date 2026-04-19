<?php
/**
 * Mini test runner sin PHPUnit. Suficiente para tests smoke.
 *
 * Cada archivo en tests/cases/*.php define funciones globales con prefix `test_`
 * y devuelve void; usar las assertions (assert_true, assert_eq, etc.).
 */

final class TestRunner
{
    public static int $passed = 0;
    public static int $failed = 0;
    /** @var array<int, string> */
    public static array $failures = [];

    public static function run(string $name, callable $fn): void
    {
        try {
            $fn();
            self::$passed++;
            echo "  \033[32m✓\033[0m $name\n";
        } catch (\Throwable $e) {
            self::$failed++;
            self::$failures[] = $name . ': ' . $e->getMessage();
            echo "  \033[31m✗\033[0m $name\n     " . $e->getMessage() . "\n";
            if (getenv('TEST_VERBOSE')) {
                echo $e->getTraceAsString() . "\n";
            }
        }
    }

    public static function group(string $name, callable $fn): void
    {
        echo "\n\033[1m$name\033[0m\n";
        $fn();
    }

    public static function summary(): int
    {
        $total = self::$passed + self::$failed;
        echo "\n----\n";
        echo self::$passed . " passed, " . self::$failed . " failed (of $total)\n";
        return self::$failed === 0 ? 0 : 1;
    }
}

function assert_true(bool $cond, string $msg = ''): void
{
    if (!$cond) {
        throw new \RuntimeException('Assertion failed' . ($msg ? ': ' . $msg : ''));
    }
}

function assert_false(bool $cond, string $msg = ''): void
{
    assert_true(!$cond, $msg);
}

function assert_eq($expected, $actual, string $msg = ''): void
{
    if ($expected !== $actual) {
        $e = is_scalar($expected) ? var_export($expected, true) : json_encode($expected);
        $a = is_scalar($actual)   ? var_export($actual,   true) : json_encode($actual);
        throw new \RuntimeException("Expected $e, got $a" . ($msg ? " — $msg" : ''));
    }
}

function assert_contains(string $needle, string $haystack, string $msg = ''): void
{
    if (strpos($haystack, $needle) === false) {
        $h = mb_strlen($haystack) > 200 ? mb_substr($haystack, 0, 200) . '…' : $haystack;
        throw new \RuntimeException("Expected to contain " . var_export($needle, true)
            . " in: " . $h . ($msg ? " — $msg" : ''));
    }
}

function assert_not_contains(string $needle, string $haystack, string $msg = ''): void
{
    if (strpos($haystack, $needle) !== false) {
        throw new \RuntimeException("Expected NOT to contain " . var_export($needle, true)
            . ($msg ? " — $msg" : ''));
    }
}
