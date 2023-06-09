<?

use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Transave\ScolaCbt\ScolaCbtServiceProvider;

class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->app->make(EloquentFactory\::class)->load($this->baseDir().DIRECTORY_SEPARATOR.'database'.DIRECTORY_SEPARATOR.'factories');

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
