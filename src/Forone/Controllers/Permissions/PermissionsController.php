<?php
/**
 * User : YuGang Yang
 * Date : 6/10/15
 * Time : 18:49
 * Email: smartydroid@gmail.com
 */

namespace Forone\Admin\Controllers\Permissions;

use App\Http\Requests\CreatePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use Artesaos\Defender\Facades\Defender;
use Forone\Admin\Controllers\BaseController;

class PermissionsController extends BaseController {

    const URI = 'permissions';
    const NAME = '权限';

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
                ['权限名', 'name'],
                ['权限显示名称', 'readable_name'],
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
        $paginate = Permission::paginate();
        $results['items'] = $paginate;

        return $this->view(self::URI.'.index', compact('results'));
    }

    /**
     *
     * @return View
     */
    public function create()
    {
        return $this->view('permissions.create');
    }

    /**
     *
     * @param CreateRoleRequest $request
     * @return View
     */
    public function store(CreatePermissionRequest $request)
    {
        Permission::create($request->only(['name', 'readable_name', 'is_nav']));
        return redirect()->route('admin.permissions.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $data = Defender::findPermissionById($id);
        if ($data) {
            return view(self::URI."/edit", compact('data'));
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
        $data = $request->only(['name', 'readable_name', 'is_nav']);
        Permission::findOrFail($id)->update($data);
        return redirect()->route('admin.permissions.index');
    }

}