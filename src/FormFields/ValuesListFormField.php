<?php

namespace Avast\Formfields\FormFields;

use TCG\Voyager\FormFields\AbstractHandler;

class ValuesListFormField extends AbstractHandler
{
    protected $codename = 'values_list';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('avast-formfields::formfields.values_list', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
        ]);
    }

}
