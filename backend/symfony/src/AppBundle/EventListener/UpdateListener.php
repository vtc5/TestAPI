<?php
/**
 * Created by PhpStorm.
 * User: vtc
 * Date: 01.01.2019
 * Time: 16:57
 */

namespace AppBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;

class UpdateListener
{
  public function preUpdate(LifecycleEventArgs $args)
  {
    $entity = $args->getEntity();
    if (is_object($entity)) {
      $entityManager = $args->getEntityManager();
      $unitOfWork = $entityManager->getUnitOfWork();
      $changeSet = $unitOfWork->getEntityChangeSet($entity);
      if (!empty($changeSet)) {
        $changed = false;
        if (method_exists($entity,'setUpdated')) {
          $entity->setUpdated(new \DateTime('now'));
          $changed = true;
        }
        if ($changed) {
          $meta = $entityManager->getClassMetadata(get_class($entity));
          $unitOfWork->recomputeSingleEntityChangeSet($meta, $entity);
        }
      }
    }
  }
}