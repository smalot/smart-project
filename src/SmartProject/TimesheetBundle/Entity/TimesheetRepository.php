<?php

namespace SmartProject\TimesheetBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * TimesheetRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TimesheetRepository extends EntityRepository
{
    /**
     * @param UserInterface $user
     * @param \DateTime     $date
     * @param boolean       $create
     *
     * @return mixed|null
     */
    public function findByUser(UserInterface $user, \DateTime $date, $create = false)
    {
        $entities = $this->createQueryBuilder('t')
          ->where('t.user = :user')
          ->andWhere('t.dateStart <= :date')
          ->andWhere('t.dateEnd >= :date')
          ->setParameter(':user', $user)
          ->setParameter(':date', $date->format('Y-m-d'))
          ->getQuery()
          ->execute();

        if ($entities) {
            return current($entities);
        } else {
            if ($create) {
                return $this->createForUser($user, $date);
            } else {
                return null;
            }
        }
    }

    /**
     * @param UserInterface $user
     * @param \DateTime     $date
     *
     * @return Timesheet
     */
    public function createForUser(UserInterface $user, \DateTime $date)
    {
        $startDate = clone $date;
        $day       = $startDate->format('w');
        if ($day == 0) {
            $interval = new \DateInterval('P6D');
        } else {
            $interval = new \DateInterval('P' . ($day - 1) . 'D');
        }
        $startDate->sub($interval);
        $endDate = clone $startDate;
        $endDate = $endDate->add(new \DateInterval('P6D'));

        $timesheet = new Timesheet();
        $timesheet->setUser($user);
        $timesheet->setDateStart($startDate);
        $timesheet->setDateEnd($endDate);
        $timesheet->setStatus(Timesheet::STATUS_NEW);

        return $timesheet;
    }
}
