<?php
/**
 * User : YuGang Yang
 * Date : 6/16/15
 * Time : 10:19
 * Email: smartydroid@gmail.com
 */

namespace Forone\Admin\Controllers\Permissions;

use Artesaos\Defender\Facades\Defender;
use Artesaos\Defender\Role;
use Forone\Admin\Admin;
use Forone\Admin\Controllers\BaseController;
use Forone\Admin\Requests\CreateAdminRequest;
use Forone\Admin\Requests\UpdateAdminRequest;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Http\Request;

class AdminsController extends BaseController {

    const URI = 'admins';
    const NAME = '管理员';

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
                ['名称', 'name'],
                ['邮箱', 'email'],
                ['创建时间', 'created_at'],
                ['操作', 'buttons', function ($data) {
                    $buttons = [
                        ['编辑']
                    ];
                    if (!$data->hasRole(config('defender.superuser_role', 'superuser'))) {
                        array_push($buttons, ['分配角色', '#modal']);
                    }
                    return $buttons;
                }]
            ]
        ];
        $collection = Role::all();
        $roles = [];
        foreach ($collection as $role) {
            array_push($roles, [
                'label' => $role->name,
                'value' => $role->id,
            ]);
        }
        $paginate = Admin::with('roles')->orderBy('created_at', 'desc')->paginate();
        $results['items'] = $paginate;

        return $this->view('forone::' . self::URI.'.index', compact('results', 'roles'));
    }

    public function create()
    {
        return $this->view('forone::' . self::URI.'.create');
    }

    /**
     * @param CreateAdminRequest $request
     * @return RedirectResponse|Redirector
     */
    public function store(CreateAdminRequest $request, Registrar $registrar)
    {

        $registrar->create($request->only(['name', 'password', 'email']));
        return redirect()->route('admin.admins.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $data = Admin::findOrFail($id);
        if ($data) {
            return $this->view('forone::' . self::URI. "/show", compact('data'));
        }else{
            return $this->redirectWithError('数据未找到');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $data = Admin::findOrFail($id);
        if ($data) {
            return $this->view('forone::' . self::URI. "/edit", compact('data'));
        }else{
            return $this->redirectWithError('数据未找到');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, UpdateAdminRequest $request)
    {
        $name = $request->get('name');
        $email = $request->get('email');
        $count = Admin::whereName($name)->where('id', '!=', $id)->count();
        if ($count > 0) {
            return $this->redirectWithError('名称不能重复');
        }
        $count = Admin::whereEmail($email)->where('id', '!=', $id)->count();
        if ($count > 0) {
            return $this->redirectWithError('邮箱不能重复');
        }
        Admin::findOrFail($id)->update($request->only(['name', 'email']));
        return redirect()->route('admin.admins.index');
    }

    /**
     * 分配角色
     */
    public function assignRole(Request $request)
    {
        $admin = Admin::findOrFail($request->get('id'));

        // detach roles
        $admin->roles()->detach();

        $role = Defender::findRoleById($request->get('role_id'));
        $admin->attachRole($role);
        return redirect()->route('admin.admins.index');
    }

}