<?php

namespace App\Repository;

use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Game>
 *
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    /**
     * Trouve un jeu par clé primaire composite.
     *
     * @param int       $playerId L'identifiant du joueur.
     * @param int       $teamId   L'identifiant de l'équipe.
     * @param int       $quizId   L'identifiant du quiz.
     * @param \DateTime $gameDate La date du jeu.
     *
     * @return Game|null L'objet Game correspondant, ou null si aucun n'a été trouvé.
     */
    public function findGameByCompositeKey($playerId, $teamId, $quizId, $gameDate): ?Game
    {
        $query = $this->entityManager->createQuery(
            'SELECT g FROM App\Entity\Game g 
             WHERE g.playerId = :playerId 
             AND g.teamId = :teamId 
             AND g.quizId = :quizId 
             AND g.gameDate = :gameDate'
        );

        $query->setParameter('playerId', $playerId);
        $query->setParameter('teamId', $teamId);
        $query->setParameter('quizId', $quizId);
        $query->setParameter('gameDate', $gameDate);

        return $query->getResult();
    }

    //    /**
    //     * @return Game[] Returns an array of Game objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('q')
    //            ->andWhere('q.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('q.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Game
    //    {
    //        return $this->createQueryBuilder('q')
    //            ->andWhere('q.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
