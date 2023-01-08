<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Project\CreateProjectRequest;
use App\Http\Requests\Api\Project\ImportProjectRequest;
use App\Http\Requests\Api\Project\UpdateProjectNameRequest;
use App\Http\Requests\Api\Project\UpdateProjectSchemaRequest;
use App\Http\Resources\Api\Project\ProjectListResource;
use App\Http\Resources\Api\Project\ProjectResource;
use App\Models\Project;
use App\Models\User;
use App\UseCases\Project\Commands\ImportProjectCommand;
use App\UseCases\Project\Handlers\ImportProjectHandler;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index(): JsonResource
    {
        /** @var User $user */
        $user = Auth::user();

        return ProjectListResource::collection($user->projects);
    }

    public function store(CreateProjectRequest $request): JsonResource
    {
        /** @var User $user */
        $user = Auth::user();

        /** @var Project $project */
        $project = $user->projects()->create($request->validated());

        return new ProjectResource($project);
    }

    public function import(ImportProjectRequest $request, ImportProjectHandler $importProjectHandler): JsonResource
    {
        $command = new ImportProjectCommand(
            $request->user(),
            $request->get('name'),
            $request->file('files'),
        );

        $project = $importProjectHandler($command);

        return new ProjectResource($project);
    }

    public function show(Project $project): JsonResource
    {
        return new ProjectResource($project);
    }

    public function updateName(Project $project, UpdateProjectNameRequest $request): JsonResource
    {
        $project->update([
            'name' => $request->get('name'),
        ]);

        return new ProjectResource($project);
    }

    public function updateSchema(Project $project, UpdateProjectSchemaRequest $request): JsonResource
    {
        $project->update([
            'schema' => $request->get('schema'),
        ]);

        return new ProjectResource($project);
    }

    public function delete(Project $project): Response
    {
        $project->delete();

        return response(null);
    }
}
