<?php

namespace  Avast\Formfields\ContentTypes;

use TCG\Voyager\Http\Controllers\ContentTypes\BaseType;
use TCG\Voyager\Http\Controllers\ContentTypes\MultipleImage;

class AttrGalleryContentType extends BaseType
{
    /**
     * @return string
     */
    public function handle()
    {
        $files = [];
        if ($this->request->file($this->row->field)){
            $pathes = (new MultipleImage($this->request, $this->slug, $this->row, $this->options))->handle();
            foreach (json_decode($pathes) as $i => $path) {
                $files[$i]['name'] = $path;
                $files[$i]['alt'] = '';
                $files[$i]['title'] = '';
                $files[$i]['description'] = '';
            }

        }
        return json_encode($files);

    }
}
