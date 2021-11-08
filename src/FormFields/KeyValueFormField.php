<?php

namespace Avast\Formfields\FormFields;

use TCG\Voyager\FormFields\AbstractHandler;

class KeyValueFormField extends AbstractHandler
{
    protected $codename = 'key_value';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('avast-formfields::formfields.key_value', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
        ]);
    }

}
