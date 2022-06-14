<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return function(ContainerConfigurator $configurator) {
    $configurator->import("parameters.php");
    $configurator->import("config.yaml");
};