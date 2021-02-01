<?php
namespace Grav\Plugin\Console;

use Grav\Console\ConsoleCommand;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class DumpCommand extends ConsoleCommand {


    protected function configure()
    {
        $this
            ->setName("dump")
            ->setDescription("Dumps the SEO metadata informations")
            ->setHelp('Dumps the SEO metadata informations');
    }

    /**
     * @return int|null|void
     */
    protected function serve() {
        $path = getcwd() . '/user/pages';
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($files as $fileinfo) {
            if ($fileinfo->isFile() && $fileinfo->getFilename() === 'seo.json') {
                dump('deleted: ' . $fileinfo->getRealPath());
                unlink($fileinfo->getRealPath());
            }
        }
    }
}