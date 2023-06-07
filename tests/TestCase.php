<?

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Transave\ScolaCbt\ScolaCbtServiceProvider;

class TestCase extends BaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->app->make(Factory::class)->load($this->baseDir().DIRECTORY_SEPARATOR.'database'.DIRECTORY_SEPARATOR.'factories');

        // additional setup
    }

    protected function getPackageProviders($app)
    {
        return [
            ScolaCbtServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // perform environment setup
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom($this->baseDir() . '/database/migrations');
    }

    private function baseDir(){
        return str_replace('tests','src',__DIR__);
    }
}
