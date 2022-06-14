<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

/***********************************************************************************************************************
 * This is an example file for all PartKeepr-related configuration options.
 *
 * PartKeepr sets all relevant Symfony2 configurations via this file. If you require more flexibility for your setup,
 * create an app/config/config_custom.yml file which will be parsed if it exists.
 *
 **********************************************************************************************************************/

//======================================================================================================================
// Database Settings
//======================================================================================================================

return static function (ContainerConfigurator $container) {
    $parameters = $container->parameters();

    /**
     * Specifies the database driver. Available options are listed on this page:
     * http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html#driver
     */
    $parameters->set('database_driver', 'pdo_mysql');

    /**
     * Specifies the hostname for the database
     */
    $parameters->set('database_host', 'localhost');

    /**
     * Specifies the database port.
     */
    $parameters->set('database_port', '3306');

    /**
     * Specifies the database name
     */
    $parameters->set('database_name', 'partkeepr_dev');

    /**
     * Specifies the username for the database
     */
    $parameters->set('database_user', 'partkeepr_dev');

    /**
     * Specifies the password for the database
     */
    $parameters->set('database_password', "1234");

    /**
     * Specifies the database server version
     */
    $parameters->set('database_version', '10.6');

    //======================================================================================================================
    // Mailer Settings
    // Currently not used, the defaults are fine
    //======================================================================================================================

    // The mailer transport. Can be smtp, mail, sendmail or gmail
    $parameters->set('mailer_transport', null);

    // The mail server host name or IP
    $parameters->set('mailer_host', null);

    // The mail server port to use
    $parameters->set('mailer_port', null);

    // The encryption method to use. Can be ssl, tls or null for unencrypted mail transport
    $parameters->set('mailer_encryption', null);

    // The mail server username
    $parameters->set('mailer_user', null);

    // The mail server password
    $parameters->set('mailer_password', null);

    // The mail server auth mode. Can be plain, login or cram-md5
    $parameters->set('mailer_auth_mode', null);

    //======================================================================================================================
    // Misc framework settings
    //======================================================================================================================

    // The locale to use. Currently only en is supported
    $parameters->set('locale', 'en');

    // The secret. See http://symfony.com/doc/current/reference/configuration/framework.html#secret
    $parameters->set('secret', 'COAAFJGGLPHPDGGNCNILHFGECFMMACKC');

    //======================================================================================================================
    // LDAP Configuration
    // Example for Active Directory:
    // https://github.com/Maks3w/FR3DLdapBundle/blob/master/Resources/doc/cookbook/active-directory.md
    //======================================================================================================================

    // The LDAP Server Host
    $parameters->set('fr3d_ldap.driver.host', '127.0.0.1');

    // The LDAP Sever Port
    $parameters->set('fr3d_ldap.driver.port', null);

    // The username to use for LDAP queries
    $parameters->set('fr3d_ldap.driver.username', null);

    // The password to use for LDAP queries
    $parameters->set('fr3d_ldap.driver.password', null);

    // true to require a DN for binding attemts, false otherwise
    $parameters->set('fr3d_ldap.driver.bindRequiresDn', false);

    // The base DN to query for users
    $parameters->set('fr3d_ldap.driver.baseDn', '');

    // sprintf format %s will be the username
    $parameters->set('fr3d_ldap.driver.accountFilterFormat', null);

    $parameters->set('fr3d_ldap.driver.optReferrals', null);

    // true to use SSL, false otherwise
    $parameters->set('fr3d_ldap.driver.useSsl', null);

    // true to use startTls, false otherwise
    $parameters->set('fr3d_ldap.driver.useStartTls', null);

    // currently not used
    $parameters->set('fr3d_ldap.driver.accountCanonicalForm', null);

    $parameters->set('fr3d_ldap.driver.accountDomainName', null);
    $parameters->set('fr3d_ldap.driver.accountDomainNameShort', null);

    // set to true to enable LDAP
    $parameters->set('fr3d_ldap.user.enabled', false);

    // sets the base DN
    $parameters->set('fr3d_ldap.user.baseDn', 'dc=blabla,dc=com');

    // The filter to use for queries
    $parameters->set('fr3d_ldap.user.filter', null);

    // The username attribute
    $parameters->set('fr3d_ldap.user.attribute.username', "samaccountname");

    // The email attribute
    $parameters->set('fr3d_ldap.user.attribute.email', "email");


    //======================================================================================================================
    // PartKeepr settings
    //======================================================================================================================

    // The authentication provider to use. Can be either PartKeepr.Auth.WSSEAuthenticationProvider or
    // PartKeepr.Auth.HTTPBasicAuthenticationProvider
    $parameters->set('authentication_provider', 'PartKeepr.Auth.WSSEAuthenticationProvider');

    /**
     * Specifies if the frontend should perform an auto-login
     */
    $parameters->set('partkeepr.frontend.auto_login.enabled', false);

    /**
     * Specifies the auto-login username
     */
    $parameters->set('partkeepr.frontend.auto_login.username', 'admin');

    /**
     * Specifies the auto-login password
     */
    $parameters->set('partkeepr.frontend.auto_login.password', 'admin');

    /**
     * Specifies the base_url for the PartKeepr frontend.
     * Usually this is not required, but if you run PartKeepr behind a reverse
     * proxy with a different path, you can set it here.
     *
     * Please note that you need to create an additional file, see
     * https://wiki.partkeepr.org/wiki/KB00008:PartKeepr_behind_a_reverse_proxy
     *
     * Example: If PartKeepr runs on / within a docker container and your reverse
     * proxy maps it on https://www.example.org/parts, you can set the
     * base_url to https://www.example.org/parts
     */
    $parameters->set('partkeepr.frontend.base_url', false);

    /**
     * Specifies the category path separator
     */
    $parameters->set('partkeepr.category.path_separator', ' ➤ ');

    /**
     * Specifies a message of the day. Set to any string instead of false
     * to display a MOTD. Example
     * $parameters->set('partkeepr.frontend.motd', "This is a MOTD");
     */
    $parameters->set('partkeepr.frontend.motd', false);

    /**
     * Specifies if a quota should be used.
     * If set to false, no quota is used. Set it to a numeric value to set a quota in bytes.
     */
    $parameters->set('partkeepr.filesystem.quota', false);

    /**
     * Specifies the dunglas cache. Defaults to false.
     * You can use the APCu cache by specifying "api.mapping.cache.apc" here.
     *
     * Further reading: https://api-platform.com/doc/1.0/api-bundle/performances
     */
    $parameters->set('cache.dunglas', false);

    /**
     * Specifies the doctrine cache. Defaults to "array", but can be "apc".
     *
     * Further reading: http://symfony.com/doc/current/bundles/DoctrineBundle/configuration.html#caching-drivers
     *
     * For complex caching scenarios, you are advised to use a custom configuration file as described at the top of this
     * file.
     */
    $parameters->set('cache.doctrine', 'array');

    /**
     * Defines if a maintenance page should be displayed.
     */
    $parameters->set('partkeepr.maintenance', false);

    /**
     * Defines if a maintenance page should be displayed. Set to false to prevent a maintenance page being
     * displayed, or to a string which should occur on the maintenance page.
     */
    $parameters->set('partkeepr.maintenance.title', '');

    /**
     * Defines if a maintenance page should be displayed. Set to false to prevent a maintenance page being
     * displayed, or to a string which should occur on the maintenance page.
     */
    $parameters->set('partkeepr.maintenance.message', '');

    /**
     * Defines a limit for the maximum amount of users allowed. Valid values are false (no limit) or an integer number
     */
    $parameters->set('partkeepr.users.limit', false);

    /**
     * Defines a limit for the maximum amount of parts allowed. Valid values are false (no limit) or an integer number
     */
    $parameters->set('partkeepr.parts.limit', false);

    /**
     * Defines if the internal part number must be unique or not. Note that empty internal part numbers aren't checked -
     * if you require to enforce an internal part number, also set the field internalPartNumber to mandatory.
     *
     * Defaults to false
     */
    $parameters->set('partkeepr.parts.internalpartnumberunique', false);

    /**
     * Defines a limit for the maximum amount of parts allowed. Valid values are false (no limit) or an integer number
     */
    $parameters->set('partkeepr.upload.limit', false);

    /**
     * Specifies the PartKeepr data directory
     */
    $parameters->set('partkeepr.filesystem.data_directory', '%kernel.project_dir%/data/');

    /**
     * Specifies if PartKeepr should check for non-running cronjobs
     */
    $parameters->set('partkeepr.cronjob.check', true);

    /**
     * Specifies which v4 API key PartKeepr should use to talk to OctoPart. You can get an API key by registering at
     * https://octopart.com/api and then registering an application.
     */
    $parameters->set('partkeepr.octopart.apikey', '');

    /**
     * The number of returned parts from API calls is limited. Try to keep this value low
     */
    $parameters->set('partkeepr.octopart.limit', '3');

    /**
     * Specifies which URL contains the patreon status. If you do not wish to display the patreon status,
     * set this parameter to false. Please note that we rely on your Patreon pledges to ensure further
     * development of PartKeepr.
     */
    $parameters->set('partkeepr.patreon.statusuri', 'https://www.partkeepr.org/patreon.json');
};
