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
