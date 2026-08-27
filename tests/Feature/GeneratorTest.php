<?php

namespace Tests\Feature;

use Tests\TestCase;

class GeneratorTest extends TestCase
{
    protected function tearDown(): void
    {
        // Cleanup TestProduct if left over
        $this->artisan('revert:scaffold', ['model' => 'TestProduct', '--force' => true]);
        parent::tearDown();
    }

    public function test_scaffold_generates_all_expected_files(): void
    {
        $this->artisan('generate:scaffold', [
            'model' => 'TestProduct',
            '--fields' => 'name:string:text:required,price:decimal:number:required',
            '--api' => true,
            '--soft-deletes' => true,
            '--force' => true,
            '--no-interaction' => true,
        ])->assertExitCode(0);

        // Verify files created
        $this->assertFileExists(app_path('Models/TestProduct.php'));
        $this->assertFileExists(app_path('Http/Controllers/TestProductController.php'));
        $this->assertFileExists(app_path('Http/Controllers/Api/TestProductApiController.php'));
        $this->assertFileExists(app_path('Http/Requests/CreateTestProductRequest.php'));
        $this->assertFileExists(app_path('Http/Requests/UpdateTestProductRequest.php'));
        $this->assertFileExists(app_path('Livewire/Tables/TestProductTable.php'));
        $this->assertFileExists(app_path('Policies/TestProductPolicy.php'));
        $this->assertFileExists(app_path('Http/Resources/TestProductResource.php'));
    }

    public function test_revert_scaffold_removes_generated_files(): void
    {
        // First generate
        $this->artisan('generate:scaffold', [
            'model' => 'TestProduct',
            '--fields' => 'name:string:text:required',
            '--api' => true,
            '--force' => true,
            '--no-interaction' => true,
        ])->assertExitCode(0);

        // Then revert
        $this->artisan('revert:scaffold', [
            'model' => 'TestProduct',
            '--force' => true,
            '--no-interaction' => true,
        ])->assertExitCode(0);

        // Verify files removed
        $this->assertFileDoesNotExist(app_path('Models/TestProduct.php'));
        $this->assertFileDoesNotExist(app_path('Http/Controllers/TestProductController.php'));
        $this->assertFileDoesNotExist(app_path('Http/Controllers/Api/TestProductApiController.php'));
        $this->assertFileDoesNotExist(app_path('Http/Requests/CreateTestProductRequest.php'));
        $this->assertFileDoesNotExist(app_path('Http/Requests/UpdateTestProductRequest.php'));
        $this->assertFileDoesNotExist(app_path('Livewire/Tables/TestProductTable.php'));
        $this->assertFileDoesNotExist(app_path('Policies/TestProductPolicy.php'));
        $this->assertFileDoesNotExist(app_path('Http/Resources/TestProductResource.php'));
    }
}
