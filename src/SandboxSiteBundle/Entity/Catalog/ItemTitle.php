<?php

namespace SandboxSiteBundle\Entity\Catalog;

use Doctrine\ORM\Mapping as ORM;
use SmartCore\Module\Unicat\Model\TypeStringModel;

/**
 * @ORM\Entity
 * @ORM\Table(name="catalog_items_title",
 *      indexes={
 *          @ORM\Index(columns={"value"})
 *      }
 * )
 */
class ItemTitle extends TypeStringModel
{
}
