<?php

namespace Illuminate\Foundation\Console;

use PhpParser\Lexer;
use PhpParser\Parser;
use Illuminate\Console\Command;
use ClassPreloader\ClassPreloader;
use Illuminate\Foundation\Composer;
use ClassPreloader\Parser\DirVisitor;
use ClassPreloader\Parser\FileVisitor;
use ClassPreloader\Parser\NodeTraverser;
use ClassPreloader\Exceptions\SkipFileException;
use Symfony\Component\Console\Input\InputOption;
use PhpParser\PrettyPrinter\Standard as PrettyPrinter;

class OptimizeCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'optimize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize the framework for better performance';

    /**
     * The composer instance.
     *
     * @var \Illuminate\Foundation\Composer
     */
    protected $composer;

    /**
     * Create a new optimize command instance.
     *
     * @param  \Illuminate\Foundation\Composer  $composer
     * @return void
     */
    public function __construct(Composer $composer)
    {
        parent::__construct();

        $this->composer = $composer;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $this->info('Generating optimized class loader');

        if ($this->option('psr')) {
            $this->composer->dumpAutoloads();
        } else {
            $this->composer->dumpOptimized();
        }

        if ($this->option('force') || ! $this->laravel['config']['app.debug']) {
            $this->info('Compiling common classes');
            $this->compileClasses();
        } else {
            $this->call('clear-compiled');
        }
    }

    /**
     * Generate the compiled class file.
     *
     * @return void
     */
    protected function compileClasses()
    {
        $preloader = new ClassPreloader(new PrettyPrinter, new Parser(new Lexer), $this->getTraverser());

        $handle = $preloader->prepareOutput($this->laravel->getCachedCompilePath());

        foreach ($this->getClassFiles() as $file) {
            try {
                fwrite($handle, $preloader->getCode($file, false)."\n");
            } catch (SkipFileException $ex) {
                //
            }
        }

        fclose($handle);
    }

    /**
     * Get the node traverser used by the command.
     *
     * @return \ClassPreloader\Parser\NodeTraverser
     */
    protected function getTraverser()
    {
        $traverser = new NodeTraverser();

        $traverser->addVisitor(new DirVisitor(true));

        $traverser->addVisitor(new FileVisitor(true));

        return $traverser;
    }

    /**
     * Get the classes that should be combined and compiled.
     *
     * @return array
     */
    protected function getClassFiles()
    {
        $app = $this->laravel;

        $core = require __DIR__.'/Optimize/config.php';

        $files = array_merge($core, $app['config']->get('compile.files', []));

        foreach ($app['config']->get('compile.providers', []) as $provider) {
            $files = array_merge($files, forward_static_call([$provider, 'compiles']));
        }

        return $files;
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['force', null, InputOption::VALUE_NONE, 'Force the compiled class file to be written.'],

            ['psr', null, InputOption::VALUE_NONE, 'Do not optimize Composer dump-autoload.'],
        ];
    }
}
