<?php

namespace App\Services\Inverters;

use InvalidArgumentException;

class InverterManager
{
    private $config;
    private $inverters = [];

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function connect(string $name): InverterInterface
    {
        if (!isset($this->config['connections'][$name])) {
            throw new InvalidArgumentException("Configuration d'onduleur inconnue: {$name}");
        }

        if (!isset($this->inverters[$name])) {
            $this->inverters[$name] = $this->createInverter($name);
        }

        if (!$this->inverters[$name]->isConnected()) {
            $this->inverters[$name]->connect();
        }

        return $this->inverters[$name];
    }

    private function createInverter(string $name): InverterInterface
    {
        $config = $this->config['connections'][$name];
        
        switch ($name) {
            case 'sungrow':
                return new SungrowInverter($config);
            case 'huawei':
                return new HuaweiInverter($config);
            case 'sma':
                return new SMAInverter($config);
            case 'fronius':
                return new FroniusInverter($config);
            case 'schneider':
                return new SchneiderInverter($config);
            case 'abb':
                return new ABBInverter($config);
            case 'delta':
                return new DeltaInverter($config);
            case 'goodwe':
                return new GoodweInverter($config);
            default:
                throw new InvalidArgumentException("Type d'onduleur non supporté: {$name}");
        }
    }

    public function getDefaultConnection(): string
    {
        return $this->config['default'] ?? 'sungrow';
    }

    public function supportedInverters(): array
    {
        return array_keys($this->config['connections']);
    }
}
