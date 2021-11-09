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
                $new = $this->request->input($this->row->field.'_new');
                $files[$i]['name'] = $path;
                $files[$i]['alt'] = empty($new[$i]['alt']) ? '' : $new[$i]['alt'];
                $files[$i]['title'] = empty($new[$i]['title']) ? '' : $new[$i]['title'];
                $files[$i]['description'] = empty($new[$i]['description']) ? '' : $new[$i]['description'];
            }
        }

        return json_encode($files);
    }
}
