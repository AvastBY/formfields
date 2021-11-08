<?php

namespace  Avast\Formfields\ContentTypes;
use TCG\Voyager\Http\Controllers\ContentTypes\BaseType;

class ValuesListContentType extends BaseType
{
    /**
     * @return null|string
     */
    public function handle()
    {
        $value = $this->request->input($this->row->field);

        $new_parameters = array();
        foreach ($value as $key => $val) {
            if($value[$key]){
                $new_parameters[] = $value[$key];
            }
        }

        return json_encode($new_parameters);
    }
}
