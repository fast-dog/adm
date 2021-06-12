<?php

namespace FastDog\Adm\Command;

use Illuminate\Console\Command;

/**
 * Class CreateResource
 * @package FastDog\Adm\Command
 */
class CreateResource extends Command
{
    /** @var string */
    protected $signature = 'make:resource';

    /** @var string */
    protected $description = 'Create new Resource';

    /**
     * @return string[]
     */
    protected function getStubs(): array
    {
        return [
            __DIR__ . '/stubs/resource/'
        ];
    }

    public function handle()
    {
//        $options = $this->getOptions();
    }
}