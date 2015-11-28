<?php

namespace AppBundle\Command;

use Elasticsearch\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateElasticsearchIndexCommand extends Command
{
    /**
     * @var Client
     */
    private $elasticsearch;

    public function __construct(Client $client)
    {
        $this->elasticsearch = $client;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('elasticsearch:create-index')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Creating Elasticsearch index</info>');

        $this->elasticsearch->indices()->create([
            'index' => 'users',
            'body' => [
                'mappings' => [
                    'user' => [
                        '_source' => [
                            'enabled' => true,
                            'properties' => [
                                'points' => [
                                    'type' => 'integer',
                                    'analyzer' => 'standard'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]);
    }
}