<?php

namespace Forone\Admin\Providers;

use Form;
use Html;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class ForoneHtmlServiceProvider extends ServiceProvider{

    public function boot()
    {
        $this->extendRules();
        $this->extendForm();
        $this->extendHtml();
    }

    public function register()
    {
    }

    public static function parseValue($model, $name)
    {
        $arr = explode('.', $name);
        if (sizeof($arr) == 2) {
            return $model && (!is_array($model) ||  array_key_exists($arr[0], $model)) ? $model[$arr[0]][$arr[1]] : '';
        }else{
            return $model && (!is_array($model) ||  array_key_exists($name, $model)) ? $model[$name] : '';
        }
    }

    private function extendRules()
    {
        Validator::extend('mobile', function ($attribute, $value, $parameters) {
            return preg_match("/^1[0-9]{2}[0-9]{8}$|15[0189]{1}[0-9]{8}$|189[0-9]{8}$/",$value);
        });
        Validator::extend('code', function ($attribute, $value, $parameters) {
            return strlen($value) != 6 || !preg_match("/[0-9]{6}/", $value);
        });
    }

    private function extendForm()
    {
        Form::macro('parse', function ($array) {
            $fields = ['placeholder', 'percent', 'modal', 'label_col'];
            $arr = [];
            foreach ($fields as $field) {
                if (array_key_exists($field, $array)) {
                    $arr[ $field ] = $array[ $field ];
                } else {
                    $arr[ $field ] = '';
                }
            }

            return $arr;
        });
        Form::macro('group_label', function ($name, $label) {
            $value = ForoneHtmlServiceProvider::parseValue($this->model, $name);
            return '<div class="control-group">
                        <label for="title" class="control-label">' . $label . '</label>
                        <div class="controls">
                            <label for="title" class="control-label">' . $value . '</label>
                        </div>
                    </div>';
        });
        Form::macro('hidden_input', function ($name, $value='') {
            return '<input type="hidden" value="'.$value.'" name="'.$name.'" id="'.$name.'">';
        });
        Form::macro('group_text', function ($name,$label,$placeholder='',$percent=0.5, $modal=false) {
            $value = ForoneHtmlServiceProvider::parseValue($this->model, $name);
            $data = '';
            $input_col = 9;
            if (is_array($placeholder)) {
                $data = Form::parse($placeholder);
                $placeholder = $data['placeholder'];
                $percent = $data['percent'] ? $data['percent'] : 0.5;
                $modal = $data['modal'] ? true : false;
                $input_col = $data['label_col'] ? 12 - $data['label_col'] : 9;
            }
            $style = $modal ? 'style="padding:0px"':'';
            return '<div class="form-group col-sm-'.($percent*12).'" '.$style.'>
                        '.Form::form_label($label, $data).'
                        <div class="col-sm-'.$input_col.'">
                            <input name="' . $name . '" type="text" value="'.$value.'" class="form-control" placeholder="'.$placeholder.'">
                          </div>
                    </div>';
        });
        Form::macro('group_password', function ($name, $label, $placeholder='',$percent=0.5, $modal=false) {
            $data = '';
            $input_col = 9;
            if (is_array($placeholder)) {
                $data = Form::parse($placeholder);
                $placeholder = $data['placeholder'];
                $percent = $data['percent'] ? $data['percent'] : 0.5;
                $modal = $data['modal'] ? true : false;
                $input_col = $data['label_col'] ? 12 - $data['label_col'] : 9;
            }
            $style = $modal ? 'style="padding:0px"':'';
            return '<div class="form-group col-sm-'.($percent*12).'" '.$style.'>
                        '.Form::form_label($label, $data).'
                        <div class="col-sm-'.$input_col.'">
                            <input name="' . $name . '" type="password" class="form-control" placeholder="'.$placeholder.'">
                          </div>
                    </div>';
        });
        Form::macro('group_area', function ($name,$label,$placeholder='',$percent=0.5) {
            $value = $this->model && (!is_array($this->model) || array_key_exists($name, $this->model)) ? $this->model[$name] : '';
            $data = '';
            $input_col = 9;
            $modal = false;
            if (is_array($placeholder)) {
                $data = Form::parse($placeholder);
                $placeholder = $data['placeholder'];
                $percent = $data['percent'] ? $data['percent'] : 0.5;
                $modal = $data['modal'] ? true : false;
                $input_col = $data['label_col'] ? 12 - $data['label_col'] : 9;
            }
            $style = $modal ? 'style="padding:0px"':'';
            return '<div class="form-group col-sm-' . ($percent * 12) . '" '.$style.'>
                        ' . Form::form_label($label, $data) . '
                        <div class="col-sm-'.$input_col.'">
                            <textarea id="'.$name.'" name="' . $name . '" rows="6" class="form-control" placeholder="'.$placeholder.'">'.$value.'</textarea>
                        </div>
                    </div>';
        });
        Form::macro('group_radio', function ($name, $label, $data,$percent=1) {
            $result = '<div class="form-group col-sm-'.($percent*12).'">
                        '.Form::form_label($label).'
                        <div class="col-sm-9">';
            foreach ($data as $item) {
                if($this->model){
                    $checked = $this->model[$name] == $item[0] ? 'checked=true' : '';;
                }else{
                    $checked = sizeof($item) == 3 ? 'checked='.$item[2] : '';
                }
                $result.='<input '.$checked.'" name="'.$name.'" type="radio" value="'.$item[0].'">
                            <span style="vertical-align: middle;padding-right:10px">'.$item[1].'</span>';
            }
            return $result.'</div></div>';
        });
        Form::macro('group_checkbox', function ($name, $label, $data, $percent=1) {
            $result = '<div class="form-group col-sm-'.($percent*12).'">
                        '.Form::form_label($label).'
                        <div class="col-sm-9">';
            foreach ($data as $item) {
                if($this->model){
                    $checked = $this->model[$name] == $item[0] ? 'checked=true' : '';;
                }else{
                    $checked = sizeof($item) == 3 ? 'checked='.$item[2] : '';
                }
                $result.='<label class="checkbox-inline">';
                $result.='<input '.$checked.'" name="'.$name.'" type="checkbox" value="'.$item[0].'">
                            <span style="vertical-align: middle;padding-right:10px">'.$item[1].'</span>';
                $result.='</label>';
            }
            return $result.'</div></div>';
        });
        Form::macro('form_action', function ($label) {
            return '<div class="form-group col-sm-12">
                        <button class="btn btn-fw btn-primary" type="submit">'.$label.'</button>
                    </div>';
        });
        Form::macro('modal_button', function ($label, $modal, $data, $class='waves-effect') {
            $test = json_encode($data);
            $html = '<a href="'.$modal.'" style="margin-left:5px;"><button onclick="fillModal(\''.$data->id.'\')" class="btn ' . $class . '" >' . $label . '</button></a>';
            $js = "<script>init.push(function(){datas['".$data->id."']='".$test."';})</script>";
            return $html.$js;
        });
        Form::macro('form_button', function ($config, $data) {
            if (!array_key_exists('alert', $config)) {
                $config['alert'] = '确认吗？';
            }
            if (!array_key_exists('uri', $config)) {
                $config['uri'] = 'update';
            }
            if (!array_key_exists('class', $config)) {
                $config['class'] = 'btn-default';
            }
            if (!array_key_exists('method', $config)) {
                $config['method'] = 'POST';
            }

            if ($config['method'] == 'POST') {
                $dataInputs = '';
                foreach ($data as $key => $value) {
                    $dataInputs .= '<input type="hidden" name="'.$key.'" value="'.$value.'">';
                }
                $result = '<form style="float: left;margin-right: 5px;" action="' . $this->url->current() . '/' . $config['uri'] . '" method="POST">
                 <input type="hidden" name="id" value="' . $config['id'] . '">
                 <input type="hidden" name="_method" value="PATCH">
                 ' . $dataInputs . '
                 ' . Form::token() . '
                 <button type="submit" class="btn '.$config['class'].'" onclick="return confirm(\''.$config['alert'].'\')" >'.$config['name'].'</button>
                 </form>';
            }else{
                $result = '<a href="'.$this->url->current().'/'.$config['uri'].'"><button type="submit" class="btn ' . $config['class'] . '">' . $config['name'] . '</button></a>';
            }

            return $result;
        });
        Form::macro('form_label', function ($label,$modal=false) {
            $col = 3;
            if (is_array($modal)) {
                $col = $modal['label_col'] ? $modal['label_col'] : 3;
                $modal = $modal['modal'];
            }
            $style = $modal ? 'style="padding: 7px 0px;"' : '';
            return '<label class="col-sm-'.$col.' control-label" '.$style.'>' . $label . '</label>';
        });
        Form::macro('form_select', function ($name, $label, $data, $percent=0.5) {
            $result = '<div class="form-group col-sm-'.($percent*12).'">
                        '.Form::form_label($label).'
                        <div class="col-sm-9"><select class="form-control" name="' . $name . '">';
            foreach ($data as $item) {
                $value = is_array($item) ? $item['value'] : $item;
                $label = is_array($item) ? $item['label'] : $item;
                $selected = '';
                if ($this->model) {
                    $selected = $this->model[ $name ] == $value ? 'selected="selected"' : '';;
                } else if(is_array($item)){
                    $selected = sizeof($item) == 3 ? 'selected=' . $item[2] : '';
                }
                $result .= '<option ' . $selected . ' value="' . $value . '">' . $label . '</option>';
            }

            return $result . '</select></div></div>';
        });
        Form::macro('form_multi_select', function ($name, $label, $data, $percent=0.5) {
            $result = '<div class="form-group col-lg-'.($percent*12).'">
                        '.Form::form_label($label).'
                        <div class="col-lg-9"><select multiple class="form-control chzn-select" name="' . $name . '[]">';
            foreach ($data as $item) {
                $value = is_array($item) ? $item['value'] : $item;
                $label = is_array($item) ? $item['label'] : $item;
                $selected = '';
                if ($this->model) {
                    if(isset($this->model[ $name ])){
                        $type_ids = explode(',',$this->model[ $name ]);
                    }else{
                        $type_ids = [];
                    }
                    $result .= '<option ' . (in_array($value, $type_ids) ? 'selected' : '') . ' value="' . $value . '">' . $label . '</option>';
                }
                else if(is_array($item)){
                    $result .= '<option ' . $selected . ' value="' . $value . '">' . $label . '</option>';
                }

            }


            return $result . '</select></div></div>';
        });
        Form::macro('form_date', function ($name,$label,$placeholder='',$percent=0.5) {
            $value = ForoneHtmlServiceProvider::parseValue($this->model, $name);
            if (!is_string($placeholder)) {
                $percent = $placeholder;
            }
            $result = '<div class="form-group col-sm-' . ($percent * 12) . '">
                        ' . Form::form_label($label) . '
                        <div class="col-sm-9">'.
                '<input id="'.$name.'date" name="' . $name . '" type="text" value="'.$value.'" class="form-control" placeholder="'.$placeholder.'">';
            $js = "<script>init.push(function(){jQuery('#".$name."date').datetimepicker({format:'Y-m-d'});})</script>";
            return $result.'</div></div>'.$js;
        });
        Form::macro('form_time', function ($name,$label,$placeholder='',$percent=0.5) {
            $value = ForoneHtmlServiceProvider::parseValue($this->model, $name);
            if (!is_string($placeholder)) {
                $percent = $placeholder;
            }
            $result = '<div class="form-group col-sm-' . ($percent * 12) . '">
                        ' . Form::form_label($label) . '
                        <div class="col-sm-9">'.
                '<input id="'.$name.'date" name="' . $name . '" type="text" value="'.$value.'" class="form-control" placeholder="'.$placeholder.'">';
            $js = "<script>init.push(function(){jQuery('#".$name."date').datetimepicker({format:'Y-m-d H:i'});})</script>";
            return $result.'</div></div>'.$js;
        });
        Form::macro('single_file_upload', function ($name, $label, $percent=0.5) {
            $value = ForoneHtmlServiceProvider::parseValue($this->model, $name);
            $url = $value ? config('rsct.img_host').$value : '/admin/img/upload_add.png';
            return '<div class="form-group col-sm-' . ($percent * 12) . '">
                        ' . Form::form_label($label) . '
                        <div class="col-sm-9">
                            <input id="'.$name.'" type="hidden" name="' . $name . '" type="text" value="'.$value.'">
                            <img style="width:58px;height:58px;cursor:pointer;" id="'.$name.'_img" src="'.$url.'">
                        </div>
                    </div>';
        });
        Form::macro('panel_start', function ($title = '') {
            return '<div class="panel panel-default">
                        <div class="panel-heading bg-white">
                            <span class="font-bold">'.$title.'</span>
                        </div>
                    <div class="panel-body">';
        });
        Form::macro('panel_end', function ($submit_label='') {

            if (!$submit_label) {
                return '';
            }

            $result = '</div><footer class="panel-footer">
                            <button type="submit" class="btn btn-info">'.$submit_label.'</button>
                        </footer></div>';
            return $result;
        });
        Form::macro('multi_file_upload', function ($name, $label, $percent=0.5) {
            $value = ForoneHtmlServiceProvider::parseValue($this->model, $name);
            $url = '/admin/img/upload_add.png';
            $uploaded_items = '';
            if ($value) {
                $items = explode('|', $value);
                foreach ($items as $item) {
                    $details = explode('~', $item);
                    $idvalue = rand().'';
                    $div = '<div id="'.$idvalue.'div" style="float:left;width:68px;margin-right: 20px">';
                    if(preg_match("/.pdf/", $details[0])){
                        $img = '<img onclick="removeMultiUploadItem(\'' . $idvalue . 'div\',\''.$name.'\')" style="width: 68px; height: 68px;cursor:pointer"
                        src="/admin/img/upload.png">';
                    }else{
                        $img = '<img onclick="removeMultiUploadItem(\'' . $idvalue . 'div\',\''.$name.'\')" style="width: 68px; height: 68px;cursor:pointer"
                        src="http://img.rsct.com/'.$details[0].'?imageView2/1/w/68/h/68">';
                    }

                    $uploaded_items .= $div . $img;
                    $v = '';
                    if (sizeof($details) == 2) {
                        $v = "value='$details[1]'";
                    }
                    $uploaded_items .= '<input '.$v.' type="hidden" onkeyup="fillMultiUploadInput(\''.$name.'\')" style="width: 68px;float: left" placeholder="图片描述"></div>';
                }
            }

            return '<div class="form-group col-sm-' . ($percent * 12) . '">
                        ' . Form::form_label($label) . '
                        <div class="col-sm-9">
                            <input id="'.$name.'" type="hidden" name="' . $name . '" type="text" value="'.$value.'">
                            <img style="width:58px;height:58px;cursor:pointer;float:left;margin-right:20px;" id="'.$name.'_img" src="'.$url.'">
                            <label id="'.$name.'_label"></label>
                            <div id="'.$name.'_div">'.$uploaded_items.'</div>
                        </div>
                    </div>';
        });
    }

    private function macroTableHeader()
    {
        Html::macro('modal_start', function ($id, $title) {
            $html = '<div id="'.$id.'" class=" remodal" data-remodal-id="'.$id.'">
                    <input type="hidden">
                    <div>
                        <span style="font-size: 20px">'.$title.'</span>
                    </div>
                    <div class="panel-body" style="margin: 35px 0px;padding: 0;">';
            return $html;
        });
        Html::macro('json', function ($data) {
            return '<pre><code>'.json_encode($data, JSON_PRETTY_PRINT).'</code></pre>';
        });
        Html::macro('modal_end', function () {
            return '</div><div><button data-remodal-action="cancel" class="remodal-cancel" style="margin-right: 20px;">取消</button>
        <button data-remodal-action="confirm" class="remodal-confirm">确认</button></div>';
        });
        Html::macro('list_header', function ($data) {
            $html = '<div class="panel panel-default">';
            $title = isset($data['title']) ? $data['title'] : '';
            $html .= '    <div class="panel-heading">'.$title.'</div>';
            $html .= '    <div class="panel-body b-b b-light">';
            if (array_key_exists('new', $data)) {
                $html .= '<a href="' . $this->url->current() . '/create" class="btn btn-primary">&#43; 新增</a>';
            }
            if (array_key_exists('filters', $data)) {

                $result = '';
                foreach ($data['filters'] as $key => $value) {
                    $result .= '<div class="col-sm-2" style="padding-left: 0px;width: 13%">
                        <select class="form-control" name="' . $key . '">';
                    foreach ($value as $item) {
                        $value = is_array($item) ? $item['value'] : $item;
                        $label = is_array($item) ? $item['label'] : $item;
                        $selected = '';
                        $urlValue = Input::get($key);
                        if ($urlValue != null) {
                            $selected = $urlValue == $item['value'] ? 'selected="selected"' : '';
                        }
                        $result .= '<option ' . $selected . ' value="' . $value . '">' . $label . '</option>';
                    }
                    $result .= '</select></div>';
                }

                $js = "<script>init.push(function(){
                        $('select').change(function(){
                            var params = window.location.search.substring(1);
                            var paramObject = {};
                            var paramArray = params.split('&');
                            paramArray.forEach(function(param){
                                if(param){
                                    var arr = param.split('=');
                                    paramObject[arr[0]] = arr[1];
                                }
                            });
                            var baseUrl = window.location.origin+window.location.pathname;
                            if($(this).val()){
                                paramObject[$(this).attr('name')] = $(this).val();
                            }else{
                                delete paramObject[$(this).attr('name')];
                            }
                            window.location.href = $.param(paramObject) ? baseUrl+'?'+$.param(paramObject) : baseUrl;
                        });
                    })</script>";
                $html .= $result . $js;
            }

            if (array_key_exists('search',$data)) {
                $search = is_bool($data['search']) ? '请输入您想检索的信息' : $data['search'];
                $html .= '<div class="col-md-3" style="padding-left:0px; float: right;width: 17%">
                                <input id="keywordsInput" type="text" class="form-control input" name="keywords" value="'.Input::get('keywords').'" placeholder="'.$search.'"  />
                            </div>';
                $js = "<script>init.push(function(){
                    $('#keywordsInput').keyup(function(event){
                        if(event.keyCode == 13){
                            console.log('do search');
                            var params = window.location.search.substring(1);
                            var paramObject = {};
                            var paramArray = params.split('&');
                            paramArray.forEach(function(param){
                                if(param){
                                    var arr = param.split('=');
                                    paramObject[arr[0]] = arr[1];
                                }
                            });
                            var baseUrl = window.location.origin+window.location.pathname;
                            if($(this).val()){
                                paramObject[$(this).attr('name')] = $(this).val();
                            }else{
                                delete paramObject[$(this).attr('name')];
                            }
                            window.location.href = $.param(paramObject) ? baseUrl+'?'+$.param(paramObject) : baseUrl;
                        }
                    });
                });</script>";
                $html .= $js;
            }

            $html .= '</div>';
            return $html;
        });
    }

    /**
     *
     */
    private function macroTableFooter() {
        Html::macro('footer', function ($data) {
            return $data ? $data->render() : '';
        });
    }

    private function macroDataGrid() {
        Html::macro('datagrid', function ($data) {

            $html = '<table class="table m-b-none" data-sort="false" ui-jp="footable">';
            $columns = $data['columns'];
            $items = $data['items'];
            $heads = [];
            $widths = [];
            $fields = [];
            $functions = [];

            // build table head
            $html .= '<thead><tr>';
            foreach ($columns as $column) {
                array_push($heads, $column[0]); // title
                array_push($fields, $column[1]); // fields
                $size = sizeof($column);
                switch ($size) {
                    case 2:
                        array_push($widths, 0); // width
                        break;
                    case 3:
                        if (is_int($column[2])) {
                            array_push($widths, $column[2]);
                        }else{
                            array_push($widths, 0);
                            $functions[$column[1]] = $column[2];
                        }
                        break;
                    case 4:
                        array_push($widths, $column[2]);
                        $functions[$column[1]] = $column[3];
                        break;
                }
            }

            foreach ($heads as $head) {
                $index = array_search($head,$heads);
                $class = '';
                $dataToggle = '';
                if ($index == 0) {
                    $first = 'footable-first-column ';
                    $dataToggle = 'data-toggle="true"';
                    $class .= $first;
                }
                if ($index == sizeof($heads)) {
                    $class .= 'footable-last-column ';
                }
                if ($index <= 1) {
                    $class .= 'footable-visible ';
                }else if ($index < 4) {
                    $dataToggle .= ' data-hide="phone"';
                }else{
                    $dataToggle .= ' data-hide="tablet,phone"';
                }

                if ($widths[ $index ]) {
                    $dataToggle .= ' style="width:'.$widths[$index].'px"';
                }

                $item = '<th ' . $dataToggle . ' class="' . $class . '" >' . $head . '</th>';
                $html .= $item;
            }
            $html .= '</tr></thead>';

            $html .= '<tbody>';
            if ($items) {
                foreach ($items as $item) {
                    $html .= '<tr>';
                    foreach ($fields as $field) {
                        $index = array_search($field,$fields);
                        $html .= $widths[$index] ? '<td style="width: '.$widths[$index].'px">':'<td>';
                        if ($field == 'buttons') {
                            $buttons = $functions[$field]($item);
                            foreach ($buttons as $button) {
                                $size = sizeof($button);
                                if ($size == 1) {
                                    $value = $button[0];
                                    if ($value == '禁用') {
                                        $html .= Form::form_button([
                                            'name'  => $value,
                                            'id'    => $item->id,
                                            'class' => 'bg-warning'
                                        ], ['enabled' => false]);
                                    } else if ($value == '启用') {
                                        $html .= Form::form_button([
                                            'name'  => $value,
                                            'id'    => $item->id,
                                            'class' => 'btn-success'
                                        ], ['enabled' => true]);
                                    } else if ($value == '查看') {
                                        $html .= '<a href="' . $this->url->current() . '/' . $item->id . '">
                                                    <button class="btn">查看</button></a>';
                                    } else if ($value == '编辑') {
                                        $html .= '<a href="' . $this->url->current() . '/' . $item->id . '/edit">
                                                    <button class="btn">编辑</button></a>';
                                    }
                                }else{
                                    $getButton = sizeof($button) > 2 ? true : false;
                                    $config = $getButton ? $button : $button[0];
                                    $data = $getButton ? [] : $button[1];
                                    if (is_string($data) && strripos($data, '#') == 0) {
                                        $html .= Form::modal_button($config, $data, $item);
                                    } else {
                                        if (array_key_exists('method', $config) && $config['method'] == 'GET') {
                                            $uri = array_key_exists('uri', $config) ? $config['uri'] : '';
                                            $params = array_key_exists('params', $config) ? $config['params'] : '';
                                            if ($params) {
                                                $params = explode(',', $params);
                                                $query = [];
                                                foreach ($params as $key) {
                                                    $query[$key] = $item[$key];
                                                }
                                                $uri .= '?'.http_build_query($query);
                                                $config['uri'] = $uri;
                                            }
                                        }else{
                                            $config['id'] = $item->id;
                                        }
                                        $html .= Form::form_button($config, $data);
                                    }
                                }
                            }
                        } else {
                            if (array_key_exists($field, $functions)) {
                                $value = $functions[$field]($item[$field]);
                            }else{
                                $arr = explode('.', $field);
                                if (sizeof($arr) == 2) {
                                    $value = $item[$arr[0]][$arr[1]];
                                } else {
                                    $value = $item[$field];
                                }
                            }
                            $html .= $value . '</td>';
                        }
                    }
                    $html .= '</tr>';
                }
            }
            $html .= '<tbody>';

            $html .= '<tfoot>';
            $html .= ' <tr>';
            $html .= '    <td colspan="10" class="text-center">';
            $html .= $items ? $items->render() : '';
            $html .= '  </td>';
            $html .= ' </tr>';
            $html .= '</tfoot>';
            $html .= '</table></div></div>';
            $js = "<script>init.push(function(){
                   $('.fancybox').fancybox({
                    openEffect  : 'none',
                    closeEffect : 'none'
  });
                });</script>";
            $html .= $js;
            return $html;
        });
    }

    private function extendHtml()
    {
        $this->macroTableHeader();
        $this->macroDataGrid();
        $this->macroTableFooter();
    }
}