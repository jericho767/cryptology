<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

/**
 * Class Controller
 * @package App\Http\Controllers
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Logged in user
     *
     * @var Player
     */
    protected $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();

            return $next($request);
        });
    }

    /**
     * Execute logic and wrapped response with code.
     *
     * @param callable $logic
     * @return array
     */
    protected function respond(callable $logic): array
    {
        try {
            // Execute logic and wrap the logic's response in the data index
            return [
                'code' => 200,
                'data' => $logic(),
            ];
        } catch (Exception $exception) {
            // Error occurred.
            return [
                'code' => 500,
                'error' => $exception->getMessage(),
            ];
        }
    }
}
