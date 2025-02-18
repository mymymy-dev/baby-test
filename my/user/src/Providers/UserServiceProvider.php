<?php

namespace My\User\Providers;

use Illuminate\Support\ServiceProvider;
use My\User\Models\User;

class UserServiceProvider extends ServiceProvider
{
	public function register(): void
	{
	}
	
	public function boot(): void
	{
        $this->loadRoutesFrom(__DIR__ . '/../../routes/api_v1.php');

        User::shouldBeStrict();
	}
}
