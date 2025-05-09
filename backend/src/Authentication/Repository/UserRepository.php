<?php

declare(strict_types=1);

namespace SymfonyVueBoilerplateBackend\Authentication\Repository;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use SymfonyVueBoilerplateBackend\API\ViewModel\UserViewModel;
use SymfonyVueBoilerplateBackend\Authentication\Entity\User;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @implements PasswordUpgraderInterface<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    /**
     * @return array<UserViewModel>
     */
    public function getAllAsViewModels(): array
    {
        return array_map(
            static fn (User $user) => new UserViewModel(
                $user->uuid,
                $user->username,
                $user->displayName,
            ),
            $this->findAll(),
        );
    }

    public function getAsViewModel(string $id): UserViewModel|null
    {
        $user = $this->find($id);

        if ($user === null) {
            return null;
        }

        return new UserViewModel(
            $user->uuid,
            $user->username,
            $user->displayName,
        );
    }
}