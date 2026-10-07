<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class SidebarService
{
    /**
     * Get filtered sidebar sections for the current user.
     *
     * @return array<int, array<string, mixed>>
     */
    public function sections(): array
    {
        $user = Auth::user();
        $menu = config('sidebar.sections', []);

        $sections = [];

        foreach ($menu as $section) {
            $items = $this->filterItems($section['items'] ?? [], $user);

            if (empty($items)) {
                continue;
            }

            if (! $this->userCan($user, $section['permission'] ?? [])) {
                continue;
            }

            $section['items'] = $items;
            $sections[] = $section;
        }

        return $sections;
    }

    /**
     * @param  array<int, mixed>  $items
     * @return array<int, mixed>
     */
    protected function filterItems(array $items, $user): array
    {
        $filtered = [];

        foreach ($items as $item) {
            if (isset($item['children'])) {
                $children = $this->filterItems($item['children'], $user);

                if (empty($children)) {
                    continue;
                }

                if (! $this->userCan($user, $item['permission'] ?? [])) {
                    continue;
                }

                $item['children'] = $children;
                $filtered[] = $item;

                continue;
            }

            if (! $this->itemVisible($item, $user)) {
                continue;
            }

            $filtered[] = $item;
        }

        return $filtered;
    }

    protected function itemVisible(array $item, $user): bool
    {
        if (isset($item['route']) && $item['route'] !== '' && ! $this->routeUsableAsLink($item['route'])) {
            return false;
        }

        return $this->userCan($user, $item['permission'] ?? []);
    }

    protected function routeUsableAsLink(string $name): bool
    {
        if (! Route::has($name)) {
            return false;
        }

        try {
            $route = Route::getRoutes()->getByName($name);

            if (! $route) {
                return false;
            }

            // Parameterized routes cannot be used as sidebar links
            foreach ($route->parameterNames() as $param) {
                if ($param !== '') {
                    return false;
                }
            }

            return true;
        } catch (\Throwable) {
            return false;
        }
    }

    /**
     * @param  array<int, string>|string  $permissions
     */
    protected function userCan($user, $permissions): bool
    {
        if (! $user) {
            return false;
        }

        $permissions = array_values(array_filter((array) $permissions, fn ($p) => $p !== ''));

        if (empty($permissions)) {
            return true;
        }

        foreach ($permissions as $permission) {
            if ($user->can($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * All route names referenced by the sidebar config that are usable as links.
     *
     * @return array<int, string>
     */
    public function referencedRouteNames(): array
    {
        $names = [];

        foreach (config('sidebar.sections', []) as $section) {
            foreach ($section['items'] ?? [] as $item) {
                $this->collectRouteNames($item, $names);
            }
        }

        return array_values(array_unique($names));
    }

    /**
     * Config route names that cannot be rendered as nav links (missing or parameterized).
     *
     * @return array<int, string>
     */
    public function unusableRouteNames(): array
    {
        $unusable = [];

        foreach ($this->referencedRouteNames() as $name) {
            if (! $this->routeUsableAsLink($name)) {
                $unusable[] = $name;
            }
        }

        return $unusable;
    }

    /**
     * @param  array<string, mixed>  $item
     * @param  array<int, string>  $names
     */
    protected function collectRouteNames(array $item, array &$names): void
    {
        if (! empty($item['route'])) {
            $names[] = $item['route'];
        }

        foreach ($item['children'] ?? [] as $child) {
            $this->collectRouteNames($child, $names);
        }
    }
}
