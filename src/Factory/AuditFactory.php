<?php

namespace App\Factory;

use App\Entity\Audit;
use App\Factory\AuditeurFactory;
use App\Factory\SiteFactory;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Audit>
 */
final class AuditFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    public static function class(): string
    {
        return Audit::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'date_heure_audit' => self::faker()->dateTime(),
            'score_conformite' => self::faker()->randomFloat(2, 50, 100),
            'zone' => self::faker()->word(),
            'auditeur' => AuditeurFactory::random(),
            'site' => SiteFactory::random(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Audit $audit): void {})
        ;
    }
}
