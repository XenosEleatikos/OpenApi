<?php

declare(strict_types=1);

namespace Xenos\OpenApi\Model;

use ArrayObject;
use JsonSerializable;
use stdClass;

use function array_map;

/** @extends ArrayObject<string, ServerVariable> */
class ServerVariables extends ArrayObject implements JsonSerializable
{
    public static function make(stdClass $schemas): self
    {
        $instance = new self();

        foreach ((array)$schemas as $name => $server) {
            $instance[$name] = ServerVariable::make($server);
        }

        return $instance;
    }

    /** @return array<string, stdClass> */
    public function jsonSerialize(): array
    {
        return array_map(
            fn (ServerVariable $serverVariable) => $serverVariable->jsonSerialize(),
            $this->getArrayCopy()
        );
    }
}
