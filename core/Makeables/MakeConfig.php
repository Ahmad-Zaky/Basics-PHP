<?php

namespace Core\Makeables;

use Core\Contracts\MakeableContract;

class MakeConfig extends Makeable implements MakeableContract
{
    protected string $type = 'Config';

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
        return $this->resolveStubPath('/../Stubs/config.stub');
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
        return is_dir(appPath('Configs')) ? $rootNamespace.'\Configs' : $rootNamespace;
    }
}