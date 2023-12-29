<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Crypt\Parser;

use Carbon\CarbonImmutable;
use Ninja\Cosmic\Crypt\AbstractKey;
use Ninja\Cosmic\Crypt\KeyCollection;
use Ninja\Cosmic\Crypt\KeyInterface;
use Ninja\Cosmic\Crypt\PrivateKey;
use Ninja\Cosmic\Crypt\PublicKey;
use Ninja\Cosmic\Crypt\Uid;
use Ninja\Cosmic\Exception\BinaryNotFoundException;
use Symfony\Component\Process\Process;

use function Cosmic\find_binary;

class KeyParser
{
    /**
     * @throws BinaryNotFoundException
     */
    public function extractKeys(string $keyType): KeyCollection
    {
        $imported_keys = new KeyCollection();
        $process = Process::fromShellCommandline($this->getCommand($keyType));
        if ($process->mustRun()->isSuccessful()) {
            $output = $this->stripHeader($process->getOutput(), 2);

            $keys = explode("\n\n", $output);
            foreach ($keys as $key) {
                if ($key === '' || $key === '0') {
                    continue;
                }

                $lines                  = explode("\n", $key);
                $keyData                = $this->parseKeyInfo($lines[0]);
                $keyData['fingerprint'] = trim($lines[1]);
                $keyData['uid']         = Uid::fromArray($this->parseUidInfo($lines[2]));

                $master_key = $this->buildKey($keyData);

                if (isset($lines[3])) {
                    $subKey = $this->parseKeyInfo($lines[3]);
                    if ($subKey !== null) {
                        $master_key->addSubKey($this->buildKey($subKey));
                    }
                }

                $imported_keys->add($master_key);
            }

        }

        return $imported_keys;
    }

    private function stripHeader(string $string, int $length): string
    {
        $lines = explode("\n", $string);
        $lines = array_slice($lines, $length);
        return implode("\n", $lines);
    }

    /**
     * @return array<string,mixed>|null
     */
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

    /**
     * @param array<string,mixed> $keyData
     */
    private function buildKey(array $keyData): AbstractKey
    {
        $keyType = $keyData['type'];
        if ($keyType === KeyInterface::GPG_TYPE_PUBLIC || $keyType === KeyInterface::GPG_TYPE_SUB) {
            return PublicKey::fromArray($keyData);
        }

        return PrivateKey::fromArray($keyData);
    }

    /**
     * @return array<string,string>
     */
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

    /**
     * @throws BinaryNotFoundException
     */
    private function getCommand(string $keyType): string
    {
        if ($keyType === KeyInterface::GPG_TYPE_PUBLIC) {
            return sprintf("%s --keyid-format LONG --list-keys", find_binary('gpg'));
        }

        return sprintf("%s --keyid-format LONG --list-secret-keys", find_binary('gpg'));
    }
}
