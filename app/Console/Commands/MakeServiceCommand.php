<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeServiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service class';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');

        $path = str_replace('\\', '/', $name);
        $segments = explode('/', $path);
        $className = ucfirst(array_pop($segments)) . 'Service';
        $namespace = 'App\\Services' . (count($segments) ? '\\' . implode('\\', $segments) : '');

        $directory = app_path('Services/' . implode('/', $segments));
        $filePath = $directory . '/' . $className . '.php';

        if (File::exists($filePath)) {
            $this->error("Service $className already exists in {$namespace}!");
            return;
        }


        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true, true);
        }


        $content = <<<EOT
<?php

namespace $namespace;

class $className
{
    public function __construct()
    {
        //
    }
}
EOT;

        File::put($filePath, $content);

        $this->info("Service $className created successfully in {$namespace}!");
    }
}
