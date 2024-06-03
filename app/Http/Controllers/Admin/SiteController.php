<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Services\SiteService;
use App\Traits\ApiRequestTrait;
use App\Http\Requests\SiteRequest;
use App\Http\Resources\SiteResource;
use Illuminate\Http\Response;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;


class SiteController extends AdminController
{
    use ApiRequestTrait;
    public SiteService $siteService;
    protected $apiRequest;

    public function __construct(SiteService $siteService)
    {
        parent::__construct();
        $this->siteService = $siteService;
        $this->apiRequest  = $this->makeApiRequest();
        $this->middleware(['permission:settings'])->only('update');
    }

    public function index(): SiteResource | \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new SiteResource($this->siteService->list());
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
    public function update(SiteRequest $request): SiteResource | Response | Application | ResponseFactory
    {
        try {
            if ($this->apiRequest->status) {
                return new SiteResource($this->siteService->update($request));
            }
            return response(['status' => false, 'message' => $this->apiRequest->message], 422);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
