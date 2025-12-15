<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeService extends Command
{
    protected $signature = 'make:service {name}';
    protected $description = 'Create a new service class';

    public function handle()
    {
        $name = $this->argument('name');
        $parts = explode('/', $name);
        $className = array_pop($parts);
        $namespace = 'App\Services';

        if (!empty($parts)) {
            $namespace .= '\\' . implode('\\', $parts);
        }

        $path = app_path('Services/' . implode('/', $parts));
        $filePath = $path . '/' . $className . '.php';

        if (File::exists($filePath)) {
            $this->info("");
            $this->error("Service {$name} already exists!");
            return;
        }

        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0755, true);
        }

        $stub = <<<EOT
<?php

namespace {$namespace};

use App\Services\Web\WebService;

/**
 * Class {$className}
 * @package App\Services
*/
class {$className} extends WebService
{

}
EOT;

        File::put($filePath, $stub);
        $this->info("");
        $this->info("Service [ {$name} ] created successfully. : [ {$filePath} ]");
    }
}
