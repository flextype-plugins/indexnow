<?php

declare(strict_types=1);

/**
 * Copyright (c) Sergey Romanenko (https://awilum.github.io)
 *
 * Licensed under The MIT License.
 *
 * For full copyright and license information, please see the LICENSE
 * Redistributions of files must retain the above copyright notice.
 */

namespace Flextype\Plugin\Indexnow\Console\Commands\Indexnow;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use function Flextype\Plugin\Indexnow\indexnow;
use function Thermage\div;
use function Thermage\renderToString;
use function Flextype\registry;
use function Flextype\entries;
use function Glowy\Strings\strings;

class IndexnowCommand extends Command
{
    protected function configure(): void
    {
        $this->setName('indexnow');
        $this->setDescription('Notifying search engines using indexnow protocol whenever their website content is changed.');
        $this->addArgument('id', InputArgument::OPTIONAL, 'Unique identifier of the entry.');
        $this->addArgument('options', InputArgument::OPTIONAL, 'Options array.');
        $this->addOption('site-url', null, InputOption::VALUE_REQUIRED, 'Site url (without trailing).');
        $this->addOption('collection', null, InputOption::VALUE_NONE, 'Set this flag to fetch entries collection.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $elapsedTimeStartPoint = microtime(true);

        $id      = $input->getArgument('id') ? $input->getArgument('id') : '';
        $options = [];

        if ($id == '') {
            $options['collection'] = true;
        }

        if ($input->getOption('site-url')) {
            registry()->set('flextype.settings.base_url', $input->getOption('site-url'));
            registry()->set('flextype.settings.base_path', '');
        } else {
            registry()->set('flextype.settings.base_url', registry()->get('plugins.site.settings.static.site_url'));
            registry()->set('flextype.settings.base_path', '');
        }

        if ($input->getArgument('options')) {
            if (strings($input->getArgument('options'))->isJson()) {
                $options = serializers()->json()->decode($input->getArgument('options'));
            } else {
                parse_str($input->getArgument('options'), $options);
            }
        }

        $input->getOption('collection') and $options['collection'] = true;

        $options['find'] = ['depth' => '> 0'];

        // Start indexin
        $output->write(
            renderToString(
                div('Indexing...', 
                    'px-2 pt-1 pb-1')
            )
        );

        // Get data from mock file
        //$entries = json_decode(file_get_contents(FLEXTYPE_ROOT_DIR . '/mock.json'), true);
        
        $entries = entries()->fetch($id, $options)->toArray();

        if ($options['collection'] === true) {
            foreach ($entries as $entry) {
                $indexResult = indexnow($entry['id']);
                $output->write(
                    renderToString(
                        div('Url "' . registry()->get('plugins.indexnow.settings.site_url') . '/' . $entry['id'] . (registry()->get('plugins.indexnow.settings.trailing_slash') ? '/' : '') . '" sent for indexing.', 
                            'px-2')
                    )
                );  
                foreach ($indexResult as $key => $value) { 
                    if ($value['statusCode'] == 200) {
                        $output->write(
                            renderToString(
                                div('[b]✓[/b] ' . $key, 
                                    'color-success px-2')
                            )
                        );  
                    } else {
                        $output->write(
                            renderToString(
                                div('[b]x[/b] ' . $key, 
                                    'color-danger px-2')
                            )
                        );  
                    }
                }
                $output->write(
                    renderToString(
                        div()
                    )
                );  
            }
        }

        $output->write(
            renderToString(
                div('Done in [b]'. sprintf("%01.4f", microtime(true) - $elapsedTimeStartPoint) .'[/b] seconds.', 'px-2 pt-1 pb-1')
            )
        );

        return Command::SUCCESS;
    }
}