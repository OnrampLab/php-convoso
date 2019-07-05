<?php
declare(strict_types = 1);
namespace OnrampLab\Convoso;
use Illuminate\Support\ServiceProvider;
class ConvosoServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommands();
    }
    // --------------------------------------------------------------------------------
    //  private
    // --------------------------------------------------------------------------------
    /**
     * Register the Artisan commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
            ]);
        }
    }
}
