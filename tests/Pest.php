<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

use App\Domains\Auth\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

pest()->extend(Tests\TestCase::class)
 // ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

beforeEach(function () {
    config(['auth.defaults.guard' => 'web']);
    config(['permission.defaults.guard_name' => 'web']);

    app(PermissionRegistrar::class)->forgetCachedPermissions();
});

function givePermissions(User $user, array $perms): User
{
    $guard = 'web';

    foreach ($perms as $perm) {
        Permission::findOrCreate($perm, $guard);
    }

    $user->givePermissionTo($perms);

    return $user;
}

function loginWithPerm(User $user, array $perms): User {
    $user->assignRole('customer');
    givePermissions($user, $perms);
    return $user;
}

function loginAdminWithPerm(User $user, array $perms): User {
    $user->assignRole('admin');
    givePermissions($user, $perms);
    return $user;
}
