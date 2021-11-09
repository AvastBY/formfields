<?php

namespace Avast\Formfields\FormFields;

use TCG\Voyager\FormFields\AbstractHandler;

class AttributedGalleryFormField extends AbstractHandler
{
    protected $codename = 'attributed_gallery';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('avast-formfields::formfields.attributed_gallery',[
            'row' => $row,
            'options' => $options,
            'dataType' => $dataType,
            'dataTypeContent' => $dataTypeContent,
        ]);
    }
}
