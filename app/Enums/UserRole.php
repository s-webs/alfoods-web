<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Manager = 'manager';
    case Cashier = 'cashier';
    case Viewer = 'viewer';

    /**
     * Get permissions for this role.
     *
     * @return array<string>
     */
    public function permissions(): array
    {
        return match ($this) {
            self::Admin => ['*'],
            self::Manager => [
                'categories.*',
                'products.*',
                'sales.*',
                'cashiers.*',
                'shifts.*',
            ],
            self::Cashier => [
                'categories.view',
                'products.view',
                'sales.create',
                'sales.view',
            ],
            self::Viewer => [
                'categories.view',
                'products.view',
                'sales.view',
                'cashiers.view',
                'shifts.view',
            ],
        };
    }

    public function can(string $permission): bool
    {
        $permissions = $this->permissions();

        if (in_array('*', $permissions)) {
            return true;
        }

        foreach ($permissions as $perm) {
            if ($perm === $permission) {
                return true;
            }
            if (str_ends_with($perm, '.*')) {
                $prefix = str_replace('.*', '', $perm);
                if (str_starts_with($permission, $prefix . '.')) {
                    return true;
                }
            }
        }

        return false;
    }

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Administrator',
            self::Manager => 'Manager',
            self::Cashier => 'Cashier',
            self::Viewer => 'Viewer',
        };
    }
}
