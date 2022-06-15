<?php

namespace DoctrineMigrations;

use App\BaseMigration;
use Doctrine\DBAL\Schema\Schema;
use App\Entity\UserProvider;

/**
 * Attaches the builtin user provider to all existing users.
 */
class Version20151001180120 extends BaseMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->performDatabaseUpgrade();

        $userProviderRepository = $this->getEM()->getRepository(
            'App:UserProvider'
        );

        $builtinProvider = $userProviderRepository->findOneBy(['type' => 'Builtin']);

        if ($builtinProvider === null) {
            $builtinProvider = new UserProvider();
            $builtinProvider->setType('Builtin');

            $this->getEM()->persist($builtinProvider);
        }

        $repository = $this->getEM()->getRepository(
            'App:User'
        );

        $users = $repository->findAll();

        foreach ($users as $user) {
            if ($user->getProvider() === null) {
                $user->setProvider($builtinProvider);
            }

            $user->setLegacy(true);
        }

        $this->getEM()->flush();
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
