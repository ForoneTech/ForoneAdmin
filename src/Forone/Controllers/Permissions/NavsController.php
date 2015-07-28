<?php
/**
 * User : YuGang Yang
 * Date : 6/16/15
 * Time : 13:26
 * Email: smartydroid@gmail.com
 */

namespace Forone\Admin\Controllers\Permissions;


use App\Http\Requests\CreateNavRequest;
use Artesaos\Defender\Permission;
use Forone\Admin\Controllers\BaseController;
use Illuminate\Http\Request;

class NavsController extends BaseController {

    const URI = 'navs';
    const NAME = '导航';

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
                ['名称', 'display_name'],
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

        $paginate = Nav::paginate();
        $results['items'] = $paginate;

        return $this->view(self::URI.'.index', compact('results'));
    }

    /**
     *
     * @return View
     */
    public function create()
    {
        return $this->view(self::URI . '.create', $this->getData());
    }

    private function getData()
    {
        $permissions = Permission::whereIsNav(true)->get();
        $perms = [];
        foreach ($permissions as $perm) {
            array_push($perms, [
                'label' => $perm->readable_name,
                'value' => $perm->id
            ]);
        }

        $navigators = Nav::whereParentId(null)->get();
        $navs = [];
        array_push($navs, [
            'label' => '无',
            'value' => 0
        ]);
        foreach ($navigators as $nav) {
            array_push($navs, [
                'label' => $nav->display_name,
                'value' => $nav->id
            ]);
        }

        return compact('perms', 'navs');
    }

    /**
     *
     * @param CreateRoleRequest $request
     * @return View
     */
    public function store(CreateNavRequest $request)
    {
        $data = $request->only(['display_name', 'sort', 'permission_id', 'is_redirect']);
        $parentId = $request->get('parent_id');
        if ($parentId) {
            $data['parent_id'] = $parentId;
        }
        Nav::create($data);
        return redirect()->route('admin.' . self::URI . '.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $data = Nav::findOrFail($id);
        if ($data) {
            return view(self::URI."/edit", array_merge($this->getData(), compact('data')));
        } else {
            return $this->redirectWithError('数据未找到');
        }
    }

    public function show($id)
    {
        $data = Nav::find($id);
        if ($data) {
            return view(self::URI."/show", array_merge($this->getData(), compact('data')));
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
    public function update($id, Request $request)
    {

        $data = $request->all();
        $return_url = '';
        switch ($data['_method']) {
            case 'PATCH':
                $this->validate($request,['id'=>'required']);
                $return_url = 'admin/navs';
                $id = $data['id'];
                break;
            case 'PUT':
                $this->validate($request,$this->rules);
                $return_url = 'admin/navs/'.$id;
                break;
        }
        $input = array_except($data, ['_method','_token','id', 'parent_id']);
        Nav::findOrFail($id)->update($input);
        return redirect()->to($return_url);
    }

}