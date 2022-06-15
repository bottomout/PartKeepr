<?php

namespace App\Controller;

use App\Kernel;
use App\Service\GridPresetService;
use App\Service\SystemService;
use Doctrine\ORM\Version as ORMVersion;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class IndexController extends AbstractController
{
    /**
     * @var ContainerBagInterface
     */
    protected $parameterBag;

    /**
     * @var SystemService
     */
    protected $system;

    /**
     * @var GridPresetService
     */
    protected $gridPreset;

    /**
     * @var Kernel
     */
    protected $kernel;

    public function __construct(ContainerBagInterface $parameterBag, SystemService $system, GridPresetService $gridPreset, Kernel $kernel)
    {
        $this->parameterBag = $parameterBag;
        $this->system = $system;
        $this->gridPreset = $gridPreset;
        $this->kernel = $kernel;
    }

    /**
     * This is basically a copy of the PartKeepr's legacy index.php.
     *
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('index.html.twig', $this->getRenderParameters());
    }

    public function getRenderParameters()
    {
        if ($this->getParameter('partkeepr.maintenance') !== false) {
            $renderParams['maintenanceTitle'] = $this->getParameter('partkeepr.maintenance.title');
            $renderParams['maintenanceMessage'] = $this->getParameter('partkeepr.maintenance.message');

            return $this->render('maintenance.html.twig', $renderParams);
        }

        $aParameters = [];
        $aParameters['doctrine_orm_version'] = ORMVersion::VERSION;
        // TODO $aParameters['doctrine_dbal_version'] = DBALVersion::VERSION;
        // TODO $aParameters['doctrine_common_version'] = DoctrineCommonVersion::VERSION;
        $aParameters['php_version'] = phpversion();
        $aParameters['auto_start_session'] = true;

        $maxPostSize = $this->system->getBytesFromHumanReadable(ini_get('post_max_size'));
        $maxFileSize = $this->system->getBytesFromHumanReadable(ini_get('upload_max_filesize'));

        $aParameters['maxUploadSize'] = min($maxPostSize, $maxFileSize);

        if ($this->getParameterWithDefault('partkeepr.upload.limit', false) !== false) {
            $aParameters['maxUploadSize'] = $this->getParameterWithDefault('partkeepr.upload.limit', false);
        }

        if ($this->getParameterWithDefault('partkeepr.octopart.apikey', "") !== "") {
            $aParameters['isOctoPartAvailable'] = true;
        } else {
            $aParameters['isOctoPartAvailable'] = false;
        }

        // @todo Hardcoded for now due to GD, see #445
        $aParameters['availableImageFormats'] = ['JPG', 'GIF', 'PNG'];

        /* Automatic Login */
        if ($this->getParameterWithDefault('partkeepr.frontend.auto_login.enabled', false) === true) {
            $aParameters['autoLoginUsername'] = $this->getParameter('partkeepr.frontend.auto_login.username');
            $aParameters['autoLoginPassword'] = $this->getParameter('partkeepr.frontend.auto_login.password');
        }

        if ($this->getParameterWithDefault('partkeepr.frontend.motd', false) !== false) {
            $aParameters['motd'] = $this->getParameterWithDefault('partkeepr.frontend.motd', false);
        }

        $aParameters['max_users'] = $this->getParameterWithDefault('partkeepr.auth.max_users', 'unlimited');

        $aParameters['authentication_provider'] = $this->getParameter('partkeepr.authentication_provider');
        $aParameters['tip_of_the_day_uri'] = $this->getParameter('partkeepr.tip_of_the_day_uri');

        $aParameters['password_change'] = $this->getParameterWithDefault('partkeepr.auth.allow_password_change', true);
        $aParameters["patreonStatus"] = $this->system->getPatreonStatus();

        $aParameters["defaultGridPresets"] = json_encode($this->gridPreset->getDefaultPresets());
        $renderParams = [];
        $renderParams['parameters'] = $aParameters;
        $renderParams['debug'] = $this->kernel->isDebug();
        $renderParams['baseUrl'] = $this->getBaseURL();

        return $renderParams;
    }

    /**
     * Returns the base_url, either from the router (default) or overridden by the
     * partkeepr.frontend.base_url parameter.
     *
     * @return string
     */
    public function getBaseURL()
    {
        $baseUrl = $this->getParameterWithDefault('partkeepr.frontend.base_url', false);

        if ($baseUrl !== false) {
            return $baseUrl;
        }

        return $this->container->get('router')->getContext()->getBaseUrl();
    }

    public function getParameterWithDefault($name, $default)
    {
        if ($this->parameterBag->has($name)) {
            return $this->parameterBag->get($name);
        } else {
            return $default;
        }
    }
}
