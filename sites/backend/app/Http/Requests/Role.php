<?php

namespace App\Http\Requests;

use App\Models\Player;
use App\Models\Role as Roles;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role as RoleModel;

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
            default:
                return [];
        }
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
            Roles::FILTER_BY['name'] => isset($filters[Roles::FILTER_BY['name']]) ?
                $filters[Roles::FILTER_BY['name']] : null,
            Roles::FILTER_BY['created_at'] => [
                'start' => isset($filters[Roles::FILTER_BY['created_at']]['start']) ?
                    $this->toDate($filters[Roles::FILTER_BY['created_at']]['start'], true) : null,
                'end' => isset($filters[Roles::FILTER_BY['created_at']]['end']) ?
                    $this->toDate($filters[Roles::FILTER_BY['created_at']]['end'], false) : null,
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
                Rule::in(Roles::FILTER_BY),
            ],
            'filter.' . Roles::FILTER_BY['name'] => [
                'min:1',
            ],
            'filter.' . Roles::FILTER_BY['created_at'] => [
                'array',
            ],
            'filter.' . Roles::FILTER_BY['created_at'] . '.start' => [
                'date_format:' . $this->dateFormat,
            ],
            'filter.' . Roles::FILTER_BY['created_at'] . '.end' => [
                'date_format:' . $this->dateFormat,
            ],
        ] + $this->getBaseListRules(Roles::SORT_BY);
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
        if (!$loggedInUser->hasRole(Roles::SUPER_ADMIN)) {
            // Non-super admin users cannot add super-admin roles
            $this->validate([
                'roles.*' => [
                    Rule::notIn([
                        Roles::SUPER_ADMIN_ID,
                    ]),
                ],
            ]);
        }
    }
}
