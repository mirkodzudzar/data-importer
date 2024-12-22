<?php

namespace App;

trait HasImportPermissions
{
    /**
     * Get all available import types for the user based on permissions
     *
     * @return array
     */
    public function getAvailableImportTypes(): array
    {
        $importTypes = config('import-types');

        return array_filter($importTypes, function ($importType) {
            return $this->hasPermission($importType['permission_required']);
        });
    }

    /**
     * Check if user has any import permissions
     *
     * @return bool
     */
    public function hasAnyImportPermission(): bool
    {
        return count($this->getAvailableImportTypes()) > 0;
    }

    /**
     * Check if user can import specific type
     *
     * @param string $importType
     * @return bool
     */
    public function canImport(string $importType): bool
    {
        $importTypes = config('import-types');

        return isset($importTypes[$importType]) &&
               $this->hasPermission($importTypes[$importType]['permission_required']);
    }
}
