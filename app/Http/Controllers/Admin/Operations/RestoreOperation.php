<?php

namespace App\Http\Controllers\Admin\Operations;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

trait RestoreOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupRestoreRoutes($segment, $routeName, $controller)
    {
        Route::post($segment.'/{id}/restore', [
            'as'        => $routeName.'.restore',
            'uses'      => $controller.'@restore',
            'operation' => 'restore',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupRestoreDefaults()
    {
        $this->crud->allowAccess('restore');

        $this->crud->operation('restore', function () {
            $this->crud->loadDefaultOperationSettingsFromConfig();
        });

        $this->crud->operation('list', function () {
            $this->crud->addButton('line', 'restore', 'view', 'crud::buttons.restore');
        });
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param Illuminate\Http\Request $request
     * @param int $id
     *
     * @return Response
     */
    public function restore(Request $request, $id)
    {
        $this->crud->hasAccessOrFail('delete');
        $this->crud->model->withTrashed()->find($id)->restore();
        return response()->json('ok');
    }
}
