<?php

namespace Avast\Formfields\Http\Controllers;

use Illuminate\Http\Request;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;
use TCG\Voyager\Http\Controllers\VoyagerMediaController;
use TCG\Voyager\Http\Controllers\Controller;
use TCG\Voyager\Facades\Voyager;

use  Avast\Formfields\ContentTypes\AttrGalleryContentType;
use  Avast\Formfields\ContentTypes\KeyValueContentType;
use  Avast\Formfields\ContentTypes\ValuesListContentType;

class FormFieldsController extends VoyagerBaseController
{

    public function getContentBasedOnType(Request $request, $slug, $row, $options = null)
    {

        switch ($row->type) {

            case 'values_list':
                return (new ValuesListContentType($request, $slug, $row, $options))->handle();
            case 'key_value':
                return (new KeyValueContentType($request, $slug, $row, $options))->handle();
            case 'attributed_gallery':
                return (new AttrGalleryContentType($request, $slug, $row, $options))->handle();
            default:
                return Controller::getContentBasedOnType($request, $slug, $row, $options);
        }
    }


    public function insertUpdateData($request, $slug, $rows, $data)
    {

        foreach ($rows as $row) {
            if ($row->type == 'attributed_gallery'){
                $attributed_gallery= 1;
                $fieldName = $row->field;
                $ex_files = json_decode($data->{$row->field}, true);
                $request->except("{$row->field}");
            }
        }

        $new_data = VoyagerBaseController::insertUpdateData($request, $slug, $rows, $data);

        if(isset($attributed_gallery)){
            foreach ($rows as $row) {
                $content = $new_data->$fieldName;
                $content = json_decode($content, 1);
                foreach ($content as $key => $it) {
                    $fname = $row->field.'_new';
                    if(!empty($request->$fname[$key])){
                        $content[$key] = array_merge($content[$key], array_diff($request->$fname[$key], [null]));
                    }
                }

                $content = json_encode($content);
                if ($row->type == 'attributed_gallery' && !is_null($content) && $ex_files != json_decode($content,1)) {
                    if (isset($data->{$row->field})) {
                        if (!is_null($ex_files)) {
                            $content = json_encode(array_merge($ex_files, json_decode($content,1)));
                        }
                    }
                    $new_content = $content;
                }
            }
            if(isset($new_content)) $content = json_decode($new_content,1);
                else $content = json_decode($content,1);

            if(isset($content)){
            	$end_content = array();
                foreach ($content as $i => $value) {
                    if(isset($request->{$fieldName.'_ext'}[$i])){
                        $end_content[] = array_merge($content[$i], $request->{$fieldName.'_ext'}[$i]);
                    }else{
                        $end_content[] = $content[$i];
                    }
                }

                $data->{$fieldName} = json_encode($end_content);
            }
            $data->save();

            return $data;
        } else {
            return $new_data;
        }
    }

    public function remove(Request $request){
    	self::remove_media($request);
	}
    public function remove_media(Request $request)
    {
        if($request->get('multiple_ext')){
            try {
                // GET THE SLUG, ex. 'posts', 'pages', etc.
                $slug = $request->get('slug');

                // GET image name
                $image = $request->get('image');

                // GET record id
                $id = $request->get('id');

                // GET field name
                $field = $request->get('field');

                // GET THE DataType based on the slug
                $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();
                $dataRow = Voyager::model('DataRow')
                                    ->where('data_type_id', '=', $dataType->id)
                                    ->where('type', '=', 'attributed_gallery')
                                    ->first();

                // Check permission
                //Voyager::canOrFail('delete_'.$dataType->name);
                $this->authorize('delete', app($dataType->model_name));

                $exploded = explode(".", $image);

                // Image
                $image = $exploded[0].'.'.$exploded[1];

                // Remove image storage path
                $pathDelete = storage_path('app\public\\').$image;
                @unlink($pathDelete);


                // Load model and find record
                $model = app($dataType->model_name);
                $data = $model::find([$id])->first();

                // Decode field value
                $fieldData = @json_decode($data->{$field}, true);
                foreach ($fieldData as $i => $single) {
                    // Check if image exists in array
                    if(in_array($image,array_values($single)))
                        $founded = $i;
                }

                // Remove image from array
                unset($fieldData[@$founded]);

                // Generate json and update field
                $data->{$field} = json_encode($fieldData);

                $data->save();

                return response()->json([
                   'data' => [
                       'status'  => 200,
                       'message' => __('voyager::media.image_removed'),
                   ],
                ]);
            } catch (Exception $e) {
                $code = 500;
                $message = __('voyager::generic.internal_error');

                if ($e->getCode()) {
                    $code = $e->getCode();
                }

                if ($e->getMessage()) {
                    $message = $e->getMessage();
                }

                return response()->json([
                    'data' => [
                        'status'  => $code,
                        'message' => $message,
                    ],
                ], $code);
            }
        } else{
            return VoyagerBaseController::remove_media($request);
        }
    }
}
