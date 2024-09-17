<?php

declare(strict_types=1);

namespace Xenos\OpenApi\Model;

use ArrayObject;
use JsonSerializable;
use stdClass;
use Xenos\OpenApi\Model\Reference;

use function array_map;

/** @extends ArrayObject<string, Parameter|Reference> */
class ParametersOrReferences extends ArrayObject implements JsonSerializable
{
    /** @param array<string, stdClass> $parametersOrReferences */
    public static function make(array $parametersOrReferences): self
    {
        return new self(
            array_map(
                fn (stdClass $parametersOrReference): Parameter|\Xenos\OpenApi\Model\Reference => Parameter::makeParameterOrReference($parametersOrReference),
                $parametersOrReferences
            )
        );
    }

    /** @return array<string, stdClass> */
    public function jsonSerialize(): array
    {
        return array_map(
            fn (Parameter|\Xenos\OpenApi\Model\Reference $parameter) => $parameter->jsonSerialize(),
            $this->getArrayCopy()
        );
    }

    /** @return Parameter[] */
    public function getParametersByLocation(ParameterLocation $parameterLocation): array
    {
        foreach ($this as $parameter) {
            /** @var Parameter $parameter */
            if ($parameter->in === $parameterLocation) {
                $parameters[] = $parameter;
            }
        }

        return $parameters ?? [];
    }
}
