@extends('forone::layouts.master')

@section('main')

     {!! Html::list_header([
     'new'=>true,
     ]) !!}

     {!! Html::datagrid($results) !!}

     {!! Html::modal_start('modal','编辑类型配置') !!}
     {!! Form::open(['method'=>'POST','url'=>'admin/admins/assign-role','id'=>'form_id']) !!}
     {!! Form::hidden_input('id') !!}
     {!! Form::form_select('role_id', '角色名称', $roles) !!}
     {!! Form::close() !!}
     {!! Html::modal_end() !!}

@stop

@section('js')
    @parent
    <script type="text/javascript">

        var datas = [];
        var data = '';

        function fillModal(id) {
            data = datas[id];
            data = JSON.parse(data);
            var roles = data['roles'];
            var roleId = -1;
            if (roles.length != 0) {
                roleId = roles[0]['id'];
            }
            $('select option').filter(function () {
                return $(this).val() == roleId;
            }).prop('selected', true);

            $('#form_id :input').each(function () {
                var name = $(this).attr('name');
                if (data[name]) {
                    $(this).val(data[name]);
                }
            });
        }

        $(document).on('confirmation', '#modal', function () {
            $('#form_id').submit();
        });
    </script>
@endsection