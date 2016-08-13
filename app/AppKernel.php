<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new League\Tactician\Bundle\TacticianBundle(),
            new Snc\RedisBundle\SncRedisBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new FOS\RestBundle\FOSRestBundle(),
            new Bazinga\Bundle\HateoasBundle\BazingaHateoasBundle(),
            new ONGR\ElasticsearchBundle\ONGRElasticsearchBundle(),
            new Nelmio\ApiDocBundle\NelmioApiDocBundle(),
            new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
            new OldSound\RabbitMqBundle\OldSoundRabbitMqBundle(),
            new AppBundle\AppBundle(),
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function getCacheDir()
    {
        return dirname(__DIR__).'/var/cache/'.$this->getEnvironment();
    }

    public function getLogDir()
    {
        return dirname(__DIR__).'/var/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir() . '/config/config.yml');

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $loader->load(function ($container) {
                $container->loadFromExtension('web_profiler', [
                    'toolbar' => true,
                    'intercept_redirects' => false
                ]);

                $container->loadFromExtension('monolog', [
                    'handlers' => [
                        'main' => [
                            'type' => 'stream',
                            'path' => '%kernel.logs_dir%/%kernel.environment%.log',
                            'level' => 'debug',
                            'channels' => ['!event']
                        ],
                        'console' => [
                            'type' => 'console',
                            'channels' => ['!event', '!doctrine']
                        ],
                        'firephp' => [
                            'type' => 'firephp',
                            'level' => 'info',
                        ],
                        'chromephp' => [
                            'type' => 'chromephp',
                        ]
                    ]
                ]);
            });
        }
    }
}
