<?php

namespace dobron;

class GoogleMapsQueryArgs
{
    /**
     * Decodes a protocol buffer string into an array.
     *
     * @param string $protocolBuffer The protocol buffer string.
     * @return array The decoded array.
     * @throws \InvalidArgumentException If the protocol buffer string is invalid.
     */
    public static function decode(string $protocolBuffer): array
    {
        $messages = explode('!', trim($protocolBuffer, '!'));

        return static::parse($messages);
    }

    /**
     * Encodes an array into a protocol buffer string.
     *
     * @param array $messages The array to encode.
     * @param int|null $realKey Optional real key for nested arrays.
     * @return string The encoded protocol buffer string.
     */
    public static function encode(array $messages, int $realKey = null): string
    {
        $segments = array_map(function ($key, $message) use ($realKey) {
            if (is_array($message)) {
                if (array_is_list($message)) {
                    return static::encode($message, $key);
                }

                return ($realKey ?? $key) . 'm' . static::countElements($message) . static::encode($message);
            }

            return ($realKey ?? $key) . $message;
        }, array_keys($messages), $messages);

        return ($realKey ? '' : '!') . implode('!', $segments);
    }

    /**
     * Counts the number of elements in an array.
     *
     * @param array $array The array to count.
     * @param int $initial The initial count.
     * @return int The total number of elements.
     */
    protected static function countElements(array $array, int $initial = 0): int
    {
        foreach ($array as $value) {
            if (is_array($value)) {
                if (array_is_list($value)) {
                    $initial += static::countElements($value);
                } else {
                    $initial += static::countElements($value, 1);
                }
            } else {
                $initial++;
            }
        }

        return $initial;
    }

    /**
     * Parses an array of messages into a decoded array.
     *
     * @param array $messages The array of messages.
     * @return array The decoded array.
     * @throws \InvalidArgumentException If a message has an unknown format.
     */
    protected static function parse(array $messages): array
    {
        $count = count($messages);
        $result = [];

        for ($i = 0; $i < $count; $i++) {
            $message = $messages[$i];

            if (preg_match('/^(\d+)m(\d+)/', $message, $matches)) {
                $key = $matches[1];
                $length = (int)$matches[2];

                $parsed = self::parse(array_slice($messages, $i + 1, $length));

                if (isset($result[$key])) {
                    $result[$key] = [$result[$key], $parsed];
                } else {
                    $result[$key] = $parsed;
                }

                $i += $length;
            } elseif (preg_match('/^(\d+)([bdefisuvxyz])(.*)$/', $message, $matches)) {
                $key = $matches[1];
                $type = $matches[2];
                $value = $matches[3];

                $computed = $type . $value;

                if (isset($result[$key])) {
                    $result[$key] = array_merge((array)$result[$key], [$computed]);
                } else {
                    $result[$key] = $computed;
                }
            } else {
                throw new \InvalidArgumentException('Unknown param format: ' . $message);
            }
        }

        return $result;
    }
}
