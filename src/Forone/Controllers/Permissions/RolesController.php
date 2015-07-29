<?php
/**
 * User : YuGang Yang
 * Date : 6/10/15
 * Time : 17:47
 * Email: smartydroid@gmail.com
 */

namespace Forone\Admin\Controllers\Permissions;


use Artesaos\Defender\Facades\Defender;
use Artesaos\Defender\Permission;
use Artesaos\Defender\Role;
use Forone\Admin\Controllers\BaseController;
use Forone\Admin\Requests\CreateRoleRequest;
use Forone\Admin\Requests\UpdateRoleRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * 角色
 * Class RolesController
 * @package App\Http\Controllers
 */
class RolesController extends BaseController {

    const URI = 'roles';
    const NAME = '角色';

    function __construct()
    {
        parent::__construct();
        view()->share('page_name', self::NAME);
        view()->share('uri', self::URI);
    }

    public function index()
    {
        $results = [
            'columns' => [
                ['编号', 'id'],
                ['名称', 'name'],
                ['创建时间', 'created_at'],
                ['更新时间', 'updated_at'],
                ['操作', 'buttons', function ($data) {
                    $buttons = [];
                    if ($data->name != config('defender.superuser_role', 'superuser')) {
                        $buttons = [
                            ['编辑'],
                        ];
                        array_push($buttons, ['分配权限', '#modal']);
                    }
                    return $buttons;
                }]
            ]
        ];

        $paginate = Role::with('permissions')->paginate();
        $results['items'] = $paginate;

        // 获取顶层权限
        $perms = Permission::all();

        return $this->view('forone::' . self::URI.'.index', compact('results', 'perms'));
    }

    /**
     *
     * @return View
     */
    public function create()
    {
        return $this->view('forone::roles.create');
    }

    /**
     *
     * @param CreateRoleRequest $request
     * @return View
     */
    public function store(CreateRoleRequest $request)
    {
        Role::create($request->only(['name']));
        return redirect()->route('admin.roles.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $data = Role::findOrFail($id);
        if ($data) {
            return $this->view('forone::' . self::URI."/edit", compact('data'));
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
    public function update($id, UpdateRoleRequest $request)
    {
        $name = $request->get('name');
        $count = Role::whereName($name)->where('id', '!=', $id)->count();
        if ($count > 0) {
            return $this->redirectWithError('角色名称不能重复');
        }
        $data = $request->only('name');
        Role::findOrFail($id)->update($data);
        return redirect()->route('admin.roles.index');
    }

    /**
     * 分配权限
     */
    public function assignPermission(Request $request)
    {
        $role = Defender::findRoleById($request->get('id'));
        $permissionNameParams = $request->except(['_token', 'id']);
        $permissionNames = array_keys($permissionNameParams);
        $permissions = [];
        foreach ($permissionNames as $permissionName) {
            $permissions[] = str_replace('_', '.', $permissionName);
        }
        // 删除 roles 所有权限
        $role->revokePermissions();
        $permissions = Permission::whereIn('name', $permissions)->get();
        $permissions->each(function($per) use ($role) {
            $role->attachPermission($per);
        });

        return redirect()->route('admin.roles.index');
    }

}