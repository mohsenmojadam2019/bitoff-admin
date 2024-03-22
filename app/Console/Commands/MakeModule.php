<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeModule extends Command
{
    protected $signature = 'make:module {name}';

    protected $description = 'make module';

    public function handle()
    {
        $name = Str::title($this->argument('name'));

        $directories = [
            "modules/{$name}/Application/Http/Controllers",
            "modules/{$name}/Application/Http/Requests",
            "modules/{$name}/Application/Http/Resources",
            "modules/{$name}/Application/Models",
            "modules/{$name}/Database/Factories",
            "modules/{$name}/Database/Migrations",
            "modules/{$name}/Providers",
            "modules/{$name}/Resources/lang",
            "modules/{$name}/Resources/views",
            "modules/{$name}/Routes",
            "modules/{$name}/Tests/Feature",
            "modules/{$name}/Tests/Unit",
        ];
        foreach ($directories as $dir) {
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }
        }

        $files = [
            [
                'name' => 'AppServiceProvider',
                'fileName' => "Providers/{$name}ServiceProvider.php",
            ],
            [
                'name' => 'RouteServiceProvider',
                'fileName' => 'Providers/RouteServiceProvider.php',
            ],
            [
                'name' => 'TestCase',
                'fileName' => 'Tests/TestCase.php',
            ],
        ];
        foreach ($files as $item) {
            $file = base_path("/modules/{$name}/{$item['fileName']}");
            if (file_exists($file)) {
                continue;
            }
            $template = file_get_contents(app_path("Console/Commands/stubs/{$item['name']}.stub"));
            file_put_contents($file, $template);
            file_put_contents($file, str_replace('{name}', $name, file_get_contents($file)));
            file_put_contents($file, str_replace('{names}', Str::of($name)->plural()->lower(), file_get_contents($file)));
        }

        $this->output->success("{$name} module created.");

        return 0;
    }
}
