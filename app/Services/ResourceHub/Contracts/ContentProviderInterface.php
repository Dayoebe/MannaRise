<?php

namespace App\Services\ResourceHub\Contracts;

interface ContentProviderInterface
{
    public function name(): string;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function search(string $query, array $options = []): array;
}
