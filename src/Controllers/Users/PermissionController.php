<?php

namespace agungsugiarto\boilerplate\Controllers\Users;

use agungsugiarto\boilerplate\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

/**
 * Class PermissionController.
 */
class PermissionController extends BaseController
{
    use ResponseTrait;

    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return View | \CodeIgniter\API\ResponseTrait
     */
    public function index()
    {
        // Title and breadcrump
        $data = [
            'title'    => lang('permission.title'),
            'subtitle' => lang('permission.subtitle'),
        ];

        if ($this->request->isAJAX()) {
            return $this->respond([
                'data' => $this->authorize->permissions(),
            ]);
        }

        return view('agungsugiarto\boilerplate\Views\Permission\index', $data);
    }

    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return \CodeIgniter\API\ResponseTrait
     */
    public function new()
    {
        //
    }

    /**
     * Return the properties of a resource object.
     *
     * @return \CodeIgniter\API\ResponseTrait
     */
    public function show()
    {
        //
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return \CodeIgniter\API\ResponseTrait
     */
    public function create()
    {
        $validationRules = [
            'name'        => 'required|min_length[5]|max_length[255]|is_unique[auth_permissions.name]',
            'description' => 'required|max_length[255]',
        ];

        $name = $this->request->getPost('name');
        $description = $this->request->getPost('description');

        if (!$this->validate($validationRules)) {
            return $this->fail(
                $this->validator->getErrors()
            );
        }

        return $this->respondCreated(
            $this->authorize->createPermission(url_title($name), $description),
            lang('permission.msg_insert')
        );
    }

    /**
     * Return the editable properties of a resource object.
     *
     * @param int id
     *
     * @return \CodeIgniter\API\ResponseTrait
     */
    public function edit($id)
    {
        if (!$found = $this->authorize->permission($id)) {
            return $this->failNotFound(lang('permission.msg_get_fail'));
        }

        return $this->respond([
            'data' => $found,
        ]);
    }

    /**
     * Add or update a model resource, from "posted" properties.
     *
     * @param int id
     *
     * @return \CodeIgniter\API\ResponseTrait
     */
    public function update($id)
    {
        $validationRules = [
            'name'        => "required|max_length[255]|is_unique[auth_permissions.name,id,$id]",
            'description' => 'required|max_length[255]',
        ];

        $data = $this->request->getRawInput();

        if (!$this->validate($validationRules)) {
            return $this->fail(
                $this->validator->getErrors()
            );
        }

        return $this->respondCreated(
            $this->authorize->updatePermission($id, $data['name'], $data['description']),
            lang('permission.msg_update')
        );
    }

    /**
     * Delete the designated resource object from the model.
     *
     * @return \CodeIgniter\API\ResponseTrait
     */
    public function delete($id)
    {
        if ($found = $this->authorize->deletePermission($id)) {
            return $this->respondDeleted($found, lang('permission.msg_delete'));
        }

        return $this->failNotFound(lang('permission.msg_get_fail'));
    }
}
