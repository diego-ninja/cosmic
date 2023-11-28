<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Resolver;

use Invoker\ParameterResolver\ParameterResolver;
use ReflectionFunctionAbstract;

class HyphenatedInputResolver implements ParameterResolver
{
    public function getParameters(
        ReflectionFunctionAbstract $reflection,
        array $providedParameters,
        array $resolvedParameters
    ): array {
        $parameters = [];

        foreach ($reflection->getParameters() as $index => $parameter) {
            $parameters[strtolower($parameter->name)] = $index;
        }

        foreach ($providedParameters as $name => $value) {
            $normalizedName = strtolower(str_replace('-', '', $name));

            if (!array_key_exists($normalizedName, $parameters)) {
                continue;
            }

            $normalizedParameterIndex = $parameters[$normalizedName];

            if (array_key_exists($normalizedParameterIndex, $resolvedParameters)) {
                continue;
            }

            $resolvedParameters[$normalizedParameterIndex] = $value;
        }

        return $resolvedParameters;
    }
}
