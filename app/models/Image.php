<?php
use app\library\UploadImage;
use app\library\CreateImageOnFly;
class Image extends ModelBase
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $type;

    /**
     *
     * @var integer
     */
    public $factor_id;

    /**
     *
     * @var string
     */
    public $created_at=0;

    /**
     *
     * @var string
     */
    public $reference_keys;

    /**
     *
     * @var string
     */
    public $image;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'image';
    }

    public function initialize()
    {
        parent::initialize();
    }
    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Image[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Image
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }
    //  Save the image file
    public function saveFile($file, $file_name, $width = 0, $height = 0)
    {
        // load the image
        $image = new \UploadImage();
        $image->load($file['tmp_name'], $this->type);
        if ($width != 0 && $height != 0) {
            $image->resize($width, $height);
        }
        $path = "img/" . date("Y") . "/" . $this->type . "/" . $file_name . $image->FileExtension($file["type"]);
        $config = \Phalcon\Di::getDefault()->getShared('configuration');
        $image->save(
            $config->imgPath. "" . date("Y") . "/" . $this->type . "/" . $file_name . $image->FileExtension($file["type"])
        );
        $this->image = $path;
        $this->save();
    }


    /**
     * @param $data
     * @return bool
     * save data from array with file upload
     */
    public function saveFromArray($data)
    {


        //first delete old image
        if( ! @isset( $data['additional_image']) && ! $data['additional_image'] )
            \Image::deleteByFilter(' type = "' . $data['type'] . '" and
                    factor_id = "' . $data['factor_id'] . '" and
                    reference_keys = "' . $data['reference_keys'] . '" ' );

        //set post to be like i submitted from image controller
        $data['reference_keys'] =$data['reference_keys'];//todo remove this later when reneame reference_keys
        $this->assign($data);

        if (!$this->saveFile($data["image"], time())) {
            //$this->setValidationMessage('unable to save file');
            //return false ;
        }

        if (!$this->save()) {
            $this->setValidationMessage($this->getValidationMessageText());
            return false;
        }
        return true;
    }


    /**
     * @param bool|false $reference_keys
     * @return string
     * for admin forms for getting images as html structure
     */
    public function getImageHTML($reference_key = false)
    {
        $images = $this->getImages($reference_key);
        $image_html = '<ul class="images-ul">';
        foreach ($images as $image_details) {

            $image_html .= $image_details ? '
                <li><img src="' . $image_details->getImageUrl() . '"></li>'
                : '';

        }
        $image_html .= '</ul>';
        return $image_html;
    }
    /**
     * @param bool|false $reference_keys
     * @return string
     * for admin forms for getting images as html structure
     */
    public function getImageUrl()
    {
        return CreateImageOnFly::CreateImage('public/'.$this->image,$this->reference_keys,$this->type);
    }

    /**
     * @param bool|false $reference_keys
     * @return Image[]
     * get images for current object
     */
    public function getImages($reference_keys = false)
    {
        $images = Image::find([
            'id = ' . $this->id
        ]);
        return $images;
    }

    /**
     * get default image url
     */
    public static function getDefaultImageUrl($class,$reference_keys = 'default')
    {
        //Get the base url
        $url=\Phalcon\Di::getDefault()->getShared('url');
        $url=$url->getBaseUri();
        // model folder paths
        $model_path="../../"."public/img/default_images/".$class.'/'. $reference_keys . '/default.jpg';
        if( !file_exists($model_path) )
            return $url.'public/img/default_images/'.'default.jpg';

        return $url.'public/img/default_images/'.$class.'/'. $reference_keys .'/'. 'default.jpg';
    }
}
