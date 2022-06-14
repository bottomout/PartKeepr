<?php

namespace App\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Routing;
use Symfony\Component\Intl\Currencies;
use App\Service\SystemService;

class DefaultController extends AbstractFOSRestController
{
    /**
     * Returns system status.
     *
     * @Routing\Route("/api/system_status", defaults={"method" = "get","_format" = "json"})
     * @View()
     *
     * @return array
     */
    public function getSystemStatusAction(SystemService $system)
    {
        return $system->getSystemStatus();
    }

    /**
     * Returns system information.
     *
     * @Routing\Route("/api/system_information", defaults={"method" = "get","_format" = "json"})
     * @View()
     *
     * @return array
     */
    public function getSystemInformationAction(SystemService $system)
    {
        return $system->getSystemInformation();
    }

    /**
     * Returns available disk space.
     *
     * @Routing\Route("/api/disk_space", defaults={"method" = "get","_format" = "json"})
     * @View()
     *
     * @return array
     */
    public function getDiskFreeSpaceAction(SystemService $system)
    {
        return [
            'disk_total' => $system->getTotalDiskSpace(),
            'disk_used'  => $system->getUsedDiskSpace(),
        ];
    }

    /**
     * Returns the available currencies.
     *
     * @Routing\Route("/api/currencies", defaults={"method" = "get","_format" = "json"})
     * @View()
     */
    public function getCurrenciesAction()
    {
        $currencyData = Currencies::getNames();

        $currencies = [];
        foreach ($currencyData as $code => $name) {
            $currencies[] = [
                "code"   => $code,
                "name"   => $name,
                "symbol" => Currencies::getSymbol($code),
            ];
        }

        return $currencies;
    }
}
