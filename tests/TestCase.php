<?

use Orchestra\Testbench\TestCase as BaseTestCase;
use Transave\ScolaCbt\ScolaCbtServiceProvider;

class TestCase extends BaseTestCase
{

    public function setUp(): void
    {
        parent::setUp();

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
        $this->loadMigrationsFrom(__DIR__ . '../database/migrations');
    }

}
