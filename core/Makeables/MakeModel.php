<?php

namespace Core\Makeables;

use Core\Contracts\MakeableContract;
use Exception;

class MakeModel extends Makeable implements MakeableContract
{
    protected string $type = 'Model';

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
        return $this->resolveStubPath('/../Stubs/model.stub');
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
        return is_dir(appPath('Models')) ? $rootNamespace.'\Models' : $rootNamespace;
    }
}