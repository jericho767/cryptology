<?php

namespace App\Http\Requests;

use App\Models\Permission;
use App\Models\Player;
use App\Models\Role as RoleModel;
use Illuminate\Validation\Rule;

/**
 * Class Role
 * @package App\Http\Requests
 */
class Role extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        switch ($this->route()->getName()) {
            case 'roles.index':
                return $this->getListRules();
            case 'roles.renew':
                return $this->getRenewRules();
            case 'roles.create':
                return $this->getCreateRules();
            case 'roles.update':
                return $this->getCreateRules($this->getRouteRole());
            default:
                return [];
        }
    }

    /**
     * Gets the role from the route parameter.
     *
     * @return RoleModel
     */
    public function getRouteRole(): RoleModel
    {
        /** @var RoleModel $role */
        $role = $this->route('role');

        return $role;
    }

    /**
     * Gets the rules applied in creating a role.
     *
     * @param RoleModel|null $exempt
     * @return array
     */
    private function getCreateRules(RoleModel $exempt = null): array
    {
        // The role that will be exempt in the unique checking
        if ($exempt !== null) {
            $unique = 'unique:' . (new RoleModel())->getTable() . ',name,' . $exempt->getAttribute('id');
        } else {
            $unique = 'unique:' . (new RoleModel())->getTable() . ',name';
        }

        return [
            'name' => [
                'required',
                'min: ' . RoleModel::NAME_MIN_LENGTH,
                'max: ' . RoleModel::NAME_MAX_LENGTH,
                $unique,
            ],
            'permissions' => [
                'array',
                'required',
            ],
            'permissions.*' => [
                Rule::in(Permission::ALL),
                'distinct',
            ],
        ];
    }

    /**
     * Gets the permissions parameter.
     *
     * @return array
     */
    public function getPermissions(): array
    {
        return $this->get('permissions');
    }

    /**
     * Gets the name parameter.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->get('name');
    }

    /**
     * Gets the rules applied for renew roles route.
     *
     * @return array
     */
    private function getRenewRules(): array
    {
        return [
            'roles' => [
                'array',
                'required',
            ],
            'roles.*' => [
                'integer',
                $this->ruleExists((new RoleModel())->getTable(), null),
            ]
        ];
    }

    /**
     * Gets the filters.
     *
     * @return array
     */
    public function getFilters(): array
    {
        $filters = $this->get('filter');

        return [
            RoleModel::FILTER_BY['name'] => isset($filters[RoleModel::FILTER_BY['name']]) ?
                $filters[RoleModel::FILTER_BY['name']] : null,
            RoleModel::FILTER_BY['created_at'] => [
                'start' => isset($filters[RoleModel::FILTER_BY['created_at']]['start']) ?
                    $this->toDate($filters[RoleModel::FILTER_BY['created_at']]['start'], true) : null,
                'end' => isset($filters[RoleModel::FILTER_BY['created_at']]['end']) ?
                    $this->toDate($filters[RoleModel::FILTER_BY['created_at']]['end'], false) : null,
            ],
        ];
    }

    /**
     * Gets the rules for the list route.
     *
     * @return array
     */
    private function getListRules(): array
    {
        return [
            'filter' => [
                'array',
            ],
            'filter.*' => [
                Rule::in(RoleModel::FILTER_BY),
            ],
            'filter.' . RoleModel::FILTER_BY['name'] => [
                'min:1',
            ],
            'filter.' . RoleModel::FILTER_BY['created_at'] => [
                'array',
            ],
            'filter.' . RoleModel::FILTER_BY['created_at'] . '.start' => [
                'date_format:' . $this->dateFormat,
            ],
            'filter.' . RoleModel::FILTER_BY['created_at'] . '.end' => [
                'date_format:' . $this->dateFormat,
            ],
        ] + $this->getBaseListRules(RoleModel::SORT_BY);
    }

    /**
     * Gets the roles.
     *
     * @return array
     */
    public function getRoles(): array
    {
        return array_map('intval', $this->get('roles'));
    }

    /**
     * Gets the player whose roles are altered.
     *
     * @return Player
     */
    public function getPlayer(): Player
    {
        /** @var Player $player */
        $player = $this->route('player');

        return $player;
    }

    /**
     * Interceptor after validations are passed.
     */
    protected function passedValidation(): void
    {
        $this->canAddSuperAdminRole();
    }

    /**
     * Checks if the logged in user can add a super-admin role.
     */
    private function canAddSuperAdminRole()
    {
        /** @var Player */
        $loggedInUser = $this->user();

        // Logged in user is not a super admin
        if (!$loggedInUser->hasRole(RoleModel::SUPER_ADMIN)) {
            // Non-super admin users cannot add super-admin roles
            $this->validate([
                'roles.*' => [
                    Rule::notIn([
                        RoleModel::SUPER_ADMIN_ID,
                    ]),
                ],
            ]);
        }
    }
}
