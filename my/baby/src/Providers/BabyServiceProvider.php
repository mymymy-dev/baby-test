<?php

namespace My\Baby\Providers;

use Illuminate\Support\ServiceProvider;
use My\Baby\Models\BabyActivity;

class BabyServiceProvider extends ServiceProvider
{
	public function register(): void
	{
	}
	
	public function boot(): void
	{
        BabyActivity::shouldBeStrict();
	}
}
