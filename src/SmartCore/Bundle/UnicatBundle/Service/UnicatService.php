<?php

namespace SmartCore\Bundle\UnicatBundle\Service;

use Doctrine\ORM\EntityManager;
use SmartCore\Bundle\UnicatBundle\Entity\UnicatRepository;
use SmartCore\Bundle\UnicatBundle\Entity\UnicatStructure;
use SmartCore\Bundle\UnicatBundle\Form\Type\CategoryFormType;
use SmartCore\Bundle\UnicatBundle\Form\Type\ItemFormType;
use SmartCore\Bundle\UnicatBundle\Form\Type\PropertyFormType;
use SmartCore\Bundle\UnicatBundle\Model\CategoryModel;
use SmartCore\Bundle\UnicatBundle\Model\ItemModel;
use SmartCore\Bundle\UnicatBundle\Model\PropertyModel;
use Symfony\Component\Form\FormFactoryInterface;

class UnicatService
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @param EntityManager $em
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(EntityManager $em, FormFactoryInterface $formFactory)
    {
        $this->em = $em;
        $this->formFactory = $formFactory;
    }

    /**
     * @param UnicatRepository $repository
     * @param mixed $data    The initial data for the form
     * @param array $options Options for the form
     *
     * @return \Symfony\Component\Form\Form
     */
    public function getCategoryForm(UnicatRepository $repository, $data = null, array $options = [])
    {
        return $this->formFactory->create(new CategoryFormType($repository), $data, $options);
    }

    /**
     * @param UnicatStructure $structure
     * @param array $options Options for the form
     *
     * @return \Symfony\Component\Form\Form
     */
    public function getCategoryCreateForm(UnicatStructure $structure, array $options = [])
    {
        $category = $structure->getRepository()->createCategory();
        $category->setStructure($structure);

        return $this->getCategoryForm($structure->getRepository(), $category, $options)
            ->add('create', 'submit', ['attr' => [ 'class' => 'btn btn-success' ]]);
    }

    /**
     * @param UnicatRepository $repository
     * @param array $options Options for the form
     *
     * @return \Symfony\Component\Form\Form
     */
    public function getCategoryEditForm(CategoryModel $category, array $options = [])
    {
        return $this->getCategoryForm($category->getStructure()->getRepository(), $category, $options)
            ->add('update', 'submit', ['attr' => [ 'class' => 'btn btn-success' ]])
            ->add('cancel', 'submit', ['attr' => [ 'class' => 'btn' ]]);
    }

    /**
     * @param UnicatRepository $repository
     * @param mixed $data    The initial data for the form
     * @param array $options Options for the form
     *
     * @return \Symfony\Component\Form\Form
     */
    public function getPropertyForm(UnicatRepository $repository, $data = null, array $options = [])
    {
        return $this->formFactory->create(new PropertyFormType($repository), $data, $options);
    }

    /**
     * @param UnicatRepository $repository
     * @param mixed $data    The initial data for the form
     * @param array $options Options for the form
     *
     * @return \Symfony\Component\Form\Form
     */
    public function getItemForm(UnicatRepository $repository, $data = null, array $options = [])
    {
        return $this->formFactory->create(new ItemFormType($repository), $data, $options);
    }

    /**
     * @param UnicatRepository $repository
     * @param mixed $data    The initial data for the form
     * @param array $options Options for the form
     *
     * @return \Symfony\Component\Form\Form
     */
    public function getItemCreateForm(UnicatRepository $repository, $data = null, array $options = [])
    {
        return $this->getItemForm($repository, $data, $options)
            ->add('create', 'submit', ['attr' => [ 'class' => 'btn btn-success' ]]);
    }

    /**
     * @param UnicatRepository $repository
     * @param mixed $data    The initial data for the form
     * @param array $options Options for the form
     *
     * @return \Symfony\Component\Form\Form
     */
    public function getItemEditForm(UnicatRepository $repository, $data = null, array $options = [])
    {
        return $this->getItemForm($repository, $data, $options)
            ->add('update', 'submit', ['attr' => [ 'class' => 'btn btn-success' ]])
            ->add('cancel', 'submit', ['attr' => [ 'class' => 'btn' ]]);
    }

    /**
     * @param UnicatRepository $repository
     * @param array $options Options for the form
     *
     * @return \Symfony\Component\Form\Form
     */
    public function getPropertyCreateForm(UnicatRepository $repository, $groupId, array $options = [])
    {
        $property = $repository->createProperty();
        $property
            ->setGroup($this->em->getRepository($repository->getPropertyGroupClass())->find($groupId))
            //@todo ->setUserId()
        ;

        return $this->getPropertyForm($repository, $property, $options)
            ->add('create', 'submit', ['attr' => [ 'class' => 'btn btn-success' ]]);
    }

    /**
     * @param UnicatRepository $repository
     * @param array $options Options for the form
     *
     * @return \Symfony\Component\Form\Form
     */
    public function getPropertyEditForm(UnicatRepository $repository, $property, array $options = [])
    {
        return $this->getPropertyForm($repository, $property, $options)
            ->add('update', 'submit', ['attr' => [ 'class' => 'btn btn-success' ]])
            ->add('cancel', 'submit', ['attr' => [ 'class' => 'btn' ]]);
    }

    /**
     * @param UnicatStructure $structure
     * @param int $id
     * @return CategoryModel|null
     */
    public function getCategory(UnicatStructure $structure, $id)
    {
        return $this->em->getRepository($structure->getRepository()->getCategoryClass())->find($id);
    }

    /**
     * @param UnicatRepository $repository
     * @param int $id
     * @return ItemModel|null
     */
    public function getItem(UnicatRepository $repository, $id)
    {
        return $this->em->getRepository($repository->getItemClass())->find($id);
    }

    /**
     * @param UnicatRepository $repository
     * @param int $groupId
     * @return PropertyModel[]
     */
    public function getProperties(UnicatRepository $repository, $groupId = null)
    {
        $filter = ($groupId) ? ['group' => $groupId] : [];

        return $this->em->getRepository($repository->getPropertyClass())->findBy($filter, ['position' => 'ASC']);
    }
    
    /**
     * @param UnicatRepository $repository
     * @param int $groupId
     * @return PropertyModel[]
     */
    public function getPropertiesGroup(UnicatRepository $repository, $groupId)
    {
        return $this->em->getRepository($repository->getPropertyGroupClass())->find($groupId);
    }

    /**
     * @param UnicatRepository $repository
     * @param int $groupId
     * @return PropertyModel[]
     */
    public function getProperty(UnicatRepository $repository, $id)
    {
        return $this->em->getRepository($repository->getPropertyClass())->find($id);
    }

    /**
     * @param int|string $val
     * @return UnicatRepository
     */
    public function getRepository($val)
    {
        $key = intval($val) ? 'id' : 'name';

        return $this->em->getRepository('UnicatBundle:UnicatRepository')->findOneBy([$key => $val]);
    }
    
    /**
     * @param int $id
     * @return UnicatStructure
     */
    public function getStructure($id)
    {
        return $this->em->getRepository('UnicatBundle:UnicatStructure')->find($id);
    }

    /**
     * @param CategoryModel $category
     * @return $this
     */
    public function createCategory(CategoryModel $category)
    {
        $this->em->persist($category);
        $this->em->flush($category);

        return $this;
    }

    /**
     * @param ItemModel $item
     * @param UnicatRepository $repository
     * @param array $structures
     * @return $this
     */
    public function createItem(ItemModel $item, UnicatRepository $repository, $structures)
    {
        //@todo $structuresColection = $this->em->getRepository($repository->getCategoryClass())->findIn($structures);

        $list_string = '';
        foreach ($structures as $node_id) {
            $list_string .= $node_id . ',';
        }

        $list_string = substr($list_string, 0, strlen($list_string)-1);

        if (false == $list_string) {
            return [];
        }

        $structuresColection = $this->em->createQuery("
            SELECT c
            FROM {$repository->getCategoryClass()} c
            WHERE c.id IN({$list_string})
        ")->getResult();

        $item->setCategories($structuresColection);

        $this->em->persist($item);
        $this->em->flush($item);

        return $this;
    }

    /**
     * @param ItemModel $item
     * @param UnicatRepository $repository
     * @param array $structures
     * @return $this
     */
    public function updateItem(ItemModel $item, UnicatRepository $repository, $structures)
    {
        return $this->createItem($item, $repository, $structures);
    }

    /**
     * @param PropertyModel $property
     * @return $this
     */
    public function createProperty(PropertyModel $property)
    {
        $this->em->persist($property);
        $this->em->flush($property);

        return $this;
    }

    /**
     * @param CategoryModel $category
     * @return $this
     */
    public function updateCategory(CategoryModel $category)
    {
        $this->em->persist($category);
        $this->em->flush($category);

        return $this;
    }

    /**
     * @param PropertyModel $property
     * @return $this
     */
    public function updateProperty(PropertyModel $property)
    {
        $this->em->persist($property);
        $this->em->flush($property);

        return $this;
    }

    /**
     * @param CategoryModel $category
     * @return $this
     */
    public function deleteCategory(CategoryModel $category)
    {
        throw new \Exception('@todo решить что сделать с вложенными категориями, а также с сопряженными записями');

        $this->em->remove($category);
        $this->em->flush($category);

        return $this;
    }

    /**
     * @param PropertyModel $property
     * @return $this
     */
    public function deleteProperty(PropertyModel $property)
    {
        throw new \Exception('@todo надо решить как поступать с данными записей');

        $this->em->remove($property);
        $this->em->flush($property);

        return $this;
    }
}
