<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Crypt\Parser;

use Carbon\CarbonImmutable;
use Ninja\Cosmic\Crypt\KeyCollection;
use Ninja\Cosmic\Crypt\KeyInterface;
use Ninja\Cosmic\Crypt\PrivateKey;
use Ninja\Cosmic\Crypt\PublicKey;
use Ninja\Cosmic\Crypt\Uid;
use Symfony\Component\Process\Process;

use function Cosmic\find_binary;

class KeyParser
{
    public function extractKeys(string $keyType): KeyCollection
    {
        $imported_keys = new KeyCollection();
        try {
            $process = Process::fromShellCommandline($this->getCommand($keyType));
            if ($process->mustRun()->isSuccessful()) {
                $output = $this->stripHeader($process->getOutput(), 2);

                $keys = explode("\n\n", $output);
                foreach ($keys as $key) {
                    if (empty($key)) {
                        continue;
                    }

                    $lines                  = explode("\n", $key);
                    $keyData                = $this->parseKeyInfo($lines[0]);
                    $keyData['fingerprint'] = trim($lines[1]);
                    $keyData['uid']         = Uid::fromArray($this->parseUidInfo($lines[2]));

                    $master_key = $this->buildKey($keyData);

                    if (isset($lines[3])) {
                        $subkey = $this->parseKeyInfo($lines[3]);
                        $master_key->addSubKey($this->buildKey($subkey));
                    }

                    $imported_keys->add($master_key);
                }

            }
        } catch (\Exception $e) {
            throw $e;
        }

        return $imported_keys;
    }

    private function stripHeader(string $string, int $length): string
    {
        $lines = explode("\n", $string);
        $lines = array_slice($lines, $length);
        return implode("\n", $lines);
    }

    private function parseKeyInfo(string $line): ?array
    {
        $keyType = substr($line, 0, 3);

        preg_match(
            pattern: KeyInterface::GPG_PUB_REGEX_PATTERN,
            subject: $line,
            matches: $matches
        );

        if (!empty($matches)) {
            return[
                'id'        => $matches['key_id'],
                'type'      => $keyType,
                'method'    => $matches['cypher'],
                'createdAt' => CarbonImmutable::createFromFormat('Y-m-d', $matches['created_at']),
                'expiresAt' => isset($matches["expires_at"]) ?
                    CarbonImmutable::createFromFormat('Y-m-d', $matches['expires_at']) :
                    null,
                'usage' => $matches['usage'],
            ];
        }

        return null;

    }

    private function buildKey(array $keyData): KeyInterface
    {
        $keyType = $keyData['type'];
        if ($keyType === KeyInterface::GPG_TYPE_PUBLIC || $keyType === KeyInterface::GPG_TYPE_SUB) {
            return PublicKey::fromArray($keyData);
        }

        return PrivateKey::fromArray($keyData);
    }

    private function parseUidInfo(string $string): array
    {
        preg_match(
            pattern: KeyInterface::GPG_UID_REGEX_PATTERN,
            subject: $string,
            matches: $matches
        );

        return [
            'trustLevel' => $matches['trustLevel'],
            'name'       => $matches['name'],
            'email'      => $matches['email'],
        ];
    }

    private function getCommand(string $keyType): string
    {
        if ($keyType === KeyInterface::GPG_TYPE_PUBLIC) {
            return sprintf("%s --keyid-format LONG --list-keys", find_binary('gpg'));
        }

        return sprintf("%s --keyid-format LONG --list-secret-keys", find_binary('gpg'));
    }
}
