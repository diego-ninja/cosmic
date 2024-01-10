<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Command\Builder\Resolver;

use Invoker\ParameterResolver\ParameterResolver;
use ReflectionFunctionAbstract;

class HyphenatedInputResolver implements ParameterResolver
{
    /**
     * @param ReflectionFunctionAbstract $reflection
     * @param array<string,mixed> $providedParameters
     * @param array<string,mixed> $resolvedParameters
     * @return array<int|string,mixed>
     */
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
