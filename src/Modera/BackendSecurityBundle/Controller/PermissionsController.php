<?php

namespace Modera\BackendSecurityBundle\Controller;

use Sli\ExpanderBundle\Ext\ContributorInterface;
use Modera\ServerCrudBundle\Controller\AbstractCrudController;
use Modera\BackendSecurityBundle\ModeraBackendSecurityBundle;
use Modera\SecurityBundle\Model\PermissionCategoryInterface;
use Modera\SecurityBundle\Model\PermissionInterface;
use Modera\SecurityBundle\Entity\PermissionCategory;
use Modera\SecurityBundle\Entity\Permission;

/**
 * @author    Sergei Vizel <sergei.vizel@modera.org>
 * @copyright 2014 Modera Foundation
 */
class PermissionsController extends AbstractCrudController
{
    /**
     * @return array
     */
    public function getConfig()
    {
        return array(
            'entity' => Permission::clazz(),
            'security' => array(
                'role' => ModeraBackendSecurityBundle::ROLE_ACCESS_BACKEND_TOOLS_SECURITY_SECTION,
                'actions' => array(
                    'create' => ModeraBackendSecurityBundle::ROLE_MANAGE_PERMISSIONS,
                    'remove' => ModeraBackendSecurityBundle::ROLE_MANAGE_PERMISSIONS,
                    'update' => ModeraBackendSecurityBundle::ROLE_MANAGE_PERMISSIONS,
                    'batchUpdate' => ModeraBackendSecurityBundle::ROLE_MANAGE_PERMISSIONS,
                ),
            ),
            'hydration' => array(
                'groups' => array(
                    'list' => function (Permission $permission) {
                        $users = array();
                        foreach ($permission->getUsers() as $user) {
                            $users[] = $user->getId();
                        }

                        $groups = array();
                        foreach ($permission->getGroups() as $group) {
                            $groups[] = $group->getId();
                        }

                        return array(
                            'id' => $permission->getId(),
                            'name' => $this->getPermissionName($permission),
                            'category' => array(
                                'id' => $permission->getCategory()->getId(),
                                'name' => $this->getPermissionCategoryName($permission->getCategory()),
                            ),
                            'users' => $users,
                            'groups' => $groups,
                        );
                    },
                ),
                'profiles' => array(
                    'list',
                ),
            ),
        );
    }

    /**
     * @param PermissionCategory $entity
     * @return string
     */
    private function getPermissionCategoryName(PermissionCategory $entity)
    {
        /* @var PermissionCategoryInterface[] $permissionCategories */
        $permissionCategories = $this->getPermissionCategoriesProvider()->getItems();
        foreach ($permissionCategories as $permissionCategory) {
            if ($permissionCategory->getTechnicalName() === $entity->getTechnicalName()) {
                return $permissionCategory->getName();
            }
        }
        return $entity->getName();
    }

    /**
     * @param Permission $entity
     * @return string
     */
    private function getPermissionName(Permission $entity)
    {
        /* @var PermissionInterface[] $permissions */
        $permissions = $this->getPermissionsProvider()->getItems();
        foreach ($permissions as $permission) {
            if ($permission->getRole() === $entity->getRole()) {
                return $permission->getName();
            }
        }
        return $entity->getName();
    }

    /**
     * @return ContributorInterface
     */
    private function getPermissionCategoriesProvider()
    {
        return $this->get('modera_security.permission_categories_provider');
    }

    /**
     * @return ContributorInterface
     */
    private function getPermissionsProvider()
    {
        return $this->get('modera_security.permissions_provider');
    }
}
