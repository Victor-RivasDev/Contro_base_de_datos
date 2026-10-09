<?php

namespace App\Factory;

use App\Entity\User;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
/**
 * @extends PersistentProxyObjectFactory<User>
 */
final class UserFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    private $hasher;
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        parent::__construct();
        $this->hasher = $hasher;
    }
    

    public static function class(): string
    {
        return User::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'email' => self::faker()->email(),
            'roles' => ['ROLE_USER'],
            'password' => '123456789',
            'name' => self::faker()->name(),
            
            
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this 
            ->afterInstantiate(function(User $user): void {
                $user->setPassword(
                    $this->hasher->hashPassword($user, $user->getPassword()));
            })
        
            
        ;
    }
}
