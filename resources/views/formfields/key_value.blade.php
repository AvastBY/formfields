@php
    if(!empty($dataTypeContent->{$row->field})){
        if(is_array($dataTypeContent->{$row->field})){
            $old_parameters = $dataTypeContent->{$row->field};
        }else{
            $old_parameters = json_decode($dataTypeContent->{$row->field});
        }
        foreach ($old_parameters as $key => $it) {
            if(is_null($it->key) && is_null($it->value)) unset($old_parameters[$key]);
        }
    }

    $end_id = 0;
@endphp


<div class="custom-parameters">
@if(!empty($dataTypeContent->{$row->field}))
    @foreach($old_parameters as $parameter)
        <div class="form-group row" row-id="{{$loop->index}}">
            <div class="col-md-5" style="margin-bottom:0;">
                <input type="text" class="form-control" name="{{ $row->field }}[{{$loop->index}}][key]" value="{{ $parameter->key }}" id="key" autocomplete="off"/>
            </div>
            <div class="col-md-6" style="margin-bottom:0;">
                <input type="text" class="form-control" name="{{ $row->field }}[{{$loop->index}}][value]" value="{{ $parameter->value }}" id="value" autocomplete="off"/>
            </div>

            <div class="col-md-1" style="margin-bottom:0;">
                <button type="button" class="btn btn-xs" style="margin-top:0px;"><i class="voyager-trash"></i></button>
            </div>
        </div>
        @php
            $end_id = $loop->index + 1;
        @endphp
    @endforeach
@endif
    <div class="form-group row" row-id="{{ $end_id }}">
        <div class="col-md-5" style="margin-bottom:0;">
            <input type="text" class="form-control" name="{{ $row->field }}[{{ $end_id }}][key]" value="" id="key" autocomplete="off"/>
        </div>
        <div class="col-md-6" style="margin-bottom:0;">
            <input type="text" class="form-control" name="{{ $row->field }}[{{ $end_id }}][value]" value="" id="value" autocomplete="off"/>
        </div>
        <div class="col-md-1" style="margin-bottom:0;">
            <button type="button" class="btn btn-success btn-xs" style="margin-top:0px;"><i class="voyager-plus"></i></button>
        </div>
    </div>

    <input type="hidden" name="keyvaluejson" value="{{$row->field}}"/>
</div>


<style>
    .custom-parameters{
        width: 100%;
    }
</style>
<script>

    function editNameCount(el){
        var str = el.getAttribute('name');
        var old_id = parseInt(el.parentNode.parentNode.getAttribute('row-id'));
        new_str = str.substring(0,str.indexOf('[')+1)
                    + (old_id+1)
                    + str.substring(str.indexOf(']'), str.length);
        return(new_str);
    }

    function addRow(){
        var new_row = this.parentNode.parentNode.cloneNode(true);

        new_row.querySelector("#key").setAttribute('name', editNameCount(new_row.querySelector("#key")));
        new_row.querySelector("#key").value = '';
        new_row.querySelector("#value").setAttribute('name', editNameCount(new_row.querySelector("#value")));
        new_row.querySelector("#value").value = '';
        new_row.setAttribute('row-id', parseInt(this.parentNode.parentNode.getAttribute('row-id'))+1)

        this.classList.remove('btn-success');
        this.innerHTML = '<i class="voyager-trash"></i>';
        new_row.querySelector('.btn-success').onclick = this.onclick;
        this.onclick = removeRow;
        this.parentNode.parentNode.parentNode.appendChild(new_row);
    };

    function removeRow() {
        this.parentNode.parentNode.remove();
    }

    var buttons = document.querySelectorAll('.custom-parameters .btn');
    for (var i = 0; i < buttons.length; i++) buttons[i].onclick = removeRow;
    var suc_buttons = document.querySelectorAll('.custom-parameters .btn-success');
    suc_buttons[suc_buttons.length - 1].onclick = addRow;

</script>


