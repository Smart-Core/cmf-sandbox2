<?php

namespace DemoSiteBundle\Entity\Catalog;

use Doctrine\ORM\Mapping as ORM;
use SmartCore\Bundle\UnicatBundle\Model\CategoryModel;

/**
 * @ORM\Entity
 * @ORM\Table(name="catalog_categories",
 *      indexes={
 *          @ORM\Index(columns={"is_enabled"}),
 *          @ORM\Index(columns={"position"})
 *      },
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(columns={"slug", "parent_id", "structure_id"}),
 *      }
 * )
 */
class Category extends CategoryModel
{
}