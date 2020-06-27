<?php

namespace App\Http\Controllers;

use App\Http\Resources\PermissionCollection;
use App\Services\PermissionService;

/**
 * Class PermissionController
 * @package App\Http\Controllers
 */
class PermissionController extends Controller
{
    private $service;

    /**
     * PermissionController constructor.
     * @param PermissionService $service
     */
    public function __construct(PermissionService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    /**
     * Gets the list of permissions.
     *
     * @return array
     */
    public function index(): array
    {
        return $this->respond(function () {
            return PermissionCollection::make($this->service->all());
        });
    }
}
