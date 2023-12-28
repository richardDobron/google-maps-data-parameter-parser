<?php

namespace dobron;

class GoogleMapsQueryArgs
{
    public static function decode(string $protocolBuffer): array
    {
        $messages = explode('!', trim($protocolBuffer, '!'));

        return static::parse($messages);
    }

    public static function encode(array $messages, int $realKey = null): string
    {
        $segments = [];

        foreach ($messages as $key => $message) {
            if (is_array($message)) {
                if (array_is_list($message)) {
                    $segments[] = self::encode($message, $key);
                } else {
                    $segments[] = ($realKey ?? $key) . 'm' . self::countElements($message) . self::encode($message);

                }
            } else {
                $segments[] = ($realKey ?? $key) . $message;
            }
        }

        return ($realKey ? '' : '!') . implode('!', $segments);
    }

    protected static function countElements(array $array, int $total = 0): int
    {
        foreach ($array as $value) {
            if (is_array($value)) {
                if (array_is_list($value)) {
                    $total += self::countElements($value);
                } else {
                    $total += self::countElements($value, 1);
                }
            } else {
                $total++;
            }
        }

        return $total;
    }

    protected static function parse(array $messages): array
    {
        $result = [];

        for ($i = 0; $i < count($messages); $i++) {
            $message = $messages[$i];

            if (preg_match('/^(\d+)m(\d+)/', $message, $matches)) {
                $key = $matches[1];
                $length = intval($matches[2]);

                if (isset($result[$key])) {
                    $result[$key] = [$result[$key], static::parse(array_slice($messages, $i + 1, $length))];
                } else {
                    $result[$key] = static::parse(array_slice($messages, $i + 1, $length));
                }

                $i += $length;
            } elseif (preg_match('/^(\d+)([bdefisuvxyz])(.*)$/', $message, $matches)) {
                $key = $matches[1];
                $type = $matches[2];
                $value = $matches[3];

                if (isset($result[$key])) {
                    if (!is_array($result[$key])) {
                        $result[$key] = [$result[$key]];
                    }

                    $result[$key][] = $type . $value;
                } else {
                    $result[$key] = $type . $value;
                }
            } else {
                throw new \RuntimeException('Unknown param format: ' . $message);
            }
        }

        return $result;
    }
}
