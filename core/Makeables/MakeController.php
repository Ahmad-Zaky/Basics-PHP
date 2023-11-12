<?php

namespace Core\Makeables;

use Core\Contracts\MakeableContract;

class MakeController extends Makeable implements MakeableContract
{
    protected string $type = 'Controller';


    public function make(string $name): void 
    {
        parent::make($name);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return $this->resolveStubPath('/../Stubs/controller.stub');
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveStubPath(string $stub): string
    {
        return file_exists($customPath = __DIR__.trim($stub, '/'))
            ? $customPath
            : __DIR__.$stub;
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace(string $rootNamespace): string
    {
        return is_dir(appPath('Controllers')) ? $rootNamespace.'\Controllers' : $rootNamespace;
    }
}