<?php
/**
 * User : YuGang Yang
 * Date : 6/16/15
 * Time : 10:19
 * Email: smartydroid@gmail.com
 */

namespace Forone\Admin\Controllers\Permissions;

use App\Http\Requests\CreateAdminRequest;
use Artesaos\Defender\Facades\Defender;
use Artesaos\Defender\Role;
use Forone\Admin\Controllers\BaseController;
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
//                    if ($data->enabled) {
//                        array_push($buttons, ['禁用']);
//                    } else {
//                        array_push($buttons, ['启用']);
//                    }
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

        return $this->view(self::URI.'.index', compact('results', 'roles'));
    }

    public function create()
    {
        return $this->view(self::URI.'.create');
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
            return view(self::URI."/show", compact('data'));
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
            return view(self::URI."/edit", compact('data'));
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
    public function update($id, CreateAdminRequest $request)
    {
        Admin::findOrFail($id)->update($request->only(['name', 'password', 'email']));
        return redirect()->route('admin.admins.index');
    }

    /**
     * 分配角色
     */
    public function assignRole(Request $request)
    {
        $admin = Admin::findOrFail($request->get('id'));
        $role = Defender::findRoleById($request->get('role_id'));
        $admin->attachRole($role);
        return redirect()->route('admin.admins.index');
    }

}