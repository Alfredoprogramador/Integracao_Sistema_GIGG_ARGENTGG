<?php

declare(strict_types=1);

namespace GiggArgentgg\Integration;

final class ConflictResolver
{
    /**
     * @param array{updated_at:string,version:int,status:string} $local
     * @param array{updated_at:string,version:int,status:string} $remote
     *
     * @return array{winner:string,status:string,alert:bool}
     */
    public function resolve(array $local, array $remote): array
    {
        if ($remote['version'] > $local['version']) {
            return ['winner' => 'remote', 'status' => $remote['status'], 'alert' => false];
        }

        if ($remote['version'] < $local['version']) {
            return ['winner' => 'local', 'status' => $local['status'], 'alert' => true];
        }

        if (strtotime($remote['updated_at']) >= strtotime($local['updated_at'])) {
            return ['winner' => 'remote', 'status' => $remote['status'], 'alert' => false];
        }

        return ['winner' => 'local', 'status' => $local['status'], 'alert' => true];
    }
}
