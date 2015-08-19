<?php
/**
 * User : YuGang Yang
 * Date : 6/10/15
 * Time : 18:49
 * Email: smartydroid@gmail.com
 */

namespace Forone\Admin\Controllers\Permissions;

use Forone\Admin\Controllers\BaseController;
use Forone\Admin\Permission;
use Forone\Admin\Requests\CreatePermissionRequest;
use Forone\Admin\Requests\UpdatePermissionRequest;

class PermissionsController extends BaseController {

    function __construct()
    {
        parent::__construct('permissions', '权限');
        $this->middleware('admin.permission', ['testsdf']);
    }

    public function index()
    {
        $results = [
            'columns' => [
                ['编号', 'id'],
                ['系统名称', 'name'],
                ['显示名称', 'display_name'],
                ['创建时间', 'created_at'],
                ['更新时间', 'updated_at'],
                ['操作', 'buttons', function ($data) {
                    $buttons = [
                        ['编辑'],
                    ];
                    return $buttons;
                }]
            ]
        ];
        $paginate = Permission::orderBy('id','desc')->paginate();
        $results['items'] = $paginate;

        return $this->view('forone::' . $this->uri.'.index', compact('results'));
    }

    /**
     *
     * @return View
     */
    public function create()
    {
        return $this->view('forone::'.$this->uri.'.create');
    }

    /**
     *
     * @param CreateRoleRequest $request
     * @return View
     */
    public function store(CreatePermissionRequest $request)
    {
        Permission::create($request->except('id', '_token'));
        return $this->toIndex('保存成功');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $data = Permission::find($id);
        if ($data) {
            return view('forone::' . $this->uri."/edit", compact('data'));
        } else {
            return $this->redirectWithError('数据未找到');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, UpdatePermissionRequest $request)
    {
        $data = $request->except('id', '_token');
        Permission::findOrFail($id)->update($data);

        return $this->toIndex();
    }

}